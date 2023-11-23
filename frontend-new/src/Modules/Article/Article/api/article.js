import request from 'src/utils/request'

export function userAddArticle(data) {
  return request({
    url: '/api/v2/article/user-add',
    method: 'post',
    data
  })
}

export function getStatusList() {
  return request({
    url: '/api/v2/status/get-list',
    method: 'get'
  })
}

export function getArticleFiles(params) {
  return request({
    url: '/api/v2/article/get-files',
    method: 'get',
    params
  })
}

export function fetchListForCategory(query) {
  return request({
    url: '/api/v2/article/get-list',
    method: 'get',
    params: query
  })
}

export function fetchUserArticle(uid) {
  return request({
    url: '/api/v2/article/get/' + uid,
    method: 'get'
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
    url: '/api/v1/admin/article',
    method: 'post',
    data
  })
}

export function updateArticle(id, data) {
  return request({
    url: '/api/v1/admin/article/' + id,
    method: 'put',
    data
  })
}
