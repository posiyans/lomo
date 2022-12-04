/** When your routing table is too long, you can split it into small modules**/

const article = {
  path: '/article/add-news',
  component: () => import('@/Modules/Article/Article/page/UserOfferNews/index.vue'),
  hidden: true,
  meta: { title: 'Предложить новость', icon: 'link', affix: true }
}

export default article
