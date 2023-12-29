import request from 'src/utils/request'

export function getInvoiceList(params) {
  return request({
    url: '/api/v2/billing/invoice/get-list',
    method: 'get',
    params
  })
}

export function deleteInvoice(id) {
  return request({
    url: '/api/v2/billing/invoice/delete/' + id,
    method: 'delete'
  })
}

export function fetchInvoiceInfo(id) {
  return request({
    url: '/api/v1/admin/billing/invoice/' + id,
    method: 'get'
  })
}

export function addInvoiceForGroupReadings(data) {
  return request({
    url: '/api/v1/admin/billing/insrumet-readings/add-group-invoice',
    method: 'post',
    data
  })
}

export function addInvoiceForReadings(id) {
  return request({
    url: '/api/v1/admin/billing/insrumet-readings/add-invoice/' + id,
    method: 'post'
  })
}

export function updateInvoice(id, data) {
  return request({
    url: '/api/v1/admin/billing/invoice/' + id,
    method: 'put',
    data
  })
}

export function addInvoiceForStead(data) {
  return request({
    url: '/api/v1/admin/billing/invoice/',
    method: 'post',
    data
  })
}

export function addPaymentForInvoice(data) {
  return request({
    url: '/api/v1/admin/billing/change-invoice/add-payment',
    method: 'post',
    data
  })
}

export function deletePaymentForInvoice(data) {
  return request({
    url: '/api/v1/admin/billing/change-invoice/delete-payment',
    method: 'post',
    data
  })
}

export function changeStatusForInvoice(data) {
  return request({
    url: '/api/v1/admin/billing/change-invoice/change-status',
    method: 'post',
    data
  })
}
