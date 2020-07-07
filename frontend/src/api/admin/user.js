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

export function fetchUserInfo(id) {
  return request({
    url: '/api/v1/admin/user/' + id,
    method: 'get'
  })
}

export function sendVerifyMailToken(id) {
  return request({
    url: '/api/v1/admin/user/profile/send-verify-mail-token/' + id,
    method: 'get'
  })
}
