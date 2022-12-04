import request from 'src/utils/request'

export function fetchCategoryList (params) {
  return request({
    url: '/api/v1/category/get-list',
    method: 'get',
    params
  })
}
