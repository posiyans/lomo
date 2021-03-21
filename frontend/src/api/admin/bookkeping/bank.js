import request from '@/utils/request'

export function uploadDischarge(data) {
  request.defaults.timeout = ''
  return request({
    url: '/api/v1/admin/billing/bank-reestr/upload',
    method: 'post',
    data: data
  })
}
