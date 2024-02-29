import request from 'src/utils/request'

export function getUploadFileUrl() {
  return '/api/v2/file/upload'
}

export async function userUploadFile(data, func) {
  return request.post(
    getUploadFileUrl(),
    data,
    {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: func
    }
  )
}

export function deleteFileApi(params) {
  return request({
    url: '/api/v2/file/delete',
    method: 'delete',
    params
  })
}
