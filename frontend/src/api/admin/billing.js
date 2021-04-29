import request from '@/utils/request'

export function fetchBillingReestrList(query) {
  return request({
    url: '/api/v1/admin/billing/reestr',
    method: 'get',
    params: query
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

export function fetchBillingBalansList(query) {
  return request({
    url: '/api/v1/admin/billing/balance-list',
    method: 'get',
    params: query
  })
}

export function fetchBillingBalansXLSX(data) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/billing/balance-xlsx',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}

export function fetchBillingBalansSteadInfo(query) {
  return request({
    url: '/api/v1/admin/billing/balance-info',
    method: 'get',
    params: query
  })
}

export function fetchBillingBalansSteadInfoFronXlsx(query) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/billing/balance-for-stead-xlsx',
    method: 'get',
    responseType: 'blob',
    params: query
  })
}

export function fetchBillingAllBalansForStead(query) {
  return request({
    url: '/api/v1/admin/billing/balance-for-stead',
    method: 'get',
    params: query
  })
}

export function fetchBillingBankReestrInfo(query) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-info',
    method: 'get',
    params: query
  })
}

export function BillingBankReestrParse(data) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-parse',
    method: 'post',
    data: data
  })
}

export function fetchBillingBankReestrList(query) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-list',
    method: 'get',
    params: query
  })
}

export function updateBillingBankReestr(data) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-update',
    method: 'post',
    data: data
  })
}

export function publishBillingBankReestr(data) {
  return request({
    url: '/api/v1/admin/billing/bank-reestr-publish',
    method: 'post',
    data: data
  })
}

