import request from 'src/utils/request'

export function getReceiptForStead(params) {
  return request({
    url: '/api/v1/receipt/get-receipt-contributions-for-stead',
    method: 'get',
    responseType: 'blob',
    params
  })
}

// for admin
export function createReceiptType(data) {
  return request({
    url: '/api/v2/rate/type/create',
    method: 'post',
    data
  })
}
