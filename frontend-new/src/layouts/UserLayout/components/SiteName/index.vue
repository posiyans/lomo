<template>
  <div>
    {{ siteName }}
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  setup() {
    // todo заменить на динамику из админки и положить в кеш
    const siteName = process.env.SITE_NAME || 'СНТ'
    return {
      siteName
    }
  }
})
</script>

<style scoped>

</style>
