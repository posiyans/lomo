import request from 'src/utils/request'

export function getReceiptForStead (params) {
  return request({
    url: '/api/v1/receipt/get-receipt-contributions-for-stead',
    method: 'get',
    responseType: 'blob',
    params
  })
}

// export function getReceiptTypeList(params) {
//   return request({
//     url: '/api/v1/receipt/get-receipt-type-list',
//     method: 'get',
//     params: params
//   })
// }
//
// export function getReceiptForParams(data) {
//   return request({
//     url: '/api/v1/receipt/get-receipt-for-params',
//     method: 'post',
//     responseType: 'blob',
//     data: data
//   })
// }
