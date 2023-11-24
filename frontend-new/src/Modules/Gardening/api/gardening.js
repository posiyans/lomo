import request from 'src/utils/request'

export function getGardeningInfo () {
  return request({
    url: '/api/v2/gardening/get',
    method: 'get'
  })
}

export function updateGardeningInfo(data) {
  return request({
    url: '/api/v2/gardening/update',
    method: 'post',
    data
  })
}
