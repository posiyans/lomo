import request from 'src/utils/request'

export function getUserAvatar(params) {
  return request({
    url: '/api/v2/avatar/user/get',
    method: 'get',
    params
  })
}
