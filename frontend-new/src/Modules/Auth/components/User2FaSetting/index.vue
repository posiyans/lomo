<template>
  <div v-if="user">
    <div class="row items-center q-col-gutter-md q-pa-md">
      <div>
        <q-checkbox
          v-model="user.two_factor"
          label="Двухэтапная аутентификация "
          color="negative"
          @update:model-value="saveData"
        />
      </div>
    </div>
    <div v-if="user.two_factor">
      <div class="text-grey q-pa-sm">Двухэтапная аутентификация через</div>
      <div v-for="item in user.two_factor_valid" :key="item.key" class="row items-center">
        <div>
          <q-checkbox v-model="user.two_factor_enable" :val="item.key" @update:model-value="saveData" />
        </div>
        <div class="q-py-md q-mr-lg" :class="{ 'text-negative': item.error}">
          <div>
            {{ item.label }}
          </div>
          <div v-if="item.error" class="text-small-80">
            {{ item.error_message }}
          </div>
        </div>
        <div v-if="item.key === 'google2fa'">
          <ChangeSecretKey v-if="showChangeSecret" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getTwoFactorSettingsForUser, updateTwoFactorSettingsForUser } from 'src/Modules/Auth/api/auth.js'
import ChangeSecretKey from 'src/Modules/Google2Fa/components/ChangeSecretKey/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ChangeSecretKey
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const showChangeSecret = ref(true)
    const authStore = useAuthStore()
    const user = ref(null)
    const getData = () => {
      const data = {
        uid: authStore.user.uid
      }
      getTwoFactorSettingsForUser(data)
        .then(res => {
          user.value = res.data
        })
    }
    const saveData = () => {
      updateTwoFactorSettingsForUser(user.value)
        .then(res => {
          getData()
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      user,
      showChangeSecret,
      saveData
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
