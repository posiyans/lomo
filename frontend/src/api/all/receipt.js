import request from '@/utils/request'

export function getReceiptForStead(data) {
  return request({
    url: '/api/v1/receipt/get-receipt-contributions-for-stead',
    method: 'post',
    responseType: 'blob',
    data: data
  })
}
