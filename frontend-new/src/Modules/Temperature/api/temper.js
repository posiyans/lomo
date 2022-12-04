import request from 'src/utils/request'

export function getTemper () {
  return request({
    url: '/api/v1/temper/get',
    method: 'get'
  })
}

export function getTemperNow () {
  return request({
    url: '/api/v1/temper/now',
    method: 'post'
  })
}

export function getNewWeatherProHD () {
  return request({
    url: '/api/v1/temper/getNewWeatherProHD',
    method: 'get'
  })
}

export function getNowWeatherProHD () {
  return request({
    url: '/api/v1/temper/getNowWeatherProHD',
    method: 'get'
  })
}
