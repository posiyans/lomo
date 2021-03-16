<template>
  <div class="flex justify-between">
    <div v-if="field.type === 'String'">
      <el-input v-model="val" size="mini" :placeholder="field.label" />
    </div>
    <div v-if="field.type === 'Mask'">
      <el-input v-model="val" v-mask="field.mask" size="mini" :placeholder="field.label" />
    </div>
    <div v-if="field.type === 'Date'">
      <el-date-picker
        v-model="val"
        type="date"
        size="mini"
        format="dd-MM-yyyy"
        value-format="yyyy-MM-dd"
        :placeholder="field.label"
        style="width: 100%;"
      />
    </div>
    <div v-if="field.type === 'Boolean'">
      <el-select v-model="val" size="mini" :placeholder="field.label">
        <el-option label="ДА" :value="true" />
        <el-option label="НЕТ" :value="false" />
      </el-select>
    </div>
    <div class="button-group-together">
      <el-button type="primary" size="mini" plain icon="el-icon-check" @click="close" />
      <el-button type="danger" size="mini" plain icon="el-icon-close" @click="reset" />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      default: ''
    },
    field: {
      type: Object,
      default: () => ({})
    },
    type: {
      type: String,
      default: 'String'
    },
    mask: {
      type: [Boolean, String],
      default: false
    },
    label: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      val: ''
    }
  },
  created() {
    this.val = this.value
  },
  methods: {
    reset() {
      this.val = this.value
      this.close()
    },
    close() {
      this.$emit('close', { field: this.field.name, value: this.val })
    }
  }

}
</script>

<style scoped>
 .button-group-together >>> .el-button + .el-button {
   margin-left: 0;
 }
</style>
