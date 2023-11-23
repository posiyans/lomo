import request from 'src/utils/request'

export function getReceipt(data) {
  return request({
    url: '/api/v1/admin/receipt/get-for-stead',
    method: 'post',
    responseType: 'blob',
    data
  })
}

/**
 * получить квитанцию для счета
 *
 * @param {Data} data
 */
export function getReceiptForInvoice(params) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v1/admin/receipt/get-for-invoices',
    method: 'get',
    responseType: 'blob',
    params
  })
}

// todo удалить позже
export function getReceiptForSteadList(data) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v1/admin/receipt/get-for-list-steads',
    method: 'post',
    responseType: 'blob',
    data
  })
}

export function getReceiptList(params) {
  return request({
    url: '/api/v1/admin/receipt/get-list',
    method: 'get',
    params
  })
}

export function fetchReceiptTypeList(params) {
  return request({
    url: '/api/v1/admin/setting/receipt-type',
    method: 'get',
    params
  })
}

export function getReceiptTypeInfo(id, params) {
  return request({
    url: '/api/v1/admin/setting/receipt-type/' + id,
    method: 'get',
    params
  })
}

export function updateReceiptType(id, data) {
  return request({
    url: '/api/v1/admin/setting/receipt-type/' + id,
    method: 'put',
    data
  })
}

export function createReceiptType(data) {
  return request({
    url: '/api/v1/admin/setting/receipt-type',
    method: 'post',
    data
  })
}
