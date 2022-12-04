<template>
  <div>
    <div v-if="loading" class="items-center" style="width: 200px;">
      <el-skeleton style="width: 100%" variant="h3" animated :count="1" :rows="1"/>
    </div>
    <div v-else>
      <q-select
          :modelValue="modelValue"
          outlined
          :options="options"
          :label="label"
          map-options
          dense
          option-value="id"
          option-label="label"
          emit-value
          @update:modelValue="setValue"
      />
    </div>
  </div>
</template>

<script>
import { fetchCategoryList } from 'src/Modules/Article/Category/api/category.js'

export default {
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
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
  data () {
    return {
      options: [],
      loading: true
    }
  },
  mounted () {
    this.getCategory()
  },
  methods: {
    setValue (val) {
      this.$emit('update:modelValue', val)
    },
    getCategory () {
      fetchCategoryList()
        .then(response => {
          this.options = response.data
          this.loading = false
        })
    }
  }
}
</script>
