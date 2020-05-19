import request from '@/utils/request'

export function updateGardeninngInfo(data) {
  return request({
    url: '/api/v1/admin/gardening',
    method: 'post',
    data: data
  })
}
