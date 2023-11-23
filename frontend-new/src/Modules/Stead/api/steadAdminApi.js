import request from 'src/utils/request'

export function fetchSteadList(params) {
  return request({
    url: '/api/v1/admin/stead',
    method: 'get',
    params
  })
}

export function fetchSteadInfo(id) {
  return request({
    url: '/api/v1/admin/stead/' + id,
    method: 'get'
  })
}

export function updateStead(id, data) {
  return request({
    url: '/api/v1/admin/stead/' + id,
    method: 'put',
    data
  })
}

export function getOwnerForStead(id) {
  return request({
    url: '/api/v1/admin/stead/get-owner/' + id,
    method: 'get'
  })
}

export function fetchSteadListInXlsx(params) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v1/admin/stead/get-file/xlsx',
    method: 'get',
    responseType: 'blob',
    params
  })
}
