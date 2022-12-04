export const article =
{
  path: '/article',
  component: () => import('layouts/UserLayout/index.vue'),
  children: [
    {
      path: 'show/:uid',
      component: () => import('src/Modules/Article/Article/page/Show/index.vue'),
      hidden: true,
      meta: {}
    },
    {
      path: 'list/:uid',
      component: () => import('src/Modules/Article/Article/page/List/index.vue'),
      hidden: true,
      meta: {}
    },
    {
      path: 'add-news',
      component: () => import('src/Modules/Article/Article/page/UserOfferNews/index.vue'),
      hidden: true,
      meta: { title: 'Предложить новость' }
    }
  ]
}
export const asyncArticle =
{
  path: 'article',
  meta: {
    title: 'Статьи'
  },
  redirect: 'list',
  children: [
    {
      path: 'edit/:uid?',
      component: () => import('src/Modules/Article/Article/page/EditArticle/index.vue'),
      meta: {
        title: 'Добавить статью'
      }
    },
    {
      path: 'list',
      component: () => import('src/Modules/Article/Article/page/List/index.vue'),
      meta: {
        title: 'Статьи'
      }
    }
  ]
}
