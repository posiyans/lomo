import request from 'src/utils/request'

export function fetchAppealType(query) {
  return request({
    url: '/api/v2/appeal/type/get-list',
    method: 'get',
    params: query
  })
}
