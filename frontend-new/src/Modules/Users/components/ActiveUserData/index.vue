<template>
  <div>
    <div class="q-gutter-sm">
      <div class="row items-center">
        <InputAndSaveProxy
          :model-value="activeUserStore.user.email"
          :func="func"
          class="col-grow"
          :readonly="readOnly"
          @success="setValue($event, 'email')"
        >
          <template #default="{ modelValue, setValue }">
            <q-input outlined :model-value="modelValue" :readonly="readOnly" label="E-mail" @update:model-value="setValue" />
          </template>
        </InputAndSaveProxy>
        <div class="q-px-md">
          <q-icon
            :name="activeUserStore.user.email_verified_at ? 'check_box' : 'check_box_outline_blank'"
            :color="activeUserStore.user.email_verified_at ? 'secondary' : 'negative'"
            size="md"
          >
            <q-tooltip>
              {{ emailVerifiedTitle }}
            </q-tooltip>
          </q-icon>
        </div>
      </div>
      <InputAndSaveProxy
        :model-value="activeUserStore.user.last_name"
        :func="func"
        label="Фамилия"
        name="last_name"
        :readonly="readOnly"
        outlined
        @success="setValue($event, 'last_name')"
      />
      <InputAndSaveProxy
        :model-value="activeUserStore.user.name"
        :func="func"
        label="Имя"
        name="name"
        :readonly="readOnly"
        outlined
        @success="setValue($event, 'name')"
      />
      <InputAndSaveProxy
        :model-value="activeUserStore.user.middle_name"
        :func="func"
        label="Отчество"
        name="middle_name"
        :readonly="readOnly"
        outlined
        @success="setValue($event, 'middle_name')"
      />
      <InputAndSaveProxy
        :model-value="activeUserStore.user.phone"
        :func="func"
        name="phone"
        @success="setValue($event, 'phone')"
      >
        <template #default="{ modelValue, setValue }">
          <InputPhone outlined :model-value="modelValue" :readonly="readOnly" label="Телефон" @update:model-value="setValue" />
        </template>
      </InputAndSaveProxy>
      <InputAndSaveProxy
        :model-value="activeUserStore.user.adres"
        :func="func"
        label="Адрес регистрации/почтовый адрес"
        name="adres"
        :readonly="readOnly"
        outlined
        autogrow
        @success="setValue($event, 'adres')"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'
import InputPhone from 'components/Input/InputPhone/index.vue'
import InputAndSaveProxy from 'components/Input/InputAndSaveProxy/index.vue'
import { updateUserFieldData } from 'src/Modules/Users/api/user-admin-api'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    InputPhone,
    InputAndSaveProxy
  },
  props: {},
  setup(props) {
    const func = updateUserFieldData
    const activeUserStore = useActiveUserStore()
    const authUser = useAuthStore()
    const key = ref(1)
    const emailVerifiedTitle = computed(() => {
      return activeUserStore.user.email_verified_at ? 'Email подтвержден' : 'Email не подтвержден'
    })
    const readOnly = computed(() => {
      return !authUser.permissions.includes('user-edit')
    })

    onMounted(() => {

    })
    const setValue = (val, name) => {
      activeUserStore.user[name] = val
    }
    return {
      key,
      func,
      setValue,
      readOnly,
      emailVerifiedTitle,
      activeUserStore
    }
  }
})
</script>

<style scoped>

</style>
