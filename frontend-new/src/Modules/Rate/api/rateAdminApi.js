import request from 'src/utils/request'

export function fetchList(query) {
  return request({
    url: '/api/v1/admin/setting/rate',
    method: 'get',
    params: query
  })
}

export function createRate(data) {
  return request({
    url: '/api/v1/admin/setting/rate/',
    method: 'post',
    data
  })
}

export function createRateType(data) {
  return request({
    url: '/api/v2/rate/type/create',
    method: 'post',
    data
  })
}

export function updateRateType(id, data) {
  return request({
    url: '/api/v2/rate/type/update/' + id,
    method: 'post',
    data
  })
}

export function updateRateGroup(id, data) {
  return request({
    url: '/api/v2/rate/group/update/' + id,
    method: 'post',
    data
  })
}

export function updateRate(id, data) {
  return request({
    url: '/api/v2/rate/update/' + id,
    method: 'post',
    data
  })
}

export function createRateGroup(data) {
  return request({
    url: '/api/v2/rate/group/create',
    method: 'post',
    data
  })
}

export function getRateGroupList() {
  return request({
    url: '/api/v2/rate/group/get-list',
    method: 'get'
  })
}

export function getRateListForGroup(id) {
  return request({
    url: '/api/v2/rate/get/' + id,
    method: 'get'
  })
}

export function getRateDependsList() {
  return new Promise(resolve => {
    const resData = [
      {
        value: 1,
        label: 'расчет от участка'
      },
      {
        value: 2,
        label: 'расчет по показаниям'
      },
      {
        value: 3,
        label: 'фиксированная сумма'
      }
    ]
    resolve({
      data: {
        data: resData
      }
    })
  })
}
