import request from '@/utils/request'

export function getSteadsList(data) {
  return request({
    url: '/api/v1/user/steads-list',
    method: 'post',
    data: data
  })
}
