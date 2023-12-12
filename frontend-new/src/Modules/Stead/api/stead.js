import request from 'src/utils/request'

export function getSteadsList(params) {
  return request({
    url: '/api/v2/stead/list',
    method: 'get',
    params
  })
}

export function addStead(data) {
  return request({
    url: '/api/v2/stead/create',
    method: 'post',
    data
  })
}

export function getSteadInfo(params) {
  return request({
    url: '/api/v2/stead/info',
    method: 'get',
    params
  })
}

export function getListOfOwnerStead(params) {
  return request({
    url: '/api/v2/stead/get-list-for-owner',
    method: 'get',
    params
  })
}

export function fetchSteadListInXlsx(params) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v2/stead/list-xlsx',
    method: 'get',
    responseType: 'blob',
    params
  })
}

export function updateSteadField(id, data) {
  return request({
    url: '/api/v2/stead/update/' + id,
    method: 'post',
    data
  })
}
