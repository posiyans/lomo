import request from '../utils/request'

export function getTemper() {
    return request({
        url: '/temper/get',
        method: 'get'
    })
}
