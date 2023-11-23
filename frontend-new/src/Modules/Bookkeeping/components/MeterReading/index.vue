<template>
  <div>
    <el-dialog
      title="Показаняи счетчиков"
      v-model="dialogMeterReadingFormVisible"
      @close="close"
    >
      <div class="mb2">{{ description }}</div>
      <el-form label-position="top">
        <el-form-item label="Показания день">
          <el-input
            v-model="d1"
            placeholder="Показания день"
            prefix-icon="el-icon-search"
          />
        </el-form-item>
        <el-form-item label="Показания ночь">
          <el-input
            v-model="d2"
            placeholder="Показания ночь"
            prefix-icon="el-icon-search"
          />
        </el-form-item>
      </el-form>
      <span class="dialog-footer">
        <el-button type="danger" @click="close">Отмена</el-button>
        <el-button type="primary" @click="save">Ок</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: {
    description: {
      type: String,
      default: ''
    },
    meterReading1: {
      type: String,
      default: null
    },
    meterReading2: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      dialogMeterReadingFormVisible: true,
      d1: null,
      d2: null
    }
  },
  created() {
    this.d1 = this.meterReading1
    this.d2 = this.meterReading2
  },
  methods: {
    save() {
      this.dialogMeterReadingFormVisible = false
      this.$emit('save', [this.d1, this.d2])
    },
    close() {
      this.dialogMeterReadingFormVisible = false
      this.$emit('close')
    }
  }

}
</script>

<style scoped>

</style>
