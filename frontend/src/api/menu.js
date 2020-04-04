import request from '@/utils/request'

export function getMenu(data) {
  return request({
    url: '/api/v1/user/category',
    method: 'get',
    params: data
  })
}
