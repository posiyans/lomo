import request from 'src/utils/request'

export function getBalanceList(params) {
  request.defaults.timeout = 50000
  return request({
    url: '/api/v2/billing/balance/get-list',
    method: 'get',
    params
  })
}

//
// export function getPayment(id) {
//   return request({
//     url: '/api/v2/billing/payment/' + id,
//     method: 'get'
//   })
// }
//
// export function updatePayment(id, data) {
//   return request({
//     url: '/api/v2/billing/payment/' + id,
//     method: 'post',
//     data
//   })
// }
//
// export function deletePayment(id) {
//   return request({
//     url: '/api/v2/billing/payment/' + id,
//     method: 'delete'
//   })
// }
//
// export function addPayment(data) {
//   return request({
//     url: '/api/v2/billing/payment/',
//     method: 'post',
//     data
//   })
// }
