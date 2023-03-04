import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useInquiryList() {
  // Use toast
  const toast = useToast()

  const refInquiryListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', sortable: true },
    { key: 'email', sortable: true },
    { key: 'phone', sortable: false },
    {
      key: 'contact_reason',
      label: 'Contact Reason',
      formatter: title,
      sortable: false,
    },
    { key: 'date', sortable: false },
    { key: 'status', sortable: false },
    { key: 'actions' },
  ]
  const totalInquiries = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const reasonFilter = ref(null)
  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refInquiryListTable.value ? refInquiryListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalInquiries.value,
    }
  })

  const refetchData = () => {
    refInquiryListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, reasonFilter, statusFilter], () => {
    refetchData()
  })

  const fetchInquiries = (ctx, callback) => {
    const appLoading = document.getElementById("loading-bg-content");
    if (appLoading) {
      appLoading.style.display = "block";
    }
    store
      .dispatch('app-user-inquiry/fetchInquiries', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        reason: reasonFilter.value,
        status: statusFilter.value,
      })
      .then(response => {
        const inquiries = response.data.data.list;
        const total = response.data.data?.pagination?.total || 0;
        callback(inquiries)
        totalInquiries.value = total
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      })
      .catch((err,) => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching inquiries list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      })
  }

  const fetchInquiryReasons = () => {
    store
      .dispatch('app-user-inquiry/fetchInquiryReasons')
      .then(response => { response.data.data; })
      .catch((err) => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching inquiry reasons',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*


  const resolveUserStatusVariant = status => {
    if (status === 'active') return 'success'
    if (status === 'inactive') return 'danger'
    return 'primary'
  }

  return {
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
    resolveUserStatusVariant,
    refetchData,

    // Extra Filters
    reasonFilter,
    statusFilter
  }
}
