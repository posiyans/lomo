import request from '@/utils/request'

export function fetchSteadList(query) {
  return request({
    url: '/api/v1/admin/stead',
    method: 'get',
    params: query
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
    data: data
  })
}

export function getOwnerForStead(id) {
  return request({
    url: '/api/v1/admin/stead/get-owner/' + id,
    method: 'get'
  })
}

export function fetchSteadListInXlsx(params) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/stead/get-file/xlsx',
    method: 'get',
    responseType: 'blob',
    params: params
  })
}
