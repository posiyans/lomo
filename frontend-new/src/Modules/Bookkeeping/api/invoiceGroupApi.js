import request from 'src/utils/request'

export function getInvoiceGroupList(params) {
  return request({
    url: '/api/v2/billing/invoice-group/get-list',
    method: 'get',
    params
  })
}

export function addInvoiceGroup(data) {
  return request({
    url: '/api/v2/billing/invoice-group/',
    method: 'post',
    data
  })
}
