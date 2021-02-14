import request from '@/utils/request'

export function getReceipt(data) {
  return request({
    url: '/api/v1/admin/receipt/get-for-stead',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}

/**
 * получить квитанцию для счета
 *
 * @param {Data} data
 */
export function getReceiptForInvoice(params) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/receipt/get-for-invoices',
    method: 'get',
    responseType: 'blob',
    params: params
  })
}

// todo удалить позже
export function getReceiptForSteadList(data) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/receipt/get-for-list-steads',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}

export function getReceiptList(params) {
  return request({
    url: '/api/v1/admin/receipt/get-list',
    method: 'get',
    params: params
  })
}
