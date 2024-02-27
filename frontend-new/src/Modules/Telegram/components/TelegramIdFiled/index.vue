<template>
  <q-input
    :model-value="newValue"
    :label="label"
    :outlined="outlined"
    :readonly="readonly"
    :dense="dense"
    @update:model-value="setValue"
  >
    <template v-if="!readonly" v-slot:after>
      <div class="relative-position q-pt-sm">
        <q-btn-group outline>
          <q-btn v-if="noSave" flat padding="sm" icon="restore_page" :disable="loading" color="red" @click="resetData" />
          <q-btn v-if="noSave" flat padding="sm" icon="save" color="green" :disable="loading" @click="updateData" />
        </q-btn-group>
        <div class="absolute-bottom">
          <q-linear-progress v-if="loading" size="xs" indeterminate />
        </div>
      </div>
    </template>
    <template v-slot:append>
      <GetLastUserIdBtn @setId="setTelegramId" />
    </template>
  </q-input>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref, watch } from 'vue'
import GetLastUserIdBtn from 'src/Modules/Telegram/components/GetLastUserIdBtn'

export default defineComponent({
  components: {
    GetLastUserIdBtn
  },
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    func: {
      type: Function,
      required: true
    },
    userId: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: ''
    },
    readonly: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
    name: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    watch(
      () => props.modelValue,
      () => {
        init()
      }
    )
    const loading = ref(false)
    const newValue = ref('')
    const oldValue = ref('')
    const noSave = computed(() => {
      return oldValue.value !== newValue.value

    })
    const init = () => {
      oldValue.value = props.modelValue
      newValue.value = props.modelValue
    }
    const resetData = () => {
      newValue.value = oldValue.value
    }
    const updateData = () => {
      loading.value = true
      const data = Object.assign({}, props.fields)
      data.field = props.name
      data.value = newValue.value
      props.func(data)
        .then(response => {
          emit('update:model-value', newValue.value)
          emit('success', newValue.value)
        })
        .catch(error => {
          emit('errors', error.response)
        })
        .finally(() => {
          loading.value = false
        })
    }
    const setValue = (val) => {
      newValue.value = val
      emit('reset-data')
    }
    onMounted(() => {
      init()
    })
    const setTelegramId = (val) => {
      newValue.value = val
    }
    return {
      loading,
      noSave,
      resetData,
      updateData,
      setValue,
      newValue,
      setTelegramId
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
