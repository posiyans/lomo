<template>
  <div>
    <el-select
      v-model="stead"
      filterable
      remote
      clearable
      :disabled="disabled"
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
    },
    steadId: {
      type: [Boolean, Number, String],
      default: false
    },
    readOnly: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      disabled: false,
      loading: false,
      centerDialogVisible: false,
      gardening: {},
      dialogVisible: false,
      SteadsList: [],
      stead: ''
    }
  },
  mounted() {
    if (this.steadId) {
      const data = {
        stead_id: this.steadId
      }
      getSteadsList(data)
        .then(response => {
          if (response.data.length === 1) {
            this.stead = response.data[0].number
            this.$emit('selectStead', response.data[0])
            if (this.read_only) {
              this.disabled = true
            }
          }
        })
    } else {
      this.findStead()
    }
  },
  methods: {
    find(val = '') {
      const data = {
        query: val
      }
      getSteadsList(data)
        .then(response => {
          if (response.data.length === 1) {
            this.stead = val
            this.$emit('selectStead', response.data[0])
          }
        })
    },
    findStead(val = '') {
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
      console.log('change')
      if (this.stead) {
        const data = this.SteadsList.find(i => {
          if (i.id === this.stead) {
            return true
          }
        })
        this.$emit('selectStead', data)
      } else {
        this.findStead()
        this.$emit('selectStead', '')
      }
    }
  }
}
</script>

<style scoped>

</style>
