import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useFaqList() {
  // Use toast
  const toast = useToast()

  const refFaqListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'question', sortable: false },
    { key: 'answer', sortable: false},
    { key: 'status', sortable: false },
    { key: 'actions' },
  ]
  const totalFaqs = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refFaqListTable.value ? refFaqListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalFaqs.value,
    }
  })

  const refetchData = () => {
    refFaqListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, statusFilter], () => {
    refetchData()
  })

  const fetchFaqs = (ctx, callback) => {
    store
      .dispatch('app-faq/fetchFaqs', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        status: statusFilter.value,
      })
      .then(response => {
        const faqs = response.data.data.list;
        const total = response.data.data?.pagination?.total || 0;
        callback(faqs)
        totalFaqs.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching faqs list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolveFaqStatusVariant = status => {
    if (status === 'active') return 'success'
    if (status === 'inactive') return 'danger'
    return 'primary'
  }

  return {
    fetchFaqs,
    tableColumns,
    perPage,
    currentPage,
    totalFaqs,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refFaqListTable,

    resolveFaqStatusVariant,
    refetchData,

    // Extra Filters
    statusFilter,
  }
}