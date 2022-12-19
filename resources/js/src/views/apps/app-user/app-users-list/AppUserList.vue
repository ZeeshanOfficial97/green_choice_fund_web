<template>
  <div>
    <!-- Filters -->
    <app-user-list-filters
      :user-type-filter.sync="userTypeFilter"
      :status-filter.sync="statusFilter"
      :user-type-options="userTypeOptions"
      :status-options="statusOptions"
    />

    <!-- Table Container Card -->
    <b-card no-body class="mb-0">
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col
            cols="12"
            md="6"
            class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
          >
            <label>Show</label>
            <v-select
              v-model="perPage"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="perPageOptions"
              :clearable="false"
              class="per-page-selector d-inline-block mx-50"
            />
            <label>entries</label>
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div class="d-flex align-items-center justify-content-end">
              <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              />
              <!-- <b-button
                variant="primary"
                @click="isAddNewUserSidebarActive = true"
              >
                <span class="text-nowrap">Add User</span>
              </b-button> -->
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        ref="refUserListTable"
        class="position-relative"
        :items="fetchUsers"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: User -->
        <template #cell(name)="data">
          <b-media vertical-align="center">
            <template #aside>
              <b-avatar
                size="32"
                :src="data.item.profile_photo_url"
                :text="avatarText(data.item.name)"
              />
            </template>
            <!-- <b-link class="font-weight-bold d-block text-nowrap">
              {{ data.item.name }}
            </b-link> -->
            <div style="margin-top: 6px">
              {{ data.item.name }}
            </div>
          </b-media>
        </template>

        <!-- Column: Status -->
        <template #cell(status)="data">
          <b-badge
            pill
            :variant="`light-${resolveUserStatusVariant(data.item.status)}`"
            class="text-capitalize"
          >
            {{ data.item.status }}
          </b-badge>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown
            variant="link"
            no-caret
            :right="$store.state.appConfig.isRTL"
          >
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
              />
            </template>
            <b-dropdown-item
              :to="{
                name: 'apps-app-users-view',
                params: { id: data.item.id },
              }"
            >
              <feather-icon
                icon="FileTextIcon"
                :to="{
                  name: 'apps-categories-edit',
                  params: { id: data.item.id },
                }"
              />
              <span
                class="align-middle ml-50"
                :to="{
                  name: 'apps-categories-edit',
                  params: { id: data.item.id },
                }"
                >View</span
              >
            </b-dropdown-item>
          </b-dropdown>
        </template>
      </b-table>

      <div class="mx-2 mb-2">
        <b-row>
          <b-col
            cols="12"
            sm="6"
            class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-start
            "
          >
            <span class="text-muted"
              >Showing {{ dataMeta.from }} to {{ dataMeta.to }} of
              {{ dataMeta.of }} entries</span
            >
          </b-col>
          <!-- Pagination -->
          <b-col
            cols="12"
            sm="6"
            class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-end
            "
          >
            <b-pagination
              v-model="currentPage"
              :total-rows="totalUsers"
              :per-page="perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
              <template #prev-text>
                <feather-icon icon="ChevronLeftIcon" size="18" />
              </template>
              <template #next-text>
                <feather-icon icon="ChevronRightIcon" size="18" />
              </template>
            </b-pagination>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BButton,
  BTable,
  BMedia,
  BAvatar,
  BLink,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
} from "bootstrap-vue";
import vSelect from "vue-select";
import store from "@/store";
import { ref, onUnmounted } from "@vue/composition-api";
import { avatarText } from "@core/utils/filter";
import AppUserListFilters from "./AppUserListFilters.vue";
// import useUsersList from './useUsersList'
import useAppUserList from "./useAppUserList";
// import userStoreModule from '../userStoreModule'
import appuserStoreModule from "../appuserStoreModule";

export default {
  components: {
    AppUserListFilters,

    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,

    vSelect,
  },
  setup() {
    const USER_APP_STORE_MODULE_NAME = "app-user";

    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME))
      store.registerModule(USER_APP_STORE_MODULE_NAME, appuserStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME))
        store.unregisterModule(USER_APP_STORE_MODULE_NAME);
    });

    const userTypeOptions = [
      { label: "Normal User", value: "1" },
      { label: "Institution User", value: "2" },
    ];

    const statusOptions = [
      { label: "All", value: null },
      { label: "Active", value: "1" },
      { label: "Inactive", value: "0" },
    ];

    const {
      fetchUsers,
      tableColumns,
      totalUsers,
      currentPage,
      perPage,
      perPageOptions,
      dataMeta,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refUserListTable,
      refetchData,
      resolveUserStatusVariant,
      // Extra Filters
      userTypeFilter,
      statusFilter,
    } = useAppUserList();

    return {
      // Sidebar
      // isAddNewUserSidebarActive,

      fetchUsers,
      tableColumns,
      totalUsers,
      perPage,
      currentPage,
      perPageOptions,
      dataMeta,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refUserListTable,
      refetchData,
      resolveUserStatusVariant,

      // Filter
      avatarText,

      statusOptions,
      userTypeOptions,

      // Extra Filters
      userTypeFilter,
      statusFilter,
    };
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
