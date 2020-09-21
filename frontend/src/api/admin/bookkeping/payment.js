import request from '@/utils/request'

export function fetchPaymentInfo(id) {
  return request({
    url: '/api/v1/admin/billing/payment/' + id,
    method: 'get',
  })
}


export function updatePaymentInfo(id, data) {
  return request({
    url: '/api/v1/admin/billing/payment/' + id,
    method: 'put',
    data
  })
}


