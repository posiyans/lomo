import request from '@/utils/request'

export function getGardeninngInfo() {
  return request({
    url: '/api/v1/user/gardening',
    method: 'get'
  })
}
