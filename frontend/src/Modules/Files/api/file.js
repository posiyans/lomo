import request from '@/utils/request'

export function userUploadFile(data) {
  return request.post(
    '/api/v2/file/user-upload',
    data,
    {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }
  )
}
