<template>

  <div>

    <!-- Filters -->
    <inquiry-list-filters
      :reason-filter.sync="reasonFilter"
      :status-filter.sync="statusFilter"
      :reason-options="reasonOptions"
      :status-options="statusOptions"
    />

    <!-- Table Container Card -->
    <b-card
      no-body
      class="mb-0"
    >

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
          <b-col
            cols="12"
            md="6"
          >
            <div class="d-flex align-items-center justify-content-end">
              <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              />
            </div>
          </b-col>
        </b-row>

      </div>

      <b-table
        ref="refInquiryListTable"
        class="position-relative"
        :items="fetchInquiries"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >

        <!-- Column: Contact Reason -->
        <template #cell(contact_reason)="data">
          <div class="text-nowrap">
            <span class="align-text-top text-capitalize">{{ data.item.contact_reason.name }}</span>
          </div>
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
            <b-dropdown-item :to="{ name: 'apps-inquiries-view', params: { id: data.item.id } }">
              <feather-icon icon="FileTextIcon" :to="{ name: 'apps-inquiries-view', params: { id: data.item.id } }" />
              <span class="align-middle ml-50" :to="{ name: 'apps-inquiries-view', params: { id: data.item.id } }">Details</span>
            </b-dropdown-item>

          </b-dropdown>
        </template>

      </b-table>
      <div class="mx-2 mb-2">
        <b-row>

          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-start"
          >
            <span class="text-muted">Showing {{ dataMeta.from }} to {{ dataMeta.to }} of {{ dataMeta.of }} entries</span>
          </b-col>
          <!-- Pagination -->
          <b-col
            cols="12"
            sm="6"
            class="d-flex align-items-center justify-content-center justify-content-sm-end"
          >

            <b-pagination
              v-model="currentPage"
              :total-rows="totalInquiries"
              :per-page="perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
              <template #prev-text>
                <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
                />
              </template>
              <template #next-text>
                <feather-icon
                  icon="ChevronRightIcon"
                  size="18"
                />
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
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import store from '@/store'
import { ref, onUnmounted, reactive } from '@vue/composition-api'
import { avatarText } from '@core/utils/filter'
import InquiryListFilters from './InquiryListFilters.vue'
import useInquiryList from './useInquiryList'
import inquiryStoreModule from '../inquiryStoreModule'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import axios from '@axios'

export default {
  components: {
    InquiryListFilters,

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
    ToastificationContent
  },
  setup() {
    const toast = useToast()
    const INQUIRY_APP_STORE_MODULE_NAME = 'app-user-inquiry'

    // Register module
    if (!store.hasModule(INQUIRY_APP_STORE_MODULE_NAME)) store.registerModule(INQUIRY_APP_STORE_MODULE_NAME, inquiryStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(INQUIRY_APP_STORE_MODULE_NAME)) store.unregisterModule(INQUIRY_APP_STORE_MODULE_NAME)
    })

    var reasonOptions = [
      { label: 'Customer', value: '51' },
      { label: 'Press/Media', value: '52' },
      { label: 'Investor relations', value: '53' },
      { label: 'Charity', value: '54' },
      { label: 'Pledge', value: '55' },
      { label: 'Private equity', value: '56' },
      { label: 'SPAC', value: '57' },
      { label: 'SPV', value: '58' },
      { label: 'DAOs', value: '59' },
      { label: 'Carbon Credits', value: '60' },
      { label: 'Blockchain', value: '61' },
      { label: 'ICO', value: '62' },
      { label: 'Governance', value: '63' },
    ];

    const statusOptions = [
      { label: 'All', value: null },
      { label: 'Active', value: '1' },
      { label: 'Inactive', value: '0' },
    ]

    // axios.get('/inquiry/reasons')
    // .then(response => {
    //     
    //     reasonOptions = response.data.data;
    //   })
    //   .catch((err) => {
    //     toast({
    //       component: ToastificationContent,
    //       props: {
    //         title: 'Error fetching inquiry reasons',
    //         icon: 'AlertTriangleIcon',
    //         variant: 'danger',
    //       },
    //     })
    //   });

    const {
      fetchInquiries,
      fetchInquiryReasons,
      tableColumns,
      totalInquiries,
      currentPage,
      perPage,
      perPageOptions,
      dataMeta,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refInquiryListTable,
      refetchData,

      // UI
      resolveUserStatusVariant,

      // Extra Filters
      reasonFilter,
      statusFilter,
    } = useInquiryList()

    return {
      // Sidebar

      fetchInquiries,
      tableColumns,
      totalInquiries,
      currentPage,
      perPage,
      perPageOptions,
      dataMeta,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refInquiryListTable,
      refetchData,

      // UI
      resolveUserStatusVariant,

      reasonOptions,
      statusOptions,

      // Extra Filters
      reasonFilter,
      statusFilter,
    }
  },
}
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>
