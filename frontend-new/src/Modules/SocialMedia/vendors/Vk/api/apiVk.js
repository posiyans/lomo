import request from 'src/utils/request'

export function getAuthPathVK(data) {
  return request({
    url: '/api/v2/social/vk/auth/get-path',
    method: 'post',
    data
  })
}
