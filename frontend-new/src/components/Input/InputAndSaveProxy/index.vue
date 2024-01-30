<template>
  <div class="row items-center">
    <div class="col-grow">
      <slot :model-value="newValue" :setValue="setValue">
        <q-input
          :model-value="newValue"
          :label="label"
          :readonly="readonly"
          :rules="rules"
          :dense="dense"
          :outlined="outlined"
          :flat="flat"
          :autogrow="autogrow"
          @update:model-value="setValue"
        >
          <template v-slot:append>
            <slot name="append"></slot>
          </template>
        </q-input>
      </slot>
    </div>
    <div class="relative-position q-pt-sm">
      <q-btn-group outline>
        <q-btn v-if="noSave" flat padding="sm" icon="restore_page" :disable="loading" color="red" @click="resetData" />
        <q-btn v-if="noSave" flat padding="sm" icon="save" color="green" :disable="loading" @click="updateData" />
      </q-btn-group>
      <div class="absolute-bottom">
        <q-linear-progress v-if="loading" size="xs" indeterminate />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref, watch } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    func: {
      type: Function,
      required: true
    },
    name: {
      type: String,
      default: ''
    },
    fields: {
      type: Object,
      default: () => {
      }
    },
    label: {
      type: String,
      default: undefined
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
    flat: {
      type: Boolean,
      default: false
    },
    autogrow: {
      type: Boolean,
      default: false
    },
    rules: {
      type: [Array, Function],
      default: () => []
    },
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
    return {
      loading,
      noSave,
      resetData,
      updateData,
      setValue,
      newValue
    }
  }
})
</script>

<style scoped>

</style>
