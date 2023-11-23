<template>
  <div>
    <div @click="loginVK" class="vk-logo-btn row items-center" :class="{'cursor-pointer': !disable}">
      <div style="padding-top: 5px">
        <img src="~assets/SocialMedia/Vk/vk-logo.svg" width="23" alt="vk">
      </div>
      <div class="text-weight-bold text-big-80">
        ID
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { useVkAuth } from 'src/Modules/SocialMedia/vendors/Vk/hooks/useVkAuth'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useQuasar } from 'quasar'

export default defineComponent({
  props: {
    disable: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const vkAuth = useVkAuth()
    const $q = useQuasar()
    const authStore = useAuthStore()
    const loginVK = () => {
      if (!props.disable) {
        if (authStore.user.uid) {
          getPassword()
        } else {
          vkAuth.login()
        }
      }
    }
    const getPassword = () => {
      $q.dialog({
        title: 'Прикрепить VK ID?',
        message: 'Введите пароль от текущей учетной записи',
        prompt: {
          model: '',
          type: 'password'
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        vkAuth.login({ password: data })
      })
    }
    return {
      loginVK,
      authStore
    }
  }
})
</script>

<style scoped lang="scss">
.vk-logo-btn {
  border-radius: 5px;
  padding: 5px 10px;

  &:hover {
    background-color: $grey-3;
  }
}
</style>
