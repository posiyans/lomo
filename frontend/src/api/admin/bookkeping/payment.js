import request from '@/utils/request'

export function fetchPaymentList(params) {
  return request({
    url: '/api/v1/admin/billing/payment',
    method: 'get',
    params: params
  })
}

export function fetchPaymentInfo(id) {
  return request({
    url: '/api/v1/admin/billing/payment/' + id,
    method: 'get'
  })
}

export function updatePaymentInfo(id, data) {
  return request({
    url: '/api/v1/admin/billing/payment/' + id,
    method: 'put',
    data
  })
}
