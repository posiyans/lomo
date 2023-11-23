export const adminBookkeeping =
  {
    path: 'bookkeeping',
    meta: {
      icon: 'receipt',
      title: 'Бухгалтерия',
      roles: ['bookkeeping-show', 'profit-show', 'payment-show']
    },
    redirect: { name: 'adminArticleList' },
    children: [
      {
        path: 'billing_reestr',
        component: () => import('src/Modules/Bookkeeping/pages/BillingReestr/index.vue'),
        meta: {
          icon: 'receipt',
          title: 'Начисления',
          roles: ['profit-show']
        }
      },
      {
        path: 'billing_balance',
        component: () => import('src/Modules/Bookkeeping/pages/BillingBalansList/index.vue'),
        meta: {
          icon: 'receipt',
          title: 'Баланс',
          roles: ['bookkeeping-show']
        }
      },
      {
        path: 'billing_balance_stead/:id',
        component: () => import('src/Modules/Bookkeeping/pages/BillingBalansStead/index.vue'),
        hidden: true,
        meta: {
          icon: 'receipt',
          title: 'Баланс',
          roles: ['bookkeeping-show']
        }
      },
      {
        path: 'payment_list',
        component: () => import('src/Modules/Bookkeeping/pages/BillingPayment/PaymentList/index.vue'),
        meta: {
          icon: 'receipt',
          title: 'Платежи',
          roles: ['payment-show']
        }
      },
      {
        path: 'payment_add',
        component: () => import('src/Modules/Bookkeeping/pages/BillingPayment/PaymentAdd/index.vue'),
        hidden: true,
        meta: {
          icon: 'receipt',
          title: 'Добавить выписку',
          roles: ['payment-edit']
        }
      },
      {
        path: 'contributions',
        component: () => import('src/Modules/Receipt/pages/AdminReceiptList/index.vue'),
        meta: {
          icon: 'receipt',
          title: 'Квитанции',
          roles: ['bookkeeping-show']
        }
      }

    ]
  }
