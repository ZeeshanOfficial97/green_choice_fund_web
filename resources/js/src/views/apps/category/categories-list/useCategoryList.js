import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useCategoryList() {
  // Use toast
  const toast = useToast()

  const refCategoryListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', sortable: true },
    { key: 'solutions', sortable: false },
    { key: 'published', sortable: false },
    { key: 'status', sortable: false },
    { key: 'actions' },
  ]
  const totalCategories = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refCategoryListTable.value ? refCategoryListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalCategories.value,
    }
  })

  const refetchData = () => {
    refCategoryListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, statusFilter], () => {
    refetchData()
  })

  const fetchCategories = (ctx, callback) => {
    store
      .dispatch('app-category/fetchCategories', {
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
        totalCategories.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching categories list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolvePublishStatusVariant = published => {
    if (published === 'published') return 'success'
    if (published === 'unpublished') return 'danger'
    return 'primary'
  }

  const resolveCategoryStatusVariant = status => {
    if (status === 'active') return 'success'
    if (status === 'inactive') return 'danger'
    return 'primary'
  }

  return {
    fetchCategories,
    tableColumns,
    perPage,
    currentPage,
    totalCategories,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refCategoryListTable,
    resolvePublishStatusVariant,
    resolveCategoryStatusVariant,
    refetchData,

    // Extra Filters
    statusFilter,
  }
}
