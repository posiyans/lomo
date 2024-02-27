import request from 'src/utils/request'

export function changeGoogle2FaSecretKey(data) {
  return request({
    url: '/api/v2/auth/2f/change-secret-key',
    method: 'post',
    data
  })
}
