import request from '@/utils/request'

export function addDeviceForStead(data) {
  return request({
    url: '/api/v1/admin/metering-devices/register',
    method: 'post',
    data: data
  })
}

export function fetchDevicesForStead(params) {
  return request({
    url: '/api/v1/admin/metering-devices/register',
    method: 'get',
    params: params
  })
}

export function updateDevices(id, data) {
  return request({
    url: '/api/v1/admin/metering-devices/register/' + id,
    method: 'put',
    data: data
  })
}

// export function fetchPaymentInfo(id) {
//   return request({
//     url: '/api/v1/admin/billing/payment/' + id,
//     method: 'get'
//   })
// }
//
// export function updatePaymentInfo(id, data) {
//   return request({
//     url: '/api/v1/admin/billing/payment/' + id,
//     method: 'put',
//     data
//   })
// }

