import request from '@/utils/request'

export function fetchReceiptTypeList(query) {
  return request({
    url: '/api/v1/admin/setting/receipt-type',
    method: 'get',
    params: query
  })
}

export function getReceiptTypeInfo(id, query) {
  return request({
    url: '/api/v1/admin/setting/receipt-type/' + id,
    method: 'get',
    params: query
  })
}

export function updateReceiptType(id, data) {
  return request({
    url: '/api/v1/admin/setting/receipt-type/' + id,
    method: 'put',
    data: data
  })
}

export function createReceiptType(data) {
  return request({
    url: '/api/v1/admin/setting/receipt-type',
    method: 'post',
    data: data
  })
}

