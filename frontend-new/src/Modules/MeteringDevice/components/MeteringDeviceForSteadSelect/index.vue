<template>
  <q-select
    :model-value="modelValue"
    :options="options"
    :label="label"
    map-options
    :clearable="clearable"
    :dense="dense"
    :outlined="outlined"
    emit-value
    option-value="id"
    transition-show="jump-up"
    transition-hide="jump-up"
    @update:model-value="setValue"
    :rules="rules"
    @clear="setValue('')"
  >
    <template v-slot:selected>
      <div
        v-if="selectedItem"
      >
        <div>
          {{ selectedItem.rate.name }} {{ selectedItem.device_brand }}
        </div>
        <div class="text-small-65 text-grey">
          {{ selectedItem.serial_number }}
        </div>
      </div>
    </template>
    <template v-slot:option="scope">
      <q-item v-bind="scope.itemProps">
        <q-item-section>
          <q-item-label>{{ scope.opt.rate.name }}</q-item-label>
          <q-item-label caption>{{ scope.opt.device_brand }}</q-item-label>
          <q-item-label caption>{{ scope.opt.serial_number }}</q-item-label>
        </q-item-section>
      </q-item>
    </template>
  </q-select>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      required: true
    },
    steadId: {
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      default: 'Прибор'
    },
    clearable: {
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
    autoSelect: {
      type: Boolean,
      default: false
    },
    rules: {
      type: Array,
      default: () => []
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const options = ref([])
    const selectedItem = computed(() => {
      if (props.modelValue) {
        return options.value.find(item => item.id === props.modelValue)
      }
      return ''
    })
    const getData = () => {
      getMeteringDeviceForStead(props.steadId)
        .then(res => {
          options.value = res.data.data
        })
    }
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      selectedItem,
      options,
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
