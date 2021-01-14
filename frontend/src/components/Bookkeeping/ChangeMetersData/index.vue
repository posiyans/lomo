<template>
  <el-dialog
    title="Введите показания"
    :visible.sync="dialogVisible"
    width="400px"
    @close="close"
  >
    <div class="mb2 do-not-carry">{{ discription }}</div>
    <div style="width: 200px;">
      <el-form label-position="top">
        <el-form-item label="Показания день">
          <el-input
            v-model="val1"
            placeholder="Показания день"
            prefix-icon="el-icon-reading"
            clearable
          />
        </el-form-item>
        <el-form-item label="Показания ночь">
          <el-input
            v-model="val2"
            placeholder="Показания ночь"
            prefix-icon="el-icon-reading"
            clearable
          />
        </el-form-item>
      </el-form>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="dialogVisible = false">Отмена</el-button>
      <el-button type="primary" @click="save">Сохранить</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  props: {
    discription: {
      type: String,
      default: ''
    },
    data: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      val1: '',
      val2: '',
      dialogVisible: true
    }
  },
  mounted() {
    this.val1 = this.data.d1.value
    this.val2 = this.data.d2.value
  },
  methods: {
    close() {
      this.$emit('close')
    },
    save() {
      this.$emit('saveData', { d1: { device: 1, value: this.val1 }, d2: { device: 2, value: this.val2 }})
      this.dialogVisible = false
    }
  }
}
</script>

<style scoped>

</style>
