export const adminUserRole = {
  path: 'user-role/list',
  name: 'adminUserRoleList',
  component: () => import('src/Modules/UserRole/pages/RoleList/index.vue'),
  meta: {
    icon: 'lock',
    title: 'Права доступа',
    roles: ['role-show', 'role-edit']
  }
}
