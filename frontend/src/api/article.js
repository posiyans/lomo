import request from '@/utils/request'

export function fetchAdminArticleList(query) {
  return request({
    url: '/api/v1/admin/article',
    method: 'get',
    params: query
  })
}

export function fetchListForCategory(query) {
  return request({
    url: '/api/v1/user/article',
    method: 'get',
    params: query
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

export function fetchArticle(id) {
  return request({
    url: '/api/v1/admin/article/' + id,
    method: 'get'
  })
}

export function fetchUserArticle(id) {
  return request({
    url: '/api/v1/user/article/' + id,
    method: 'get'
  })
}
