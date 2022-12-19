import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import { title } from '@core/utils/filter'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useUsersList() {
  // Use toast
  const toast = useToast()

  const refUserListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'name', sortable: true },
    { key: 'email', sortable: true },
    { key: 'stripe_user_id', sortable: false, formatter: title },
    { key: 'user_type', sortable: true, formatter: title },
    { key: 'status', sortable: false },
    { key: 'actions' },
  ]
  const totalUsers = ref(0)
  const currentPage = ref(1)
  const perPage = ref(10)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const userTypeFilter = ref(null)
  const statusFilter = ref(null)

  const dataMeta = computed(() => {
    const localItemsCount = refUserListTable.value ? refUserListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalUsers.value,
    }
  })

  const refetchData = () => {
    
    refUserListTable.value.refresh()
  }

  watch([currentPage, perPage, searchQuery, userTypeFilter, statusFilter], () => {
    refetchData()
  })

  const fetchUsers = (ctx, callback) => {
    
    store
      .dispatch('app-user/fetchUsers', {
        q: searchQuery.value,
        length: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        dir: isSortDirDesc.value ? 'desc' : 'asc',
        userTypeId: userTypeFilter.value,
        status: statusFilter.value,
      })
      .then(response => {
        
        // const { users, total } = response.data
        const users = response.data.data.list;
        const total = response.data.data?.pagination?.total || 0;
        callback(users)
        totalUsers.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching users list',
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

    userTypeFilter,
    statusFilter,
  }
}
