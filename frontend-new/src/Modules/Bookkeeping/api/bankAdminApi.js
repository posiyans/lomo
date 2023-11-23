import request from 'src/utils/request'

export function uploadDischarge(data) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v1/admin/billing/bank-reestr/upload',
    method: 'post',
    data
  })
}
