import request from '@/utils/request'

export function fetchListComments(query) {
  return request({
    url: '/api/v1/user/comment',
    method: 'get',
    params: query
  })
}



export function addComment(data) {
  return request({
    url: '/api/v1/user/comment',
    method: 'post',
    data
  })
}