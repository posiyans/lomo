import request from 'src/utils/request'

export function fetchAdminArticleList(query) {
  return request({
    url: '/api/v2/article/admin/get-list',
    method: 'get',
    params: query
  })
}

export function fetchArticle(id) {
  return request({
    url: '/api/v2/article/admin/get/' + id,
    method: 'get'
  })
}

export function createArticle(data) {
  return request({
    url: '/api/v2/article/admin/create',
    method: 'post',
    data
  })
}

export function updateArticle(id, data) {
  return request({
    url: '/api/v2/article/admin/update/' + id,
    method: 'put',
    data
  })
}
