<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn color="primary" label="Добавить" />
      </slot>
    </div>
    <q-dialog v-model="dialogVisible">
      <q-card style="min-width: 450px;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Добавить группу платежей</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <q-input
            v-model="newRate.title"
            outlined
            hint="Введите название типа платежа (электроэнергия, взносы)"
            label="Название группы платежей"
          />
        </q-card-section>
        <q-card-section>
          <div class="q-gutter-sm text-right">
            <q-btn color="negative" flat label="Отмена" v-close-popup />
            <q-btn color="primary" label="Добавить" @click="saveData" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { createRateGroup } from 'src/Modules/Rate/api/rateAdminApi'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const newRate = ref({
      title: ''
    })
    const showDialog = () => {
      newRate.value.title = ''
      dialogVisible.value = true
    }
    const saveData = () => {
      const data = {
        name: newRate.value.title
      }
      createRateGroup(data)
        .then(res => {
          dialogVisible.value = false
          emit('success')
        })
    }
    return {
      dialogVisible,
      saveData,
      showDialog,
      newRate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
