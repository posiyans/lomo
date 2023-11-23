import request from '@/utils/request'

export function getAvatarUrl(uid) {
  return request({
    url: '/api/v2/avatar/get-user/' + uid,
    method: 'get'
  })
}
