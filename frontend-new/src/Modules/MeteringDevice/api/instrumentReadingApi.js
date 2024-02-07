import request from 'src/utils/request'

export function getInstrumentReadingList(params) {
  return request({
    url: '/api/v2/instrument-reading/get-list',
    method: 'get',
    params
  })
}

export function getInstrumentReadingListXlsx(params) {
  request.defaults.timeout = 60000
  return request({
    url: '/api/v2/instrument-reading/get-list',
    method: 'get',
    responseType: 'blob',
    params
  })
}

export function addInstrumentReading(data) {
  return request({
    url: '/api/v2/instrument-reading/',
    method: 'post',
    data
  })
}

export function deleteInstrumentReading(readingId) {
  return request({
    url: '/api/v2/instrument-reading/' + readingId,
    method: 'delete'
  })
}
