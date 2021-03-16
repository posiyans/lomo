import request from '@/utils/request'

export function addOwnerUser(data) {
  return request({
    url: '/api/v1/admin/owner-resource',
    method: 'post',
    data: data
  })
}

export function fetchOwnerUserList(params) {
  return request({
    url: '/api/v1/admin/owner-resource',
    method: 'get',
    params: params
  })
}

export function getOwnerUserInfo(id) {
  return request({
    url: '/api/v1/admin/owner-resource/' + id,
    method: 'get'
  })
}

// получить список возможных полей для собственника
export function getOwnerFieldList() {
  return request({
    url: '/api/v1/admin/owner/fields-list',
    method: 'get'
  })
}

export function updateOwnerUserInfo(id, data) {
  return request({
    url: '/api/v1/admin/owner-resource/' + id,
    method: 'put',
    data: data
  })
}

// добавить участок собственнику
export function OwnerAddUser(data) {
  return request({
    url: '/api/v1/admin/owner/owner-add-stead',
    method: 'post',
    data: data
  })
}

