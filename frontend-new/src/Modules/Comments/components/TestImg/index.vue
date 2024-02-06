<template>
  <div>
    <input type="file" accept="image/*" @change="handleChange" ref="fileinput">
    <img :src="sourceImgUrl" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const sourceImgUrl = ref(null)
    const fileinput = ref(null)
    const handleChange = (e) => {
      let files = e.target.files || e.dataTransfer.files
      console.log(files)
      e.preventDefault();
      setSourceImg(files[0]);

    }
    const setSourceImg = (file) => {
      const fr = new FileReader();
      fr.onload = function (e) {
        sourceImgUrl.value = fr.result;
        console.log(sourceImgUrl.value)
      }
      fr.readAsDataURL(file);
    }
    return {
      data,
      fileinput,
      sourceImgUrl,
      handleChange
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

