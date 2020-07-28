import request from '@/utils/request'

export function getReceipt(data) {
  return request({
    url: '/api/v1/admin/receipt/get-for-stead',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}

export function getReceiptForSteadList(data) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/receipt/get-for-list-steads',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}

export function getReestrForSteadList(data) {
  return request({
    url: '/api/v1/admin/receipt/get-reestr-for-list-steads',
    method: 'post',
    data: data
  })
}
