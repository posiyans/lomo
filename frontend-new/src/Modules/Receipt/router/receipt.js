const receipt =
  {
    path: '/modules',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'receipt',
        component: () => import('src/Modules/Receipt/pages/ReceiptForm/index.vue'),
        name: 'modulesReceipt',
        hidden: true
      }
    ]
  }
export default receipt
