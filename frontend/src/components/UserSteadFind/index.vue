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
        :value="item.id"
      />
    </el-select>
  </div>
</template>

<script>
import { getSteadsList } from '@/api/user/stead.js'
export default {
  props: {
    userStead: {
      type: [String, Number],
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
    selectStead() {
      const data = this.SteadsList.find(i => {
        if (i.id === this.stead) {
          return true
        }
      })
      this.$emit('selectStead', data)
    }
  }
}
</script>

<style scoped>

</style>
