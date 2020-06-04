import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/api/v1/admin/user',
    method: 'get',
    params: query
  })
}

export function updateUserData(data, id) {
  return request({
    url: '/api/v1/admin/user/' + id,
    method: 'put',
    data: data
  })
}