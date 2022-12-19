export default [

  // *===============================================---*
  // *--------- USER ---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/apps/app-users/list',
    name: 'apps-app-users-list',
    component: () => import('@/views/apps/app-user/app-users-list/AppUserList.vue'),
  },
  {
    path: '/app-users/view/:id',
    name: 'apps-app-users-view',
    component: () => import('@/views/apps/app-user/app-users-view/AppUserView.vue'),
  },

  // *===============================================---*
  // *--------- Category ---- ---------------------------------------*
  // *===============================================---*

  {
    path: '/apps/categories/list',
    name: 'apps-categories-list',
    component: () => import('@/views/apps/category/categories-list/CategoryList.vue'),
  },
  {
    path: '/apps/categories/edit/:id',
    name: 'apps-categories-edit',
    component: () => import('@/views/apps/category/categories-edit/CategoryEdit.vue'),
  },


  // *===============================================---*
  // *--------- Solution ---- ---------------------------------------*
  // *===============================================---*

  {
    path: '/apps/solutions/list',
    name: 'apps-solutions-list',
    component: () => import('@/views/apps/solution/solutions-list/SolutionList.vue'),
  },
  {
    path: '/apps/solutions/edit/:id',
    name: 'apps-solutions-edit',
    component: () => import('@/views/apps/solution/solutions-edit/SolutionEdit.vue'),
  },

  // *===============================================---*
  // *--------- User Inquiry ---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/apps/inquiries/list',
    name: 'apps-inquiries-list',
    component: () => import('@/views/apps/inquiry/inquiries-list/InquiryList.vue'),
  },
  {
    path: '/apps/inquiries/view/:id',
    name: 'apps-inquiries-view',
    component: () => import('@/views/apps/inquiry/inquiries-view/InquiryView.vue'),
  },

  // *===============================================---*
  // *--------- Investments ---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/apps/investments/list',
    name: 'apps-investments-list',
    component: () => import('@/views/apps/investment/investments-list/InvestmentList.vue'),
  },
  {
    path: '/apps/investments/view/:id',
    name: 'apps-investments-view',
    component: () => import('@/views/apps/investment/investments-view/InvestmentView.vue'),
  },

  // *===============================================---*
  // *--------- Notifications ---- ---------------------------------------*
  // *===============================================---*
  {
    path: '/apps/notifications/send',
    name: 'apps-notifications-send',
    component: () => import('@/views/apps/notification/notifications-send/NotificationSend.vue'),
  }

]
