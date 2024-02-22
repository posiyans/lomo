<template>
  <div>
    <q-btn icon="settings" color="grey" flat @click="showDialog" />
    <q-dialog
      v-model="dialogModel"
      maximized
    >
      <q-card>
        <q-card-section class="row items-center">
          <div class="text-h6">Настройки обращений</div>
          <AddAppealTypeBtn @success="reload" />
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <AppealTypeList :key="key" />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat size="md" label="OK" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import AppealTypeList from './components/AppealTypeList/index.vue'
import AddAppealTypeBtn from 'src/Modules/Appeal/components/AddAppealTypeBtn/index.vue'

export default defineComponent({
  components: {
    AppealTypeList,
    AddAppealTypeBtn
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const dialogModel = ref(false)
    const showDialog = () => {
      dialogModel.value = true
    }
    const reload = () => {
      key.value++
    }
    return {
      key,
      reload,
      dialogModel,
      showDialog
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
