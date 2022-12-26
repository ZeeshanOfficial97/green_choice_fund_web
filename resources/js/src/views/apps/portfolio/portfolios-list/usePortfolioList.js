import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function usePortfolioList() {
  // Use toast
  const toast = useToast()

  const refPortfoliosListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'user', sortable: false },
    { key: 'email', sortable: false },
    { key: 'phone', sortable: false },
    { key: 'category', sortable: false },
    { key: 'solution', sortable: false },
  ]
  const totalPortfolios = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const userFilter = ref(null)
  const categoryFilter = ref(null)
  const solutionFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refPortfoliosListTable.value ? refPortfoliosListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalPortfolios.value,
    }
  })

  const refetchData = () => {
    refPortfoliosListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, userFilter, categoryFilter, solutionFilter], () => {
    refetchData()
  })

  const fetchPortfolios = (ctx, callback) => {

    store
      .dispatch('app-portfolio/fetchPortfolios', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        user_id: userFilter.value,
        category_id: categoryFilter.value,
        solution_id: solutionFilter.value,
      })
      .then(response => {
        const portfolios = response.data.data.list;
        const total = response.data.data?.pagination?.total || 0;
        callback(portfolios)
        totalPortfolios.value = total
      })
      .catch((err,) => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching Portfolios list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*


  return {
    fetchPortfolios,
    tableColumns,
    totalPortfolios,
    currentPage,
    perPage,
    perPageOptions,
    dataMeta,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refPortfoliosListTable,
    refetchData,

    // Extra Filters
    userFilter,
    categoryFilter,
    solutionFilter
  }
}
