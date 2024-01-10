export const adminBookkeeping =
  {
    path: 'bookkeeping',
    meta: {
      icon: 'receipt',
      title: 'Бухгалтерия',
      roles: ['bookkeeping-show', 'profit-show', 'payment-show']
    },
    redirect: { name: 'adminUserBalance' },
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
        name: 'adminUserBalance',
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
        component: () => import('src/Modules/Bookkeeping/pages/Payment/AdminPaymentList/index.vue'),
        meta: {
          icon: 'receipt',
          title: 'Платежи',
          roles: ['payment-show', 'payment-edit']
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
        path: 'invoice',
        component: () => import('src/Modules/Bookkeeping/pages/Invoice/AdminInvoiceList/index.vue'),
        meta: {
          icon: 'receipt',
          title: 'Счета',
          roles: ['invoice-show', 'invoice-edit']
        }
      },
      {
        path: 'rate',
        component: () => import('src/Modules/Rate/pages/SettingsRates/index.vue'),
        meta: {
          icon: 'star_rate',
          title: 'Тарифы',
          roles: ['rate-edit', 'rate-show']
        }
      }
    ]
  }
