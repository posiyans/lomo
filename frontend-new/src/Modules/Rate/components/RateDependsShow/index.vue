<template>
  <div>
    {{ depends }}
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getRateDependsList } from 'src/Modules/Rate/api/rateAdminApi'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const depends = ref([])
    onMounted(() => {
      getRateDependsList()
        .then(res => {
          depends.value = re.data.data
        })
    })
    return {
      data,
      depends
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
