import request from 'src/utils/request'

export function getWeather(params) {
  return request({
    url: '/api/v2/weather/get',
    method: 'get',
    params
  })
}
