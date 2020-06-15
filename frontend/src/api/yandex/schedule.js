import request from '@/utils/request'

export function fetchYandexSchedule(query) {
  return request({
    url: '/rasp',
    method: 'get',
    params: query
  })
}
