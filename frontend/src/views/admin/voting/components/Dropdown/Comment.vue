<template>
  <el-select v-model="comment" placeholder="Комментарии">
    <el-option
      v-for="item in options"
      :key="item.id"
      :label="item.label"
      :value="item.id">
    </el-option>
  </el-select>
</template>

<script>
export default {
  props: {
    value: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      options: [
        {
          id: 0,
          label: 'Комментарии отключены'
        },
        {
          id: 1,
          label: 'Комментарии разрешены'
        },
        {
          id: 2,
          label: 'Комментарии разрешены собственникам'
        }
      ],
      // value: '',
    }
  },
  computed: {
    comment: {
      get() {
        return this.value
      },
      set(val) {
        this.$emit('input', val)
      }
    }
  }
}
</script>
