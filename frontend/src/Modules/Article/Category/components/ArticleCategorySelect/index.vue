<template>
  <div>
    <div v-if="loading" class="items-center" style="width: 200px;">
      <el-skeleton style="width: 100%" variant="h3" animated :count="1" :rows="1" />
    </div>
    <div v-else>
      <el-select
        :value="value"
        :placeholder="placeholder"
        @input="setValue"
      >
        <el-option
          v-for="item in options"
          :key="item.id"
          :label="item.label"
          :value="item.id"
        />
      </el-select>
    </div>
  </div>
</template>

<script>
import { fetchCategoryList } from '@/Modules/Article/Category/api/category.js'

export default {
  props: {
    value: {
      type: Number,
      default: null
    },
    placeholder: {
      type: String,
      default: 'Категория'
    },
    /**
     * какие категории показзать
     */
    type: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      options: [],
      loading: true
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
    this.getCategory()
  },
  methods: {
    setValue(val) {
      this.$emit('input', val)
    },
    getCategory() {
      fetchCategoryList()
        .then(response => {
          this.options = response.data
          this.loading = false
        })
    }
  }
}
</script>
