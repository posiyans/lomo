import request from 'src/utils/request'

export function getReceiptForStead (data) {
  return request({
    url: '/api/v1/receipt/get-receipt-contributions-for-stead',
    method: 'post',
    responseType: 'blob',
    data
  })
}

export function getReceiptTypeList (params) {
  return request({
    url: '/api/v1/receipt/get-receipt-type-list',
    method: 'get',
    params
  })
}

export function getReceiptForParams (data) {
  return request({
    url: '/api/v1/receipt/get-receipt-for-params',
    method: 'post',
    responseType: 'blob',
    data
  })
}

export function fetchList (query) {
  return request({
    url: '/api/v1/user/rate',
    method: 'get',
    params: query
  })
}
