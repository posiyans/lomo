import request from 'src/utils/request'

export function fetchYandexSchedule (query) {
  return request({
    url: '/api/v1/yandex/rasp',
    method: 'get',
    params: query
  })
}
