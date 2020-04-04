import request from '../utils/request'

export function getMyInfo() {
    return request({
        url: '/api/v1/user-info',
        method: 'get'
    })
}

export function setUserInfo(data) {
    return request({
        url: '/api/v1/save-user-info',
        method: 'post',
        data: data
    })
}
