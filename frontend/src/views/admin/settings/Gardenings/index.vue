<template>
  <div class="app-container">

    <el-form ref="form" :model="form" label-position="top" label-width="200px">
      <el-form-item label="Название">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Полное название">
        <el-input v-model="form.full_name" />
      </el-form-item>
      <el-form-item label="Счет">
        <el-input v-model="form.PersonalAcc" />
      </el-form-item>
      <el-form-item label="Банк">
        <el-input v-model="form.BankName" />
      </el-form-item>
      <el-form-item label="БИК">
        <el-input v-model="form.BIC" />
      </el-form-item>
      <el-form-item label="Корсчет">
        <el-input v-model="form.CorrespAcc" />
      </el-form-item>
      <el-form-item label="ИНН">
        <el-input v-model="form.PayeeINN" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Сохранить</el-button>
        <el-button>Отмена</el-button>
        обновлено {{ form.updated_at }}
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import { getGardeninngInfo } from '@/api/user/gardening.js'
import { updateGardeninngInfo } from '@/api/admin/gardening.js'
export default {
  data() {
    return {
      form: {
        name: '',
        full_name: '',
        PersonalAcc: '',
        BankName: '',
        BIC: '',
        CorrespAcc: '',
        PayeeINN: ''
      }
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      getGardeninngInfo()
        .then(response => {
          this.form = response.data
        })
    },
    onSubmit() {
      updateGardeninngInfo(this.form)
        .then(response => {
          // console.log(response.data)
        })
      // console.log('submit!');
    }

  }
}
</script>
