import request from 'src/utils/request'

export function sendMessage(data) {
  return request({
    url: '/api/v2/comment/send-message',
    method: 'post',
    data
  })
}

export function getMessage(params) {
  return request({
    url: '/api/v2/comment/get-message',
    method: 'get',
    params
  })
}

export function deleteMessage(params) {
  return request({
    url: '/api/v2/comment/delete',
    method: 'delete',
    params
  })
}

export function getCommentsStatusList() {
  return [
    {
      value: 0,
      label: 'отключены',
      color: 'negative'
    },
    {
      value: 1,
      label: 'разрешены всем',
      color: 'secondary'
    },
    {
      value: 2,
      label: 'только собственникам',
      color: 'secondary'
    }
  ]
}

export function getAllMessage(params) {
  return request({
    url: '/api/v2/comment/get-all',
    method: 'get',
    params
  })
}
