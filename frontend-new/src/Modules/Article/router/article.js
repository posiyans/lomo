export const article =
  {
    path: '/article',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'show/:uid',
        component: () => import('src/Modules/Article/Article/page/Show/index.vue'),
        hidden: true,
        meta: {
          title: 'Показать статью',
          noTitle: true
        }
      },
      {
        path: 'list/:id',
        component: () => import('src/Modules/Article/Article/page/List/index.vue'),
        hidden: true,
        meta: {
          title: 'Статьи раздела',
          noTitle: true
        }
      },
      {
        path: 'add-news',
        component: () => import('src/Modules/Article/Article/page/UserOfferNews/index.vue'),
        hidden: true,
        meta: { title: 'Предложить новость' }
      }
    ]
  }
export const adminArticle =
  {
    path: 'article',
    meta: {
      icon: 'article',
      title: 'Статьи',
      roles: ['article-show', 'article-edit']
    },
    redirect: { name: 'adminArticleList' },
    children: [
      {
        path: 'edit/',
        component: () => import('src/Modules/Article/Article/page/EditArticle/index.vue'),
        meta: {
          icon: 'add',
          title: 'Добавить статью',
          roles: ['article-edit']
        }
      },
      {
        path: 'edit/:id',
        component: () => import('src/Modules/Article/Article/page/EditArticle/index.vue'),
        hidden: true,
        meta: {
          title: 'Редактировать',
          roles: ['article-edit', 'article-show']
        }
      },
      {
        path: 'list',
        name: 'adminArticleList',
        component: () => import('src/Modules/Article/Article/page/AdminArticleList/index.vue'),
        meta: {
          icon: 'list_alt',
          title: 'Статьи',
          roles: ['article-show']
        }
      },
      {
        path: 'category/list',
        name: 'adminArticleCategoryList',
        component: () => import('src/Modules/Article/Category/pages/AdminCategoryList/index.vue'),
        meta: {
          icon: 'list_alt',
          title: 'Разделы',
          roles: ['article-show']
        }
      }
    ]
  }
