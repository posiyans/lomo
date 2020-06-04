import request from '@/utils/request'

export function fetchAppelList(query) {
  return request({
    url: '/api/v1/admin/appeal',
    method: 'get',
    params: query
  })
}

export function updateAppel(data, id) {
  return request({
    url: '/api/v1/admin/appeal/' + id,
    method: 'put',
    data
  })
}

export function fetchArticle(id) {
  return request({
    url: '/api/v1/article/detail',
    method: 'get',
    params: { id }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/api/v1/article/pv',
    method: 'get',
    params: { pv }
  })
}

export function createArticle(data) {
  return request({
    url: '/api/v1/article/create',
    method: 'post',
    data
  })
}

export function updateArticle(data) {
  return request({
    url: '/api/v1/article/update',
    method: 'post',
    data
  })
}