<template>
  <tr>
    <td>
      {{ field.label }}
    </td>
    <td>
      <div class="row items-center q-col-gutter-sm justify-center">
        <div v-if="!showEdit" :class="{ 'cursor-pointer': kadastr}" @click="showEvent">
          {{ value }}
        </div>
        <InputAndSaveProxy
          v-if="showEdit"
          outlined
          dense
          :model-value="value"
          :name="field.name"
          :func="func"
          @success="reload"
          @errors="errors"
        />
        <div v-html="field.units"></div>
      </div>
    </td>
    <td v-if="edit">
      <q-btn v-if="write" :color="showEdit ? 'negative' : 'primary'" flat :icon="showEdit ? 'close' : 'edit'" @click="editField" />
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import { updateSteadField } from 'src/Modules/Stead/api/stead'
import InputAndSaveProxy from 'components/Input/InputAndSaveProxy/index.vue'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    InputAndSaveProxy
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    },
    field: {
      type: Object,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    },
  },
  setup(props, { emit }) {
    const showEdit = ref(false)
    const $q = useQuasar()
    const value = computed(() => {
      return props.modelValue[props.field.name] || ''
    })
    const write = computed(() => {
      return !props.field.readonly
    })
    const kadastr = computed(() => {
      return props.field.name === 'kadastr'
    })
    const editField = () => {
      showEdit.value = !showEdit.value
    }
    const func = (data) => {
      return updateSteadField(props.modelValue.id, data)
    }
    const reload = () => {
      showEdit.value = false
      emit('reload')
    }
    const errors = (er) => {
      errorMessage(er.data.errors)
    }
    const showEvent = () => {
      if (kadastr.value) {
        window.open('https://pkk.rosreestr.ru/#/?text=' + value.value)
      }
    }

    return {
      showEdit,
      kadastr,
      errors,
      func,
      reload,
      write,
      value,
      showEvent,
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
