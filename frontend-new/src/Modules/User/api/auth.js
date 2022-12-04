import request from 'src/utils/request'

export function getInfo () {
  return request({
    url: '/api/v2/user/info',
    method: 'get'
  })
}

export function userLoginByEmail (data) {
  return request({
    url: '/api/v2/login',
    method: 'post',
    data
  })
}

export function userLogout () {
  return request({
    url: '/api/v2/logout',
    method: 'get'
  })
}

export function getCsrfCookieToken () {
  return request({
    // url: '/api/v2/csrf-cookie',
    url: 'api/v1/sanctum/csrf-cookie',
    method: 'get'
  })
}

export function userRegister (data) {
  return request({
    url: '/api/v2/register',
    method: 'post',
    data
  })
}

export function passwordReset (data) {
  return request({
    url: '/api/v2/password-send-reset-link',
    method: 'post',
    data
  })
}

export function getAuthPathVK (data) {
  return request({
    url: '/api/v2/vk/get-auth-path',
    method: 'get'
  })
}
