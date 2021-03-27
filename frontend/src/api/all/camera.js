import request from '@/utils/request'

export function getCameraList() {
  return request({
    url: '/api/v1/camera/all-list',
    method: 'get'
  })
}
