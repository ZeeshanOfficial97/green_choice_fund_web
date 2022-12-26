import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useEulaList() {
  // Use toast
  const toast = useToast()

  const refEulaListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', sortable: true },
    { key: 'file_url', sortable: false, label: 'File' },
    { key: 'status', sortable: false },
    // { key: 'actions' },
  ]
  const totalEulas = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refEulaListTable.value ? refEulaListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalEulas.value,
    }
  })

  const refetchData = () => {
    refEulaListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, statusFilter], () => {
    refetchData()
  })

  const fetchEulas = (ctx, callback) => {
    store
      .dispatch('app-eula/fetchEulas', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        status: statusFilter.value,
      })
      .then(response => {
        const inquiries = response.data.data.list;
        const total = response.data.data?.pagination?.total || 0;
        callback(inquiries)
        totalEulas.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching eulas list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolveEulaStatusVariant = status => {
    if (status === 'active') return 'success'
    if (status === 'inactive') return 'danger'
    return 'primary'
  }

  return {
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
    resolveEulaStatusVariant,
    refetchData,

    // Extra Filters
    statusFilter,
  }
}
