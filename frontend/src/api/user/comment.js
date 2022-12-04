import request from '@/utils/request'

/**
 * @deprecated
 * @param query
 * @returns {*}
 */
export function fetchListComments(query) {
  return request({
    url: '/api/v1/user/comment',
    method: 'get',
    params: query
  })
}
/**
 * @deprecated
 * @param query
 * @returns {*}
 */
export function addComment(data) {
  return request({
    url: '/api/v1/user/comment',
    method: 'post',
    data
  })
}
/**
 * @deprecated
 * @param query
 * @returns {*}
 */
export function deleteComment(id) {
  return request({
    url: '/api/v1/admin/comment/' + id,
    method: 'delete'
  })
}
