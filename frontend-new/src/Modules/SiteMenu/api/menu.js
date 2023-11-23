import request from 'src/utils/request'

export function getSiteMenu(params) {
  return request({
    url: '/api/v2/site-menu/get',
    method: 'get',
    params
  })
}

export function addSiteMenu(data) {
  return request({
    url: '/api/v2/site-menu/add',
    method: 'post',
    data
  })
}

export function updateSiteMenu(id, data) {
  return request({
    url: '/api/v2/site-menu/update/' + id,
    method: 'post',
    data
  })
}

export function deleteSiteMenu(id) {
  return request({
    url: '/api/v2/site-menu/delete/' + id,
    method: 'delete'
  })
}
