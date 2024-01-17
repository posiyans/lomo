import request from 'src/utils/request'

export function getPaymentList(params) {
  return request({
    url: '/api/v2/billing/payment/get-list',
    method: 'get',
    params
  })
}

export function getPayment(id) {
  return request({
    url: '/api/v2/billing/payment/' + id,
    method: 'get'
  })
}

export function updatePayment(id, data) {
  return request({
    url: '/api/v2/billing/payment/' + id,
    method: 'post',
    data
  })
}

export function deletePayment(id) {
  return request({
    url: '/api/v2/billing/payment/' + id,
    method: 'delete'
  })
}

export function addPayment(data) {
  return request({
    url: '/api/v2/billing/payment/',
    method: 'post',
    data
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

export function addPaymentForStead(data) {
  return request({
    url: '/api/v1/admin/billing/payment/',
    method: 'post',
    data
  })
}

export function fetchPaymentListinFile(params) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/billing/payment',
    method: 'get',
    responseType: 'blob',
    params
  })
}
