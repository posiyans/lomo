import request from 'src/utils/request'

export function fetchYandexSchedule(query) {
  return request({
    url: '/api/v1/yandex/schedule/get',
    method: 'get',
    params: query
  })
}
