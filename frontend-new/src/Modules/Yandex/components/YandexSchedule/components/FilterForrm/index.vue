<template>
  <div class="row items-center q-col-gutter-sm">
    <div>
      <q-btn-group
        push
      >
        <q-btn
          v-for="item in directions"
          :key="item.key"
          push
          :color="item.key === modelValue.back ? 'primary' : ''"
          :text-color="item.key === modelValue.back ? '' : 'black'"
          :label="item.label"
          :icon="item.icon"
          :icon-right="item.iconRight"
          @click="setDirection(item.key)"
        />
      </q-btn-group>
    </div>
    <div>
      <q-btn-group push>
        <q-btn
          v-for="item in dates"
          :key="item.key"
          push
          :color="item.key === modelValue.type ? 'primary' : ''"
          :text-color="item.key === modelValue.type ? '' : 'black'"
          :label="item.label"
          :icon="item.icon"
          :icon-right="item.iconRight"
          @click="setType(item.key)"
        />
      </q-btn-group>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const directions = [
      {
        key: true,
        label: 'Обратно',
        icon: 'arrow_back'
      },
      {
        key: false,
        label: 'Туда',
        iconRight: 'arrow_forward'
      }
    ]
    const dates = [
      {
        key: '0',
        label: 'Сегодня'
      },
      {
        key: '1',
        label: 'Завтра'
      },
      {
        key: '2',
        label: 'На все дни'
      }
    ]
    const setDirection = (val) => {
      console.log(val)
      const tmp = Object.assign({}, props.modelValue)
      tmp.back = val
      emit('update:model-value', tmp)
    }
    const setType = (val) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp.type = val
      emit('update:model-value', tmp)
    }
    return {
      data,
      directions,
      dates,
      setDirection,
      setType
    }
  }
})
</script>

<style scoped>

</style>
