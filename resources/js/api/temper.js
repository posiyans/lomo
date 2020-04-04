import request from '../utils/request'

export function getTemper() {
    return request({
        url: '/temper/get',
        method: 'get'
    })
}

export function getTemperNow() {
    return request({
        url: '/temper/now',
        method: 'post'
    })
}
