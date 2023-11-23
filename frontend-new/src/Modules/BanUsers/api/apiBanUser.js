import request from 'src/utils/request'

export function getBanUserList(params) {
  return request({
    url: '/api/v2/ban-user/list',
    method: 'get',
    params
  })
}

export function checkMyBan(params) {
  return request({
    url: '/api/v2/ban-user/check',
    method: 'get',
    params
  })
}

export function addUserBan(data) {
  return request({
    url: '/api/v2/ban-user/add',
    method: 'post',
    data
  })
}

export function deleteUserBan(params) {
  return request({
    url: '/api/v2/ban-user/delete',
    method: 'delete',
    params
  })
}

export function updateUserBan(data) {
  return request({
    url: '/api/v2/ban-user/update',
    method: 'put',
    data
  })
}

export function getBanForUser(params) {
  return request({
    url: '/api/v2/ban-user/get-for-user',
    method: 'get',
    params
  })
}
