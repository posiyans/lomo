import axios from 'axios'
import { getCsrfCookieToken } from 'src/Modules/User/api/auth'
import { Notify } from 'quasar'

// create an axios instance
const service = axios.create({
  baseURL: process.env.BASE_API, // url = base url + request url
  withCredentials: true, // send cookies when cross-domain requests
  timeout: 25000 // request timeout,
})

service.interceptors.request.use(
  config => {
    return config
  },
  error => {
    // do something with request error
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  response => {
    const res = response
    return res
  },
  error => {
    // Notify.create('Danger, Will Robinson! Danger!')
    if (error.response.status === 419) {
      getCsrfCookieToken()
        .then(res => {
          Notify.create({
            message: 'Ваша сессия устарела.',
            caption: 'Попробуйте еще раз',
            color: 'info'
          })
        })
    }
    return Promise.reject(error)
  }
)

export default service
