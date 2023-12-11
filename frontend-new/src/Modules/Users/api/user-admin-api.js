import request from 'src/utils/request'

export function fetchList(params) {
  return request({
    url: '/api/v2/user/list',
    method: 'get',
    params
  })
}

export function updateUserFieldData(data) {
  return request({
    url: '/api/v2/user/update',
    method: 'post',
    data
  })
}

export function fetchUserInfo(params) {
  return request({
    url: '/api/v2/user/info',
    method: 'get',
    params
  })
}

export function sendVerifyMailToken(id) {
  return request({
    url: '/api/v1/admin/user/profile/send-verify-mail-token/' + id,
    method: 'get'
  })
}
