import request from '@/utils/request'

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
    data: data
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
    data: data
  })
}

export function addInvoiceForStead(data) {
  return request({
    url: '/api/v1/admin/billing/invoice/',
    method: 'post',
    data: data
  })
}

// export function updatePaymentInfo(id, data) {
//   return request({
//     url: '/api/v1/admin/billing/payment/' + id,
//     method: 'put',
//     data
//   })
// }
//

