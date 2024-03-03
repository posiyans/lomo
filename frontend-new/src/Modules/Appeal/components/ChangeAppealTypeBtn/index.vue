<template>
  <div>
    <q-btn icon="settings" flat round color="negative" @click="showDialog">
      <q-tooltip>
        Изменить тип
      </q-tooltip>
    </q-btn>
    <q-dialog v-model="dialogVisible">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Изменить тип обращения</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <AppealTypeSelect v-model="appealType" outlined />
          <div class="text-right q-py-md">
            <q-btn flat color="negative" label="Отмена" v-close-popup />
            <q-btn color="primary" label="Изменить" @click="changeType" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import AppealTypeSelect from 'src/Modules/Appeal/components/AppealTypeSelect/index.vue'
import { updateAppeal } from 'src/Modules/Appeal/api/appealApi'

export default defineComponent({
  components: {
    AppealTypeSelect
  },
  props: {
    appeal: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const appealType = ref('')
    const dialogVisible = ref(false)
    const showDialog = () => {
      console.log(props.appeal)
      appealType.value = props.appeal.type.id
      dialogVisible.value = true
    }
    const changeType = () => {
      const data = {
        field: 'appeal_type_id',
        value: appealType.value
      }
      updateAppeal(props.appeal.id, data)
        .then(() => {
          emit('success')
          dialogVisible.value = false
        })
    }
    return {
      appealType,
      changeType,
      dialogVisible,
      showDialog
    }
  }
})
</script>

<style scoped>

</style>
