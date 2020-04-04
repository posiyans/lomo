import request from '@/utils/request'

export function getSteadsList(query) {
    return request({
        url: '/api/v1/user/steads-list',
        method: 'get',
        params: query
    })
}
