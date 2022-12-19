import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useSolutionList() {
  // Use toast
  const toast = useToast()

  const refSolutionListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', sortable: true },
    { key: 'category_name',label: 'Category', sortable: false },
    { key: 'description', sortable: false },
    { key: 'published', sortable: false },
    { key: 'status', sortable: false },
    { key: 'actions' },
  ]
  const totalSolutions = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const categoryFilter = ref(null)
  const publishFilter = ref(null)
  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refSolutionListTable.value ? refSolutionListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalSolutions.value,
    }
  })

  const refetchData = () => {
    refSolutionListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, categoryFilter, publishFilter, statusFilter], () => {
    refetchData()
  })

  const fetchSolutions = (ctx, callback) => {
    store
      .dispatch('app-solution/fetchSolutions', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        category: categoryFilter.value,
        publish:publishFilter.value,
        status: statusFilter.value,
      })
      .then(response => {
        const solutions = response.data.data.list;
        const total = response.data.data?.pagination?.total || 0;
        callback(solutions)
        totalSolutions.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching solutions list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolvePublishStatusVariant = status => {
    if (status === 'published') return 'success'
    if (status === 'unpublished') return 'danger'
    return 'primary'
  }

  const resolveSolutionStatusVariant = status => {
    if (status === 'active') return 'success'
    if (status === 'inactive') return 'danger'
    return 'primary'
  }

  return {
    fetchSolutions,
    tableColumns,
    perPage,
    currentPage,
    totalSolutions,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refSolutionListTable,

    resolvePublishStatusVariant,
    resolveSolutionStatusVariant,
    refetchData,

    // Extra Filters
    categoryFilter,
    publishFilter,
    statusFilter,
  }
}
