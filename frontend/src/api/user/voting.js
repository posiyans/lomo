import request from '@/utils/request'

export function fetchUserVotingList(query) {
  return request({
    url: '/api/v1/user/voting',
    method: 'get',
    params: query
  })
}

export function fetchUserVoting(id) {
  return request({
    url: '/api/v1/user/voting/' + id,
    method: 'get'
  })
}

export function userAddAnswerToVoting(data) {
  return request({
    url: '/api/v1/user/vote',
    method: 'post',
    data
  })
}

