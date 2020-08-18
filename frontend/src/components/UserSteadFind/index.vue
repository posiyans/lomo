<template>
  <div>
    <el-select
      v-model="stead"
      filterable
      remote
      clearable
      reserve-keyword
      placeholder="Введите номер участка"
      no-data-text="Данный номер не найден"
      :remote-method="findStead"
      :loading="loading"
      @change="selectStead"
    >
      <el-option
        v-for="item in SteadsList"
        :key="item.id"
        :label="item.number"
        :value="item">
      </el-option>
    </el-select>
  </div>
</template>

<script>
import { getSteadsList } from '@/api/user/stead.js'
export default {
  props: {
    userStead: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      loading: false,
      centerDialogVisible: false,
      gardening: {},
      dialogVisible: false,
      SteadsList: [],
      stead: ''
    }
  },
  mounted() {
    this.stead = this.userStead
    this.findStead()
  },
  methods: {
    findStead(val) {
      this.loading = true
      const data = {
        query: val
      }
      getSteadsList(data)
        .then(response => {
          this.SteadsList = response.data
          this.loading = false
        })
    },
    selectStead(){
      this.$emit('selectStead', this.stead)
    }
  }
}
</script>

<style scoped>

</style>
