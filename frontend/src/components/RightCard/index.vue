<template>
  <el-card class="right-cart">
    <div slot="header">
      <span>{{ header}} </span>
    </div>
    <div class="body">
    <slot>sdfsdf</slot>

    </div>
  </el-card>
</template>

<script>
export default {
  name: 'RightCard',
  components: {
  },
  props: {
    header: {
      type: String,
      default: ''
    }
  },
  data() {
    return {}
  },
  mounted() {
  },
  computed: {
  },
  methods: {

  }
}
</script>

<style scoped>

  .right-cart >>> .el-card__header {
    padding: 5px 20px;
    background-color: rgb(84, 92, 100);
    color: #ffffff;
  }
  .right-cart {
    margin-top: 5px;
  }
  .body {
    word-wrap: break-word;
  }
</style>
