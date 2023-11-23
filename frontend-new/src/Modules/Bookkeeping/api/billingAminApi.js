import request from 'src/utils/request'

export function fetchBillingReestrList(params) {
  return request({
    url: '/api/v1/admin/billing/reestr',
    method: 'get',
    params
  })
}

export function DeleteReestr(id) {
  return request({
    url: '/api/v1/admin/billing/reestr/' + id,
    method: 'delete'
  })
}

export function createBillingReestr(data) {
  return request({
    url: '/api/v1/admin/billing/reestr',
    method: 'post',
    data
  })
}

export function updateBillingReestr(id, data) {
  return request({
    url: '/api/v1/admin/billing/reestr/' + id,
    method: 'put',
    data
  })
}

export function fetchReestr($id) {
  return request({
    url: '/api/v1/admin/billing/reestr/' + $id,
    method: 'get'
  })
}

export function fetchBillingBalansList(params) {
  return request({
    url: '/api/v1/admin/billing/balance-list',
    method: 'get',
    params
  })
}

export function fetchBillingBalansXLSX(data) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/billing/balance-xlsx',
    method: 'post',
    responseType: 'blob',
    data
  })
}

export function fetchBillingBalansSteadInfo(query) {
  return request({
    url: '/api/v1/admin/billing/balance-info',
    method: 'get',
    params: query
  })
}

export function fetchBillingBalansSteadInfoFronXlsx(params) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/billing/balance-for-stead-xlsx',
    method: 'get',
    responseType: 'blob',
    params
  })
}

export function fetchBillingAllBalansForStead(params) {
  return request({
    url: '/api/v1/admin/billing/balance-for-stead',
    method: 'get',
    params
  })
}

export function fetchBillingBankReestrInfo(params) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-info',
    method: 'get',
    params
  })
}

export function BillingBankReestrParse(data) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-parse',
    method: 'post',
    data
  })
}

export function fetchBillingBankReestrList(params) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-list',
    method: 'get',
    params
  })
}

export function updateBillingBankReestr(data) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-update',
    method: 'post',
    data
  })
}

export function publishBillingBankReestr(data) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-publish',
    method: 'post',
    data
  })
}
