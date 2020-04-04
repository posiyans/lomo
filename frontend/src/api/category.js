import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/api/v1/admin/category',
    method: 'get',
    params: query
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
    url: '/api/v1/admin/category/' + id,
    method: 'put',
    data
  })
}


//
// export function fetchArticle(id) {
//   return request({
//     url: '/vue-element-admin/article/detail',
//     method: 'get',
//     params: { id }
//   })
// }
//
// export function fetchPv(pv) {
//   return request({
//     url: '/vue-element-admin/article/pv',
//     method: 'get',
//     params: { pv }
//   })
// }
//
// export function createArticle(data) {
//   return request({
//     url: '/vue-element-admin/article/create',
//     method: 'post',
//     data
//   })
// }
//
// export function updateArticle(data) {
//   return request({
//     url: '/vue-element-admin/article/update',
//     method: 'post',
//     data
//   })
// }
