<template>
  <div class="q-pa-md">

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
      </el-form-item>
      <div class="row items-center q-col-gutter-sm text-grey">
        <div>
          обновлено
        </div>
        <ShowTime :time="form.updated_at" />
      </div>
    </el-form>
  </div>
</template>
<script>
import { getGardeninngInfo } from 'src/Modules/Gardening/api/gardening.js'
import { updateGardeninngInfo } from 'src/Modules/Gardening/api/gardening-admin-api.js'
import ShowTime from 'components/ShowTime/index.vue'

export default {
  components: {
    ShowTime
  },
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
        .then(res => {
          this.getData()
          this.$q.notify({
            message: 'Данные успешно обновлены',
            color: 'secondary'
          })
          // console.log(response.data)
        })
      // console.log('submit!');
    }

  }
}
</script>
