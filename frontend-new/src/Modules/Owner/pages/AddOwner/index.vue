<template>
  <div v-if="visibleForm" class="q-pa-md">
    <div class="q-gutter-md">
      <q-form
        @submit="onSubmit"
        greedy
        class="q-gutter-md"
      >
        <div v-for="item in fieldList" :key="item.name">
          <ComponentField :model-value="form" :item="item" @update:model-value="setValue" />
        </div>
        <div>
          <q-btn color="primary" label="Добавить" type="submit" />
        </div>
      </q-form>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { addOwnerUser, getOwnerFieldList } from 'src/Modules/Owner/api/owner-admin-api'
import { errorMessage } from 'src/utils/message'
import { useRouter } from 'vue-router'
import InputDate from 'src/components/Input/InputDate/index.vue'
import InputPhone from 'src/components/Input/InputPhone/index.vue'
import ComponentField from './components/ComponentFiled/index.vue'

export default defineComponent({
  components: {
    ComponentField,
    InputDate,
    InputPhone
  },
  props: {},
  setup(props, { emit }) {
    const visibleForm = ref(false)
    const fieldList = ref([])
    const $router = useRouter()
    const form = ref({})
    const getListField = () => {
      getOwnerFieldList()
        .then(res => {
          fieldList.value = res.data.data
          fieldList.value.forEach(item => {
            form.value[item.name] = item.type === 'boolean' ? null : ''
          })
          visibleForm.value = true
        })
    }
    const setValue = (val) => {
      // const tmp = Object.assign({}, form.value)
      // tmp = val
      form.value = val
    }
    const onSubmit = () => {
      addOwnerUser({ user: form.value })
        .then(response => {
          // $router.push('/admin/owner/show-info/' + response.data.data)
        })
        .catch(er => {
          errorMessage(er.response.errors)
        })
    }
    onMounted(() => {
      getListField()
    })
    return {
      form,
      setValue,
      fieldList,
      visibleForm,
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
