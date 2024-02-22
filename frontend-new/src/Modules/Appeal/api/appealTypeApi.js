import request from 'src/utils/request'

export function fetchAppealType(query) {
  return request({
    url: '/api/v2/appeal/type/get-list',
    method: 'get',
    params: query
  })
}

export function createAppealType(data) {
  return request({
    url: '/api/v2/appeal/type/',
    method: 'post',
    data
  })
}

export function updateAppealType(id, data) {
  return request({
    url: '/api/v2/appeal/type/' + id,
    method: 'put',
    data
  })
}

export function deleteAppealType(id) {
  return request({
    url: '/api/v2/appeal/type/' + id,
    method: 'delete'
  })
}
