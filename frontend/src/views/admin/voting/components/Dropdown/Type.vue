<template>
  <el-select v-model="category" placeholder="Тип голосования">
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
      type: String,
      default: 'public'
    }
  },
  data() {
    return {
      options: [
        {
          id: 'owner',
          label: 'Голосование собственников'
        },
        {
          id: 'public',
          label: 'Публичное голосование'
        }
      ]
      // value: '',
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
    // this.getCategory()
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
