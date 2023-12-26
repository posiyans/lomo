import { adminArticle } from 'src/Modules/Article/router/article.js'
import { adminStead } from 'src/Modules/Stead/router/stead.js'
import { adminOwner } from 'src/Modules/Owner/router/ownerRouter.js'
import { adminAppeal } from 'src/Modules/Appeal/router/appealRouter.js'
import { adminVoting } from 'src/Modules/Voting/router/votingRouter.js'
import { adminUser } from 'src/Modules/Users/router/userRouter.js'
// import { adminUserRole } from 'src/Modules/UserRole/router/userRoleRouter.js'
import { adminSettingsRouter } from 'src/router/SettingsRouter.js'
import { adminBookkeeping } from 'src/Modules/Bookkeeping/router/bookkeepingRouter'
import { adminUserBan } from 'src/Modules/BanUsers/router/userBanRouter'
import { adminComment } from 'src/Modules/Comments/router/commentRouter'

const adminRoutes =
  [
    {
      path: '',
      component: () => import('src/pages/Admin/Dashboard/index.vue'),
      name: 'AdminDashboard',
      meta: {
        title: 'Главная',
        icon: 'dashboard',
        roles: ['access-admin-panel']
      }
    },
    adminArticle,
    adminComment,
    adminUserBan,
    adminStead,
    adminOwner,
    adminAppeal,
    adminVoting,
    adminUser,
    // adminUserRole,
    adminBookkeeping,
    adminSettingsRouter
  ]

export default adminRoutes
