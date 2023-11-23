export function useAdminMenu() {
  const menuList = [
    {
      title: 'Статьи',
      icon: '',
      roles: ['article-show', 'article-edit'],
      children: [
        {
          title: 'Добавить статью',
          icon: '',
          roles: ['article-edit'],
          path: ''
        },
        {
          title: 'Статьи',
          icon: '',
          roles: ['article-show', 'article-edit'],
          path: ''
        }
      ]

    }
  ]
  return {
    menuList
  }
}
