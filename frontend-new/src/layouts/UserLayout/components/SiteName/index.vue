<template>
  <div>
    {{ siteName }}
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  setup () {
    const siteName = process.env.SITE_NAME || 'СНТ'
    return {
      siteName
    }
  }
})
</script>

<style scoped>

</style>
