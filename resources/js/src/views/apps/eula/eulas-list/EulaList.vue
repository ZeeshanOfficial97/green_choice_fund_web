<template>
  <div>
    <eula-list-add-new
      :is-add-new-eula-sidebar-active.sync="
        isAddNewEulaSidebarActive
      "
      @refetch-data="refetchData"
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
              <b-button
                variant="primary"
                @click="isAddNewEulaSidebarActive = true"
              >
                <span class="text-nowrap">Add Eula</span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        ref="refEulaListTable"
        class="position-relative"
        :items="fetchEulas"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: File -->
        <template #cell(file_url)="data">
          <a :href="data.item.file_url" target="_blank">
            <span>View eula</span>
          </a>
        </template>

        <!-- Column: Status -->
        <template #cell(status)="data">
          <b-badge
            pill
            :variant="`light-${resolveEulaStatusVariant(
              data.item.status
            )}`"
            class="text-capitalize"
          >
            {{ data.item.status }}
          </b-badge>
        </template>

        <!-- Column: Actions -->
        <!-- <template #cell(actions)="data">
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
                name: 'apps-categories-edit',
                params: { id: data.item.id },
              }"
            >
              <feather-icon
                icon="EditIcon"
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
                >Edit</span
              >
            </b-dropdown-item>

            <b-dropdown-item @click.self="confirmDelete(data.item.id)">
              <feather-icon
                icon="TrashIcon"
                @click.self="confirmDelete(data.item.id)"
              />
              <span
                class="align-middle ml-50"
                @click.self="confirmDelete(data.item.id)"
                >Delete</span
              >
            </b-dropdown-item>
          </b-dropdown>
        </template> -->
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
              :total-rows="totalEulas"
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

import useEulaList from "./useEulaList";
import eulaStoreModule from "../eulaStoreModule";

import EulaListAddNew from "./EulaListAddNew.vue";

import SweetAlertOption from "@/views/extensions/sweet-alert/SweetAlertConfirmOption.vue";
// Notification
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    EulaListAddNew,
    SweetAlertOption,
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
    const Eula_APP_STORE_MODULE_NAME = "app-eula";

    // Register module
    if (!store.hasModule(Eula_APP_STORE_MODULE_NAME))
      store.registerModule(
        Eula_APP_STORE_MODULE_NAME,
        eulaStoreModule
      );

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(Eula_APP_STORE_MODULE_NAME))
        store.unregisterModule(Eula_APP_STORE_MODULE_NAME);
    });

    const isAddNewEulaSidebarActive = ref(false);

    const statusOptions = [
      { label: "Active", value: 1 },
      { label: "Inactive", value: 0 },
    ];

    const {
      fetchEulas,
      tableColumns,
      perPage,
      currentPage,
      totalEulas,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refEulaListTable,
      refetchData,

      resolveEulaStatusVariant,

      statusFilter,
    } = useEulaList();

    return {
      // Sidebar
      isAddNewEulaSidebarActive,

      fetchEulas,
      tableColumns,
      perPage,
      currentPage,
      totalEulas,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refEulaListTable,
      refetchData,

      resolveEulaStatusVariant,

      statusOptions,

      // Extra Filters
      statusFilter,
    };
  },
  methods: {
    // Confirm Delete
    confirmDelete(id) {
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
        allowOutsideClick: true,
      }).then((result) => {
        if (result.value) {
          store
            .dispatch("app-category/deleteCategory", {
              id: id,
            })
            .then((response) => {
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: response?.data?.message,
                  icon: "AlertTriangleIcon",
                  variant:
                    response?.data?.code === 200
                      ? "success"
                      : response?.data?.code === 610
                      ? "danger"
                      : "danger",
                },
              });

              if (response?.data?.code === 200) {
                this.refetchData();
              }
            })
            .catch((err) => {
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: err?.message
                    ? err.message
                    : err?.response?.data?.message,
                  icon: "AlertTriangleIcon",
                  variant: "danger",
                },
              });
            });
        }
      });
    },
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
@import "~@resources/scss/vue/libs/vue-sweetalert.scss";
</style>
