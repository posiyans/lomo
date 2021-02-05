import request from '@/utils/request'

export function fetchBillingReestrList(query) {
  return request({
    url: '/api/v1/admin/billing/reestr',
    method: 'get',
    params: query
  })
}

// export function updateAppel(data, id) {
//   return request({
//     url: '/api/v1/admin/appeal/' + id,
//     method: 'put',
//     data
//   })
// }
//
// export function fetchArticle(id) {
//   return request({
//     url: '/api/v1/article/detail',
//     method: 'get',
//     params: { id }
//   })
// }
//
// export function fetchPv(pv) {
//   return request({
//     url: '/api/v1/article/pv',
//     method: 'get',
//     params: { pv }
//   })
// }

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
updateBillingReestr

export function fetchReestr($id) {
  return request({
    url: '/api/v1/admin/billing/reestr/' + $id,
    method: 'get'
  })
}

// export function updateArticle(data) {
//   return request({
//     url: '/api/v1/article/update',
//     method: 'post',
//     data
//   })
// }

export function fetchBillingBalansList(query) {
  return request({
    url: '/api/v1/admin/billing/balance-list',
    method: 'get',
    params: query
  })
}

export function fetchBillingBalansSteadInfo(query) {
  return request({
    url: '/api/v1/admin/billing/balance-info',
    method: 'get',
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

