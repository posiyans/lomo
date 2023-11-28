<template>
  <div class="q-pa-md">
    <div class="q-gutter-md">
      <div>
        <q-input
          v-model="form.surname"
          outlined
          label="Фамилия"
        />
      </div>
      <div>
        <q-input
          v-model="form.name"
          outlined
          label="Имя"
        />
      </div>
      <div>
        <q-input
          v-model="form.middle_name"
          outlined
          label="Отчетство"
        />
      </div>
      <div style="max-width: 320px;">
        <InputDate
          v-model="form.date_birth"
          outlined
          label="Дата рождения"
        />
      </div>
      <div style="max-width: 320px;">
        <InputPhone
          v-model="form.general_phone"
          outlined
          label="Номер телефона"
        />
      </div>
      <div>
        <q-input
          v-model="form.phones"
          outlined
          label="Доп. номера"
          placeholder="79111111111, 7922222222"
        />
      </div>
      <div>
        <q-input
          v-model="form.email"
          outlined
          label="Электронная почта для получения уведомлений"
          :rules="[ isEmail ]"
        />
      </div>
      <div>
        <q-input
          v-model="form.address"
          outlined
          label="Адрес места жительства"
        />
      </div>
      <div>
        <q-input
          v-model="form.address_notifications"
          outlined
          label="Почтовый адрес для получения уведомлений"
        />
      </div>
      <div style="max-width: 320px;">
        <q-select
          outlined
          v-model="form.member"
          :options="[
            {
             label: 'Да',
             value:  true
            },
             {
              label: 'Нет',
             value:  false
            }
          ]"
          map-options
          emit-value
          label="Является собственик членом СНТ"
        />
      </div>
      {{ form }}
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Добавить</el-button>
      </el-form-item>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { addOwnerUser } from 'src/Modules/Owner/api/owner-admin-api'
import { errorMessage } from 'src/utils/message'
import { useRouter } from 'vue-router'
import InputDate from 'src/components/Input/InputDate/index.vue'
import InputPhone from 'src/components/Input/InputPhone/index.vue'
import { isEmail } from 'src/utils/validators.js'

export default defineComponent({
  components: {
    InputDate,
    InputPhone
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const $router = useRouter()
    const form = ref({
      surname: '',
      name: '',
      middle_name: '',
      date_birth: null,
      email: '',
      general_phone: '',
      phones: '',
      address: '',
      address_notifications: '',
      member: ''
    })
    const onSubmit = () => {
      addOwnerUser({ user: form.value })
        .then(response => {
          // $router.push('/admin/owner/show-info/' + response.data.data)
        })
        .catch(er => {
          errorMessage(er.response.errors)
        })
    }
    return {
      form,
      isEmail,
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
