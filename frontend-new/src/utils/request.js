import axios from 'axios'
import { getCsrfCookieToken } from 'src/Modules/Auth/api/auth'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

// create an axios instance
const service = axios.create({
  baseURL: process.env.BASE_API, // url = base url + request url
  withCredentials: true, // send cookies when cross-domain requests
  timeout: 25000 // request timeout,
})

service.interceptors.request.use(
  config => {
    config.headers.Accept = 'application/json'
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(getCookie('XSRF-TOKEN'))
    return config
  },
  error => {
    // do something with request error
    return Promise.reject(error)
  }
)

function getCookie(name) {
  const cookies = document.cookie.split(';')
  for (const cookie of cookies) {
    const [key, value] = cookie.split('=')
    if (key.trim() === name) {
      return value
    }
  }
  return ''
}

// response interceptor
service.interceptors.response.use(
  response => {
    const res = response
    return res
  },
  error => {
    if (error.response.status === 419) {
      getCsrfCookieToken()
        .then(res => {
          const autUserSore = useAuthStore()
          autUserSore.getMyInfo()
          // Notify.create({
          //   message: 'Ваша сессия устарела.',
          //   caption: 'Попробуйте еще раз',
          //   color: 'info'
          // })
        })
    }
    // return error
    return Promise.reject(error)
  }
)

export default service
