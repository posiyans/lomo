import request from 'src/utils/request'

export function addDeviceForStead(data) {
  return request({
    url: '/api/v1/admin/metering-devices/register',
    method: 'post',
    data
  })
}

export function fetchDevicesForStead(params) {
  return request({
    url: '/api/v1/admin/metering-devices/register',
    method: 'get',
    params
  })
}

export function updateDevices(id, data) {
  return request({
    url: '/api/v1/admin/metering-devices/register/' + id,
    method: 'put',
    data
  })
}
