import request from '@/utils/request'

export function getRoutes() {
  return request({
    url: '/api/v1/routes',
    method: 'get'
  })
}

export function getRoles() {
  return request({
    url: '/api/v1/admin/role',
    method: 'get'
  })
}

export function addRole(data) {
  return request({
    url: '/api/v1/role',
    method: 'post',
    data
  })
}

export function updateRole(id, data) {
  return request({
    url: `/api/v1/admin/role/${id}`,
    method: 'put',
    data
  })
}

export function deleteRole(id) {
  return request({
    url: `/api/v1/role/${id}`,
    method: 'delete'
  })
}
