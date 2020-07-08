import request from '@/utils/request'

export function userRegister(data) {
  return request({
    url: '/api/v1/register',
    method: 'post',
    data
  })
}

export function login(data) {
  return request({
    url: '/api/v1/login',
    method: 'post',
    data
  })
}

export function loginVK(data) {
  return request({
    url: '/api/v1/login-vk',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/api/v1/user',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/api/v1/user-logout',
    method: 'post'
  })
}

export function setUserInfo(data) {
  return request({
    url: '/api/v1/user/save-user-info',
    method: 'post',
    data: data
  })
}

export function passwordReset(data) {
  return request({
    url: '/api/v1/password/email',
    method: 'post',
    data: data
  })
}
passwordChange

export function passwordChange(data) {
  return request({
    url: '/api/v1/password/reset',
    method: 'post',
    data: data
  })
}
