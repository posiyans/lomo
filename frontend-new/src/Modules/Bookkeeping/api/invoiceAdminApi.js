import request from 'src/utils/request'

export function getInvoiceList(params) {
  return request({
    url: '/api/v2/billing/invoice/get-list',
    method: 'get',
    params
  })
}

export function getInvoiceListXlsx(params) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v2/billing/invoice/get-list',
    method: 'get',
    responseType: 'blob',
    params
  })
}

export function deleteInvoice(id) {
  return request({
    url: '/api/v2/billing/invoice/delete/' + id,
    method: 'delete'
  })
}

export function updateInvoiceField(id, data) {
  return request({
    url: '/api/v2/billing/invoice/' + id,
    method: 'put',
    data
  })
}

export function addPaymentInInvoice(id, data) {
  return request({
    url: '/api/v2/billing/invoice/add-payment/' + id,
    method: 'post',
    data
  })
}

export function addInvoice(data) {
  return request({
    url: '/api/v2/billing/invoice/',
    method: 'post',
    data
  })
}
