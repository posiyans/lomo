import request from 'src/utils/request'

export function fetchCameraList(query) {
  return request({
    url: '/api/v1/admin/setting/camera/get-list',
    method: 'get',
    params: query
  })
}

export function addCamera(data) {
  return request({
    url: '/api/v1/admin/setting/camera/add',
    method: 'post',
    data
  })
}

export function updateCamera(data) {
  return request({
    url: '/api/v1/admin/setting/camera/update',
    method: 'post',
    data
  })
}

export function refreshCamera(id) {
  return request({
    url: '/api/v1/admin/setting/camera/reload/' + id,
    method: 'get'
  })
}
