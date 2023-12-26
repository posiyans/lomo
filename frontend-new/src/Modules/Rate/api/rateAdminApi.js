import request from 'src/utils/request'

export function fetchList(query) {
  return request({
    url: '/api/v1/admin/setting/rate',
    method: 'get',
    params: query
  })
}

export function updateRate(id, data) {
  return request({
    url: '/api/v1/admin/setting/rate/' + id,
    method: 'put',
    data
  })
}

export function createRate(data) {
  return request({
    url: '/api/v1/admin/setting/rate/',
    method: 'post',
    data
  })
}

export function createRateGroup(data) {
  return request({
    url: '/api/v2/rate/group/create',
    method: 'post',
    data
  })
}

export function getRateGroupList() {
  return request({
    url: '/api/v2/rate/group/get-list',
    method: 'get'
  })
}

export function getRateListForGroup(id) {
  return request({
    url: '/api/v2/rate/get/' + id,
    method: 'get'
  })
}
