import request from '@/utils/request'

export function uploadFile(data) {
  return request.post(
    '/api/v1/file',
    data,
    {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
}
