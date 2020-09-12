<template>
  <el-dropdown :show-timeout="100" trigger="click">
    <el-button plain>
      {{ comemnt_title }}
      <i class="el-icon-caret-bottom el-icon--right" />
    </el-button>
    <el-dropdown-menu slot="dropdown" class="no-padding">
      <el-dropdown-item>
        <el-radio-group v-model="comment_disabled" style="padding: 10px;">
          <el-radio :label="0">
            Комментарии отключены
          </el-radio>
          <el-radio :label="1">
            Комментарии разрешены
          </el-radio>
        </el-radio-group>
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    value: {
      type: Number,
      default: 0
    }
  },
  computed: {
    comment_disabled: {
      get() {
        return this.value
      },
      set(val) {
        this.$emit('input', val)
      }
    },
    comemnt_title(){
      const r = {
        0:'Комментарии отключены',
        1:'Комментарии разрешены'
      }
      return r[this.comment_disabled]
    }
  }
}
</script>
