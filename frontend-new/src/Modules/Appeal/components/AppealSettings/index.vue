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
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <AppealTypeList />
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

export default defineComponent({
  components: {
    AppealTypeList
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const dialogModel = ref(false)
    const showDialog = () => {
      dialogModel.value = true
    }
    return {
      data,
      dialogModel,
      showDialog
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
