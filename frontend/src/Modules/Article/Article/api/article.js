import request from '@/utils/request'

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
