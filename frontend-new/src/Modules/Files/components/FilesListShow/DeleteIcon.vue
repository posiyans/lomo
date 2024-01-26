<template>
  <q-icon
    :name="focusing ? 'delete' : 'highlight_off'"
    @mouseover="focusing = true"
    @mouseleave="focusing = false"
  />
</template>

<script>
export default {
  data() {
    return {
      focusing: false
    }
  }

}
</script>

<style scoped>

</style>
