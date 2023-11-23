import { getAuthPathVK } from 'src/Modules/SocialMedia/vendors/Vk/api/apiVk.js'
import { Notify } from 'quasar'
import { getCsrfCookieToken } from 'src/Modules/Auth/api/auth'

export function useVkAuth() {
  const login = (data) => {
    getCsrfCookieToken()
      .then(() => {
        getAuthPathVK(data)
          .then(response => {
            const { data } = response
            window.location = data
          })
          .catch(er => {
            Notify.create({
              color: 'negative',
              message: er.response.data.errors
            })
          })
      })
  }
  return {
    login
  }
}
