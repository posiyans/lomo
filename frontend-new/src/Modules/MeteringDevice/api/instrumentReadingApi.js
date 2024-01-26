import request from 'src/utils/request'

export function getInstrumentReadingList(params) {
  return request({
    url: '/api/v2/instrument-reading/get-list',
    method: 'get',
    params
  })
}
