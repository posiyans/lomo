import request from 'src/utils/request'

export function getCameraList(params) {
  return request({
    url: '/api/v2/camera/get-list',
    method: 'get',
    params
  })
}

export function addCamera(data) {
  return request({
    url: '/api/v2/camera/create',
    method: 'post',
    data
  })
}

export function updateCamera(id, data) {
  return request({
    url: '/api/v2/camera/' + id,
    method: 'post',
    data
  })
}

export function deleteCamera(id) {
  return request({
    url: '/api/v2/camera/' + id,
    method: 'delete'
  })
}

export function refreshCamera(id) {
  return request({
    url: '/api/v2/camera/reload/' + id,
    method: 'get'
  })
}
