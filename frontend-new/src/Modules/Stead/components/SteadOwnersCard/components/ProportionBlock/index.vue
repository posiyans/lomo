<template>
  <div>
    <div v-if="!edit" class="cursor-pointer" @click="editProportion(owner)">
      <q-chip
        square
        icon-right="edit"
      >
        {{ owner.proportion }}%
      </q-chip>
    </div>
    <div v-if="edit" class="br4">
      <div class="row items-center bg-grey-1 ">
        <div class="bg-grey-3">
          <InputNumber
            :model-value="newValue"
            dense
            style="max-width: 120px;"
            @update:model-value="setValue"
          />
        </div>
        <div class="bg-grey-3">
          <q-btn-group outline>
            <q-btn flat padding="sm" icon="save" color="green" :disable="loading || !noSave" @click="updateData" />
            <q-btn flat padding="sm" icon="delete" color="negative" :disable="loading" @click="updateData">
              <q-tooltip>
                Удалить участок у собственника
              </q-tooltip>
            </q-btn>
          </q-btn-group>
          <div class="absolute-bottom">
            <q-linear-progress v-if="loading" size="xs" indeterminate />
          </div>
        </div>
        <div style="padding-right: 25px;">
        </div>
        <div>
          <q-btn icon="close" flat color="negative" dense @click="edit = false" class="">
            <q-tooltip>
              Закрыть
            </q-tooltip>
          </q-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import InputAndSaveProxy from 'components/Input/InputAndSaveProxy/index.vue'
import { updateSteadOwnerProportion } from 'src/Modules/Stead/api/stead'
import InputNumber from 'components/Input/InputNumber/index.vue'

export default defineComponent({
  components: {
    InputAndSaveProxy,
    InputNumber
  },
  props: {
    owner: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const edit = ref(false)
    const loading = ref(false)
    const newValue = ref(null)
    const editProportion = (owner) => {
      console.log(owner)
      newValue.value = props.owner.proportion
      edit.value = !edit.value
    }
    const noSave = computed(() => {
      return props.owner.proportion !== newValue.value

    })
    const resetData = () => {
      newValue.value = props.owner.proportion
    }
    const updateData = () => {
      const data = {
        owner_uid: props.owner.uid,
        stead_id: props.owner.stead_id,
        proportion: newValue.value
      }
      updateSteadOwnerProportion(data)
        .then(res => {
          edit.value = false
          emit('success')
        })
    }
    const setValue = (val) => {
      if (val < 0) {
        val = 0
      } else if (val > 100) {
        val = 100
      }
      newValue.value = val
    }
    return {
      edit,
      setValue,
      resetData,
      newValue,
      updateData,
      loading,
      noSave,
      updateSteadOwnerProportion,
      editProportion
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
