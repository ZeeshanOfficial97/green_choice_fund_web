import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useUsersList() {
  // Use toast
  const toast = useToast()

  const refInvestmentListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    {
      key: 'investment_num', sortable: true, label: 'Investment #', formatter: title
    },
    // { key: 'email', sortable: true },
    { key: 'invested_amount', sortable: true, label: 'Amount', formatter: title, tdClass: 'text-right' },
    { key: 'stripe_charge_id', label: 'Stripe Charge ID', sortable: false },
    { key: 'stripe_user_id', label: 'Stripe User ID', sortable: false },
    { key: 'investment_status', sortable: false, label: 'Status', formatter: title },
    { key: 'created_at', sortable: false, label: 'Date', formatter: title },
    { key: 'actions' },
  ]
  const totalInvestments = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refInvestmentListTable.value ? refInvestmentListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalInvestments.value,
    }
  })

  const refetchData = () => {
    refInvestmentListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, statusFilter], () => {
    refetchData()
  })

  const fetchInvestments = (ctx, callback) => {
    const appLoading = document.getElementById("loading-bg-content");
    if (appLoading) {
      appLoading.style.display = "block";
    }
    store
      .dispatch('app-user-investment/fetchInvestments', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        status: statusFilter.value,
      })
      .then(response => {

        // const { users, total } = response.data
        const investments = response.data.data.list;
        const total = response.data.data.pagination.total;
        callback(investments)
        totalInvestments.value = total
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching investments list',
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

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolveInvestmentStatusVariant = status => {
    if (status === 'Pending') return 'warning'
    if (status === 'Verified') return 'info'
    if (status === 'In Process') return 'success'
    if (status === 'Processed') return 'success'
    if (status === 'Cancelled') return 'danger'
    return 'primary'
  }

  return {
    fetchInvestments,
    tableColumns,
    totalInvestments,
    currentPage,
    perPage,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refInvestmentListTable,

    resolveInvestmentStatusVariant,
    refetchData,

    // Extra Filters
    statusFilter,
  }
}
