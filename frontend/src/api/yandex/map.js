import request from '@/utils/request'

export function getYandexMap(query) {
  return request({
    url: '/api/v1/yandex/get-map',
    method: 'get',
    params: query
  })
}
