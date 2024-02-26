<template>
  <div class="row items-center">
    <ComponentField v-if="edit" style="max-width: 45vw;" v-model="newValue" :item="field" dense outlined class="text-grey-7" />
    <div v-if="!edit" class="">
      <div v-if="field.type === 'date'">
        <ShowTime :time="modelValue" format="DD-MM-YYYY" />
      </div>
      <div v-else>
        {{ modelValue }}
      </div>
    </div>
    <q-space />
    <div v-if="editable">
      <div v-if="edit">
        <q-btn icon="close" color="negative" size="sm" flat @click="edit = !edit" />
        <q-btn icon="done" flat color="secondary" size="sm" @click="saveData" />
      </div>
      <div v-else>
        <q-btn icon="edit_note" flat size="sm" @click="showEditForm" />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import OwnerUserFieldValueEdit from 'src/Modules/Owner/components/OwnerUserFieldValueEdit/index.vue'
import ComponentField from 'src/Modules/Owner/components/ComponentFiled/index.vue'
import { updateOwnerUserFiled } from 'src/Modules/Owner/api/ownerUserApi'
import { errorMessage } from 'src/utils/message'
import ShowTime from 'components/ShowTime/index.vue'

export default defineComponent({
  components: {
    OwnerUserFieldValueEdit,
    ComponentField,
    ShowTime
  },
  props: {
    modelValue: {
      default: ''
    },
    field: {
      type: Object,
      required: true
    },
    ownerUid: {
      type: String,
      default: ''
    },
    editable: {
      type: Boolean,
      default: false
    }
  },
  emits: ['success'],
  setup(props, { emit }) {
    const edit = ref(false)
    const saveData = () => {
      console.log('111')
      const data = {
        field: props.field.name,
        value: newValue.value
      }
      updateOwnerUserFiled(props.ownerUid, data)
        .then(() => {
          emit('success')
          edit.value = false
        })
        .catch(() => {
          errorMessage('Ошибка')
        })
    }
    const newValue = ref('')
    const showEditForm = () => {
      newValue.value = props.modelValue
      edit.value = true
    }
    return {
      edit,
      newValue,
      saveData,
      showEditForm
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
