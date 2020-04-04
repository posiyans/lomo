import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/api/v1/admin/voting',
    method: 'get',
    params: query
  })
}

export function fetchVoting(id) {
  return request({
    url: '/api/v1/admin/voting/' + id,
    method: 'get'
  })
}

export function updateVoting(data, id) {
  return request({
    url: '/api/v1/admin/voting/' + id,
    method: 'put',
    data
  })
}

export function createVoting(data) {
  return request({
    url: '/api/v1/admin/voting',
    method: 'post',
    data
  })
}


export function fetchVotingResuiltList(query) {
  return request({
    url: '/api/v1/admin/voting-result',
    method: 'get',
    params: query
  })
}
