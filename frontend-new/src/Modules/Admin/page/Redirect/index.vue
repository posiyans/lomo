<template>
  <div>
    Loading ...
  </div>
</template>

<script>
import { defineComponent, onBeforeMount } from 'vue'

export default defineComponent({
  setup () {
    onBeforeMount(() => {
      window.location = '/admin/my-dashboard'
    })
    return {
    }
  }
})
</script>

<style scoped>

</style>
