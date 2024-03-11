import request from 'src/utils/request'

export function searchBySite(params) {
  return request({
    url: '/api/v2/search/by-site',
    method: 'get',
    params
  })
}
