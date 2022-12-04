<template>
  <div>
    <el-select
      :value="value"
      :placeholder="placeholder"
      :clearable="clearable"
      @input="setValue"
    >
      <el-option
        v-for="item in status"
        :key="item.id"
        :label="item.label"
        :value="item.id"
      />
    </el-select>
  </div>
</template>

<script>
import { getStatusList } from 'src/Modules/Article/Article/api/article'

export default {
  props: {
    value: {
      type: [Number, String],
      required: true
    },
    placeholder: {
      type: String,
      default: undefined
    },
    clearable: {
      type: Boolean,
      default: false
    }

  },
  data () {
    return {
      status: []
    }
  },
  created () {
    this.getData()
  },
  methods: {
    getData () {
      getStatusList()
        .then(res => {
          this.status = res.data
        })
    },
    setValue (val) {
      this.$emit('input', val)
    }
  }
}
</script>

<style scoped>

</style>
