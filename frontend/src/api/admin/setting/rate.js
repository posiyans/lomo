import request from '@/utils/request'

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
