<template>
  <div
    class="text-red pl4"
    @mouseover="focusing = true"
    @mouseleave="focusing = false"
  >
    <i :class="focusing ? 'el-icon-delete-solid' : 'el-icon-circle-close'" />
  </div>
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
