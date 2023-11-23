import request from 'src/utils/request'

export function getSocialMedia(params) {
  return request({
    url: '/api/v2/social/get-list',
    method: 'get',
    params
  })
}
