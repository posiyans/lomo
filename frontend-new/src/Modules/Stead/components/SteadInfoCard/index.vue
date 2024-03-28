<template>
  <div>
    <div style="max-width: 95vw;">
      <table v-if="stead">
        <TrTableBlock
          v-for="item in columns"
          :key="item.field"
          v-model="stead"
          :field="item"
          :edit="edit"
          @reload="getData"
        />
        <RosreestrDataTr v-if="stead.kadastr" :kadastr="stead.kadastr" />
      </table>
    </div>
    <DropDownBlock
      show-label="Яндекс карты"
      hide-label="Яндекс карты"
    >
      <div style="height: 500px;">
        <YandexMap :stead-id="steadId" />
      </div>
    </DropDownBlock>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import TrTableBlock from './components/TrTableBlock/index.vue'
import YandexMap from 'src/Modules/Yandex/components/YandexMap/index.vue'
import RosreestrDataTr from './components/RosreestrDataTr/index.vue'
import DropDownBlock from 'components/DropDownBlock/index.vue'

export default defineComponent({
  components: {
    TrTableBlock,
    YandexMap,
    RosreestrDataTr,
    DropDownBlock
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const columns = [
      {
        label: 'Номер участка',
        name: 'number',
        type: 'number'
      },
      {
        label: 'Размер',
        name: 'size',
        type: 'number',
        units: 'm<sup>2</sup>'
      },
      {
        label: 'Кадастровый номер',
        name: 'kadastr',
        type: 'string'
      },
    ]
    const data = ref(false)
    const stead = ref(null)

    const getData = () => {
      const data = {
        id: props.steadId,
        full: 1
      }
      getSteadInfo(data)
        .then(res => {
          stead.value = res.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      getData,
      columns,
      stead
    }
  }
})
</script>

<style scoped lang='scss'>
table {
  border-collapse: collapse;
}
</style>
