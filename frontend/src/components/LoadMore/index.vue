<template>
  <div v-if="loadMore" style="width: 100%;">
    <el-button type="warning" plain @click="add" style="width: 100%;">
    {{ loadMore }}
    </el-button>
  </div>
</template>

<script>
export default {
  props:{
    length:{
      type: Number,
      default: 0
    },
    total: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
    }
  },
  computed: {
    loadMore() {

      if (this.total > this.length){
        return 'Загрузить еще'
      }
      return false
    }
  },
  methods:{
    add(){
      this.$emit('download')
    },
  }
}
</script>
