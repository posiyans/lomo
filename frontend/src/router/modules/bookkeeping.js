/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const bookkepingRouter = {
  path: '/bookkeping',
  component: Layout,
  redirect: '/bookkeping/billing_reestr',
  meta: {
    title: 'Бухгалтерия',
    icon: 'documentation',
    roles: ['edit-rate']
  },
  children: [
    {
      path: 'billing_reestr_create',
      component: () => import('@/views/admin/bookkeeping/BillingAddInvoiceForAllStead/add'),
      name: 'BillingReestrCreate',
      hidden: true

    },
    // {
    //   path: 'billing_reestr_edit/:id(\\d+)',
    //   component: () => import('@/views/admin/bookkeeping/BillingAddInvoiceForAllStead/edit'),
    //   name: 'BillingReestrEdit',
    //   hidden: true
    //
    // },
    {
      path: 'billing_reestr',
      component: () => import('@/views/admin/bookkeeping/BillingReestr/BillingReestrList/index'),
      name: 'BillingReestrList',
      meta: {
        title: 'Начисления',
        icon: 'documentation',
        affix: true
      }
    },

    {
      path: 'billing_balance',
      component: () => import('@/views/admin/bookkeeping/BillingBalans/BillingBalansList/index'),
      name: 'BillingBalansLists',
      meta: {
        title: 'Баланс',
        icon: 'documentation',
        affix: true
      }
    },
    {
      path: 'billing_balance_stead/:id(\\d+)',
      component: () => import('@/views/admin/bookkeeping/BillingBalans/BillingBalansStead/index'),
      name: 'BillingBalansStead',
      hidden: true
    },
    {
      path: 'payment_list',
      component: () => import('@/views/admin/bookkeeping/BillingPayment/PaymentList/index'),
      meta: {
        title: 'Платежи',
        icon: 'documentation',
        affix: true
      }
    },
    {
      path: 'contributions',
      component: () => import('@/views/admin/bookkeeping/Receipts/index'),
      name: 'AdminReceipts',
      meta: {
        title: 'Квитанции',
        icon: 'documentation',
        affix: true
      }
    },
    {
      path: 'payment_info/:id(\\d+)',
      component: () => import('@/views/admin/bookkeeping/BillingPayment/PaymentInfo/index'),
      hidden: true
    },
    {
      path: 'invoice_info/:id(\\d+)',
      component: () => import('@/views/admin/bookkeeping/BillingInvoice/InvoiceInfo/index'),
      hidden: true
    },
    // {
    //   path: 'billing_bank_reestr_upload',
    //   component: () => import('@/views/admin/bookkeeping/BillingBank/BillingBankReestr/create'),
    //   meta: {
    //     title: 'Выписки',
    //     icon: 'documentation',
    //     affix: true
    //   }
    // },
    // {
    //   path: 'billing_bank_reestr_upload_new',
    //   component: () => import('@/views/admin/bookkeeping/BillingBank/BillingBankReestr/parse/index'),
    //   meta: {
    //     title: 'Выписки2',
    //     icon: 'documentation',
    //     affix: true
    //   }
    // },
    {
      path: 'billing_bank_reestr/:id(\\d+)',
      component: () => import('@/views/admin/bookkeeping/BillingBank/BillingBankReestr/edit'),
      hidden: true
    }
  ]

}

export default bookkepingRouter
