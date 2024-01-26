<template>
  <tr v-if="rosreestr" class="text-small-85">
    <td>
      Данные росреестра
    </td>
    <td colspan="2" class="text-grey-8">
      <div>
        {{ rosreestr.feature.attrs.address }}
      </div>
      <div>
        {{ rosreestr.feature.attrs.area_value }} m <sup>2</sup>
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getKadastrInfo } from 'src/Modules/Stead/api/stead'

export default defineComponent({
  components: {},
  props: {
    kadastr: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const rosreestr = ref(false)
    const getPkkData = () => {
      const data = {
        kadastr: props.kadastr
      }
      getKadastrInfo(data)
        .then(res => {
          rosreestr.value = res.data
        })
    }
    onMounted(() => {
      getPkkData()
    })
    return {
      rosreestr
    }
  }
})
</script>

<style scoped lang='scss'>
td, th {
  border: 1px solid #606266;
  padding: 5px 10px;
  text-align: center;
}
</style>

