import request from '@/utils/request'

export function fetchCommunalListForStead(id, params) {
  return request({
    url: '/api/v1/admin/billing/communal/stead/get/' + id,
    method: 'get',
    params: params
  })
}

export function addInstrumentReadings(id, data) {
  return request({
    url: '/api/v1/admin/billing/communal/stead/add-reading/' + id,
    method: 'post',
    data: data
  })
}

// удалить показания
export function deleteInstrumentReadings(id) {
  return request({
    url: '/api/v1/admin/billing/communal/stead/delete-reading/' + id,
    method: 'delete'
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

