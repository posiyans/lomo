import request from 'src/utils/request'

export function getYandexMap(params) {
  return request({
    url: '/api/v2/yandex/map/get-steads',
    method: 'get',
    params
  })
}
