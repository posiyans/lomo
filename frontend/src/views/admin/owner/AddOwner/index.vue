<template>
  <div class="app-container">
    <div class="page-title">Добавить собственника</div>
    <div class="pt3">
      <el-form ref="form" :model="form" label-position="top" label-width="120px">
        <el-form-item label="Фамилия" required>
          <el-input v-model="form.surname" placeholder="Фамилия" />
        </el-form-item>
        <el-form-item label="Имя">
          <el-input v-model="form.name" placeholder="Имя" />
        </el-form-item>
        <el-form-item label="Отчетство">
          <el-input v-model="form.middle_name" placeholder="Отчетство" />
        </el-form-item>
        <el-form-item label="Дата рождения">
          <el-date-picker
            v-model="form.date_birth"
            type="date"
            placeholder="Дата рождения"
            format="dd-MM-yyyy"
            value-format="yyyy-MM-dd"
            style="width: 100%;"
          />
        </el-form-item>
        <el-form-item label="Номер телефона">
          <div style="width: 250px;">
            <el-input v-model="form.general_phone" v-mask="'+7(###)###-##-##'" placeholder="Номер телефона" />
          </div>
        </el-form-item>
        <el-form-item label="Доп. номера">
          <div style="width: 250px;">
            <el-input v-model="form.phones" placeholder="79111111111, 7922222222" />
          </div>
        </el-form-item>
        <el-form-item label="Электронная почта для получения уведомлений">
          <el-input v-model="form.email" placeholder="Электронная почта" />
        </el-form-item>
        <el-form-item label="Aдрес места жительства">
          <el-input v-model="form.address" placeholder="Aдрес жительства" />
        </el-form-item>
        <el-form-item label="Почтовый адрес для получения уведомлений">
          <el-input v-model="form.address_notifications" placeholder="Адрес для уведомлений" />
        </el-form-item>
        <el-form-item label="Членство в СНТ" required>
          <el-select v-model="form.member" placeholder="Является собственик членом СНТ">
            <el-option label="ДА" :value="true" />
            <el-option label="НЕТ" :value="false" />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">Добавить</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { addOwnerUser } from '@/api/admin/owner/owner-api'

export default {
  data() {
    return {
      form: {
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
      }
    }
  },
  methods: {
    onSubmit() {
      addOwnerUser({ user: this.form })
        .then(response => {
          console.log(response.data)
          if (response.data.status) {
            console.log('push')
            this.$router.push('/admin/owner/show-info/' + response.data.data)
          } else {
            console.log('er')
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
      console.log('submit!')
    }
  }
}
</script>

<style scoped>

</style>
