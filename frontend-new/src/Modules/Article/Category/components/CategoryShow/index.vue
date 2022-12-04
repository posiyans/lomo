<template>
  <div>
    {{ title }}
  </div>
</template>

<script>
import { fetchCategoryList } from 'src/Modules/Article/Category/api/category.js'

export default {
  props: {
    value: {
      type: Number,
      default: null
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
      options: []
    }
  },
  computed: {
    item () {
      return this.options.find(item => item.id === this.value)
    },
    title () {
      if (this.item) {
        return this.item.label
      }
      return ''
    },
    category: {
      get () {
        return this.value
      },
      set (val) {
        // this.value = val
        this.$emit('input', val)
      }
    }
  },
  mounted () {
    this.getCategory()
  },
  methods: {
    setValue (val) {
      this.$emit('input', val)
    },
    getCategory () {
      fetchCategoryList()
        .then(response => {
          this.options = response.data
        })
    }
  }
}
</script>
