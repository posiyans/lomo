import request from 'src/utils/request'

export function fetchCategoryList(params) {
  return request({
    url: '/api/v2/category/get-list',
    method: 'get',
    params
  })
}

export function createCategory(data) {
  return request({
    url: '/api/v1/admin/category',
    method: 'post',
    data
  })
}

export function updateCategory(id, data) {
  return request({
    url: '/api/v2/category/update/' + id,
    method: 'post',
    data
  })
}
