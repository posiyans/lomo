<template>
  <div :style="style">{{ title }}</div>
</template>

<script>
import { getStatusList } from '@/Modules/Article/Article/api/article'

export default {
  props: {
    value: {
      type: [Number, String],
      required: true
    },
    color: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      status: []
    }
  },
  computed: {
    item() {
      if (this.value) {
        return this.status.find(item => item.id === this.value)
      }
      return ''
    },
    title() {
      if (this.item) {
        return this.item.label
      }
      return ''
    },
    style() {
      if (this.item) {
        return 'color: ' + this.item.color + ';'
      }
      return ''
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData() {
      getStatusList()
        .then(res => {
          this.status = res.data
        })
    },
    setValue(val) {
      this.$emit('input', val)
    }
  }
}
</script>

<style scoped>

</style>
