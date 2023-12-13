<template>
  <div v-if="visibleForm" class="q-pa-md">
    <div class="q-gutter-md">
      <q-form
        @submit="onSubmit"
        greedy
        class="q-gutter-md"
      >
        <div
          class="row items-center rounded-borders ba q-px-md"
          :class="{'b--light-silver': steadList.length > 0, 'b--dark-red': steadList.length === 0}"
          style="min-height: 48px;"
        >
          <div class="">
            Участок(и):
          </div>

          <div v-for="stead in steadList" :key="stead">
            <q-chip outline color="primary" text-color="white" removable @remove="removeStead(stead)">
              <div class="q-mr-sm">
                уч.
              </div>
              <SteadInfo :stead-id="stead" />
            </q-chip>
          </div>
          <q-space />
          <SelectSteadDialog @add-stead="addStead" />
        </div>

        <div v-for="item in fieldList" :key="item.name">
          <ComponentField v-model="form[item.name]" :item="item" />
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
import { addOwnerUser, getOwnerFieldList } from 'src/Modules/Owner/api/ownerUserApi'
import { errorMessage } from 'src/utils/message'
import { useRouter } from 'vue-router'
import InputDate from 'src/components/Input/InputDate/index.vue'
import InputPhone from 'src/components/Input/InputPhone/index.vue'
import ComponentField from 'src/Modules/Owner/components/ComponentFiled/index.vue'
import SelectStead from 'src/Modules/Stead/components/SelectStead/index.vue'
import SelectSteadDialog from 'src/Modules/Stead/components/SelectSteadDialog/index.vue'
import SteadInfo from 'src/Modules/Stead/components/ShowSteadInfo/index.vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    ComponentField,
    InputDate,
    SelectStead,
    SelectSteadDialog,
    SteadInfo,
    InputPhone
  },
  props: {},
  setup(props, { emit }) {
    const steadAddDialogVisible = ref(false)
    const $q = useQuasar()
    const visibleForm = ref(false)
    const fieldList = ref([])
    const steadList = ref([])
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
    const removeStead = (val) => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Удалить участок?',
        cancel: true,
        persistent: true,
        ok: {
          label: 'Удалить',
          color: 'negative'
        }
      }).onOk(() => {
        steadList.value.splice(steadList.value.indexOf(val), 1)
      })
    }
    const addStead = (val) => {
      steadList.value.push(val)
    }
    const onSubmit = () => {
      if (!steadList.value.length === 0) {
        $q.dialog({
          title: 'Не указан участок!',
          message: 'Укажите № участка в собственности'
        })
      } else {
        addOwnerUser({ owner: form.value, steads: steadList.value })
          .then(response => {
            $router.push('/admin/owner/show-info/' + response.data.data.uid)
          })
          .catch(er => {
            // console.log(er.respone)
            errorMessage(er.response.data.errors)

          })
      }
    }
    onMounted(() => {
      getListField()
    })
    return {
      form,
      addStead,
      removeStead,
      steadAddDialogVisible,
      steadList,
      fieldList,
      visibleForm,
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
