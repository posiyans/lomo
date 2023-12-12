<template>
  <tr>
    <td>
      {{ label }}
    </td>
    <td>
      {{ value }}
    </td>
    <td>
      <q-btn v-if="write" color="primary" flat icon="edit" @click="editField" />
      <q-dialog v-model="showDialog">
        <q-card style="min-width: 360px;">
          <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Редактировать {{ label }}</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup />
          </q-card-section>

          <q-card-section>
            <q-input v-model="newValue" outlined />
          </q-card-section>
          <q-card-section>
            <div class="text-right">
              <q-btn color="primary" label="Сохранить" @click="saveData" />
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import { updateSteadField } from 'src/Modules/Stead/api/stead'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    label: {
      type: String,
      default: ''
    },
    field: {
      type: String,
      default: ''
    },
    readonly: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const showDialog = ref(false)
    const $q = useQuasar()
    const newValue = ref('')
    const value = computed(() => {
      return props.modelValue[props.field] || ''
    })
    const write = computed(() => {
      return !props.readonly
    })
    const editField = () => {
      newValue.value = value.value
      showDialog.value = true
    }
    const saveData = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Сохранить изменения?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        const data = {
          field: props.field,
          value: newValue.value
        }
        updateSteadField(props.modelValue.id, data)
          .then(res => {
            showDialog.value = false
            emit('reload')
          })

      })
    }
    return {
      showDialog,
      newValue,
      write,
      value,
      saveData,
      editField
    }
  }
})
</script>

<style scoped lang='scss'>
td, th {
  border: 1px solid #606266;
  padding: 5px 10px;
  text-align: center;
  color: #000000;
}
</style>
