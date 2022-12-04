import request from 'src/utils/request'

export function sendMessage (data) {
  return request({
    url: '/api/v2/comment/send-message',
    method: 'post',
    data
  })
}

export function getMessage (params) {
  return request({
    url: '/api/v2/comment/get-message',
    method: 'get',
    params
  })
}

export function deleteMessage (uid) {
  return request({
    url: '/api/v2/comment/delete-message/' + uid,
    method: 'delete'
  })
}
