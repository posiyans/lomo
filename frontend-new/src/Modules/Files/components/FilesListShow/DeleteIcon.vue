<template>
  <div
      class="text-red"
      @mouseover="focusing = true"
      @mouseleave="focusing = false"
  >
    <q-icon :name="focusing ? 'delete' : 'highlight_off'" />
  </div>
</template>

<script>
export default {
  data () {
    return {
      focusing: false
    }
  }

}
</script>

<style scoped>

</style>
