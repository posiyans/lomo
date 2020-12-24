import request from '@/utils/request'

export function fetchVotingList(query) {
  return request({
    url: '/api/v1/admin/voting',
    method: 'get',
    params: query
  })
}

export function fetchAdminVoting(id) {
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

export function fetchVotingResults(query) {
  return request({
    url: '/api/v1/admin/voting/result',
    method: 'get',
    params: query
  })
}

export function fetchVotingQuestions(id, query) {
  return request({
    url: '/api/v1/admin/voting/questions/' + id,
    method: 'get',
    params: query
  })
}

export function addUserAswers(data) {
  return request({
    url: '/api/v1/admin/voting/owner/add-answer',
    method: 'post',
    data
  })
}

export function getBulletinList(data) {
  return request({
    url: '/api/v1/admin/voting/owner/get-bulletin-list',
    method: 'post',
    data
  })
}
