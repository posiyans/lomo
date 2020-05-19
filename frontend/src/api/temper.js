import request from '../utils/request'

export function getTemper() {
    return request({
        url: '/api/temper/get',
        method: 'get'
    })
}

export function getTemperNow() {
    return request({
        url: '/api/temper/now',
        method: 'post'
    })
}

export function getNewWeatherProHD() {
  return request({
    url: '/temper/getNewWeatherProHD',
    method: 'get'
  })
}
