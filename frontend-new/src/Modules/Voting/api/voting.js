import request from 'src/utils/request'

export function fetchVotingQuestionResultInfo (query) {
  return request({
    url: '/api/v2/voting/result/question',
    method: 'get',
    params: query
  })
}

export function fetchUserVotingList (query) {
  return request({
    url: '/api/v1/user/voting',
    method: 'get',
    params: query
  })
}

export function fetchUserVoting (id) {
  return request({
    url: '/api/v1/user/voting/' + id,
    method: 'get'
  })
}

export function userAddAnswerToVoting (data) {
  return request({
    url: '/api/v1/user/vote',
    method: 'post',
    data
  })
}

export function votingSendSms (data) {
  return request({
    url: '/api/v1/user/voting/sms/send',
    method: 'post',
    data
  })
}

export function votingValidSms (data) {
  return request({
    url: '/api/v1/user/voting/sms/valid',
    method: 'post',
    data
  })
}

export function getQuestionResult (params) {
  return request({
    url: '/api/v1/user/voting/question/result/get',
    method: 'get',
    params
  })
}

export function getQuestionsForVoting(params) {
  return request({
    url: '/api/v2/voting/question/get-for-voting',
    method: 'get',
    params
  })
}

export function getAnswersForQuestion(params) {
  return request({
    url: '/api/v2/voting/answer/get-for-question',
    method: 'get',
    params
  })
}
