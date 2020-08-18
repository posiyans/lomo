<template>
  <el-dialog
    title="Скачать квитанцию на оплату взносов"
    :visible.sync="show"
    :fullscreen="$store.state.app.device == 'mobile' ? true : false"
    width="500px"
    @close="close"
  >
    <div class="mb2" style="font-size: large; color:#000;">Тарифы на оплату.</div>
    <div v-for="i in rate"  :key="i.name" class="pb2">
      {{i.name}} - {{i.rate.discription}}
      <span v-if="stead.size > 0 && i.rate.ratio_a > 0">
        * <b>{{stead.size/100}}</b> = {{(stead.size/100 * i.rate.ratio_a).toFixed(2)}} руб.
      </span>
    </div>
    <div v-if="stead.size > 0" class="pt1" style="color: #1b5fab"><b>Итого: <span style="color: red">{{stead.size | totalFilter(rate)}} руб.</span></b></div>
    <div class="mt4 mb2" style="font-size: large" :class="{red: !stead.size > 0}">Укажите номер участка.</div>
    <UserSteadFind @selectStead="setStead"/>
    <div v-if="stead.size > 0" class="pt1" style="color: red">
      Внимание! Проверте данные.<br>
      Участок № <span style="color: #004fa5"><b>{{ stead.number }} </b></span> размер
      <span style="color: #004fa5"><b>{{ stead.size }}</b></span> кв.м.
    </div>
    <span slot="footer" class="dialog-footer">
    <el-button @click="show = false">Отмена</el-button>
    <el-button type="primary" :disabled="!stead.size > 0" @click="download">Скачать</el-button>
  </span>
  </el-dialog>
</template>

<script>
import UserSteadFind from '@/components/UserSteadFind'
import { fetchList } from '@/api/rate'
import { getReceiptForStead } from '@/api/all/receipt'
import { saveAs } from 'file-saver'

export default {
  props: {
    dialogVisible: {
      type: Boolean,
      default: true
    }
  },
  filters: {
    totalFilter(size, rate) {
      let sum = 0
      rate.forEach(i => {
        sum = Number(sum) + Number(i.rate.ratio_a) * Number(size) / 100 + Number(i.rate.ratio_b)
      })
      return sum.toFixed(2)
    },
  },
  components: {
    UserSteadFind
  },
  data() {
    return {
      getSteadsList: [],
      stead: '',
      show: false,
      rate: []
    }
  },
  mounted() {
    this.show = this.dialogVisible
    this.getRateList()
  },
  methods: {
    download() {
      const data = {
        stead: this.stead.id
      }
      getReceiptForStead(data).then(response => {
        saveAs(new Blob([response.data], {
          type: response.data.type
        }), 'Квитанция_' + this.stead.number + '.pdf')
        this.$message('Фаил успешно скачен.')
        this.show = false
      })
    },
    setStead(val) {
      this.stead = val
    },
    close() {
      this.$emit('close')
    },
    getRateList() {
      this.loading = true
      const data = {
        type: 2
      }
      fetchList(data).then(response => {
        this.rate = response.data.data
        this.loading = false
      })
    },
  }
}
</script>

<style scoped>

</style>
