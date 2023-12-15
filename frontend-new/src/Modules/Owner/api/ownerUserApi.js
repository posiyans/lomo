import request from 'src/utils/request'

// получить список возможных полей для собственника
export function getOwnerFieldList() {
  return request({
    url: '/api/v2/owner/get-field-list',
    method: 'get'
  })
}

export function addOwnerUser(data) {
  return request({
    url: '/api/v2/owner/create',
    method: 'post',
    data
  })
}

export function fetchOwnerUserList(params) {
  return request({
    url: '/api/v2/owner/get-list',
    method: 'get',
    params
  })
}

export function getOwnerUserInfo(uid) {
  return request({
    url: '/api/v2/owner/get/' + uid,
    method: 'get'
  })
}

export function updateOwnerUserFiled(uid, data) {
  return request({
    url: '/api/v2/owner/update/' + uid,
    method: 'post',
    data
  })
}

// добавить участок собственнику
export function addSteadToOwnerUser(data) {
  return request({
    url: '/api/v2/owner/add-stead',
    method: 'post',
    data
  })
}

export function deleteSteadFromOwnerUser(id) {
  return request({
    url: '/api/v1/admin/owner/delete-stead/' + id,
    method: 'delete'
  })
}

export function fetchOwnerListInXlsx(params) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v2/owner/get-list-xlsx',
    method: 'get',
    responseType: 'blob',
    params
  })
}

export function deleteOwnerUser(uid) {
  return request({
    url: '/api/v2/owner/delete/' + uid,
    method: 'delete'
  })
}
