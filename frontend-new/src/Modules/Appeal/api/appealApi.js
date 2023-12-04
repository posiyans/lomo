import request from 'src/utils/request'

export function fetchAppealList(query) {
  return request({
    url: '/api/v2/appeal/get-list',
    method: 'get',
    params: query
  })
}

export function createAppeal(data) {
  return request({
    url: '/api/v2/appeal/create',
    method: 'post',
    data
  })
}

export function getAppeal(id) {
  return request({
    url: '/api/v2/appeal/get/' + id,
    method: 'get'
  })
}
