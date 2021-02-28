import request from '@/utils/request'

export function getDeviceForStead(data) {
  return request({
    url: '/api/v1/instrument-reading/get-device-for-stead',
    method: 'post',
    data: data
  })
}

export function sendDataMeteringDeviceForStead(data) {
  return request({
    url: '/api/v1/instrument-reading/send-data-for-stead',
    method: 'post',
    data: data
  })
}
