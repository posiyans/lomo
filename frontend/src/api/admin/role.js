import request from '@/utils/request'

export function fetchList() {
  return request({
    url: '/api/v1/admin/role-list',
    method: 'get',
  })
}
