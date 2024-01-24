import request from 'src/utils/request'

export function getMeteringDeviceForStead(steadId) {
  return request({
    url: '/api/v2/metering-device/get-for-stead/' + steadId,
    method: 'get'
  })
}

export function updateFieldMeteringDevice(deviceId, data) {
  return request({
    url: '/api/v2/metering-device/' + deviceId,
    method: 'post',
    data
  })
}
