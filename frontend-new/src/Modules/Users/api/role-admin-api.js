import request from 'src/utils/request'

export function fetchList() {
  return request({
    url: '/api/v2/role/get-list',
    method: 'get'
  })
}

export function fetchRoleForUser(params) {
  return request({
    url: '/api/v2/role/get-for-user',
    method: 'get',
    params
  })
}

export function changeRoleForUser(data) {
  return request({
    url: '/api/v2/role/change-for-user',
    method: 'post',
    data
  })
}

export function changePermissionForUser(data) {
  return request({
    url: '/api/v2/role/change-permission-for-user',
    method: 'post',
    data
  })
}
