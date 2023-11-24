<template>
  <div class="q-pa-md">
    <q-form
      class="q-gutter-md"
      @submit="onSubmit"
    >
      <q-input
        outlined
        v-model="form.name"
        label="Название СНТ"
      />
      <q-input
        outlined
        v-model="form.full_name"
        label="Полное название"
      />
      <q-input
        outlined
        v-model="form.PersonalAcc"
        label="Счет"
      />

      <q-input
        outlined
        v-model="form.BankName"
        label="Банк"
      />

      <q-input
        outlined
        v-model="form.BIC"
        label="БИК"
      />

      <q-input
        outlined
        v-model="form.CorrespAcc"
        label="Корсчет"
      />
      <q-input
        outlined
        v-model="form.PayeeINN"
        label="ИНН"
      />
      <div>
        <q-btn label="Отмена" type="reset" color="negative" flat class="q-ml-sm" />
        <q-btn label="Сохранить" type="submit" color="primary"/>
      </div>
    </q-form>
    <div class="row items-center q-col-gutter-sm text-grey">
      <div>
        обновлено
      </div>
      <ShowTime :time="form.updated_at" />
    </div>

  </div>
</template>
<script>
import { getGardeningInfo, updateGardeningInfo } from 'src/Modules/Gardening/api/gardening.js'

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
      getGardeningInfo()
        .then(response => {
          this.form = response.data
        })
    },
    onSubmit() {
      updateGardeningInfo(this.form)
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
