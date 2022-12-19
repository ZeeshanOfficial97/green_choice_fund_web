export default [
  {
    header: 'Apps',
  },
  {
    title: 'App User',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: 'apps-app-users-list',
      }
    ],
  },
  {
    title: 'Category',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: 'apps-categories-list',
      }
    ],
  },
  {
    title: 'Solution',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: 'apps-solutions-list',
      },
    ],
  },
  {
    title: 'Inquiry',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: { name: 'apps-inquiries-list' },
      },
    ],
  },
  {
    title: 'Investment',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: { name: 'apps-investments-list' },
      },
    ],
  },
  {
    title: 'Notification',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'Send',
        route: { name: 'apps-notifications-send' },
      },
    ],
  }
]
