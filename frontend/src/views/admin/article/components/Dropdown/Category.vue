<template>
  <el-select v-model="category" placeholder="Категория">
    <el-option
      v-for="item in options"
      :key="item.id"
      :label="item.label"
      :value="item.id"
    />
  </el-select>
</template>

<script>
import { fetchCategoryList } from '@/api/category'

export default {
  props: {
    value: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      options: []
    }
  },
  computed: {
    category: {
      get() {
        return this.value
      },
      set(val) {
        // this.value = val
        this.$emit('input', val)
      }
    }
  },
  mounted() {
    // this.value = this.val
    this.getCategory()
  },
  methods: {
    getCategory() {
      const listQuery = {
        children: false
      }
      fetchCategoryList(listQuery)
        .then(response => {
          this.options = response.data
          // console.log('response')
          // console.log(response)
        })
    }
  }
}
</script>
