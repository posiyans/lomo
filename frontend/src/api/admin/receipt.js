import request from '@/utils/request'

export function getReceipt(data) {
  return request({
    url: '/api/v1/admin/receipt/get-for-stead',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}
