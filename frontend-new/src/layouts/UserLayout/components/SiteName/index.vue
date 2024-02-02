<template>
  <div>
    {{ siteName }}
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import { getGardeningInfo } from 'src/Modules/Gardening/api/gardening'
import { LocalStorage } from 'quasar'

export default defineComponent({
  setup() {
    const siteName = ref(LocalStorage.getItem('SiteName') || 'СНТ')
    onMounted(() => {
      getGardeningInfo()
        .then(res => {
          siteName.value = res.data.name || 'СНТ'
          LocalStorage.set('SiteName', siteName.value)
        })
    })
    return {
      siteName
    }
  }
})
</script>

<style scoped>

</style>
