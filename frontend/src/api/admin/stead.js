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
    // params: query
  })
}

export function updateStead(id, data) {
  return request({
    url: '/api/v1/admin/stead/' + id,
    method: 'put',
    data: data
  })
}
