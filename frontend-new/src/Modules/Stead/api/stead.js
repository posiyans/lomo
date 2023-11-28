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
