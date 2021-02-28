<template>
  <el-drawer
    :visible.sync="drawer"
    :size="mobile ? '100%' : '400px'"
    :with-header="false"
    @closed="closedDrawer"
  >
    <div class="drawer__content">
      <div class="drawer__title">
        Скачать квитанцию
      </div>
      <div class="pa3 pt4">
        <div v-if="dataForReceipt.length > 0" class="fw6 ml2 mb3 dark-blue">
          {{ dataForReceipt[0].name[0] }} участок {{ steadNumber }}
        </div>
        <div v-for="item in dataForReceipt" :key="item.id" class="ml2 mb2" :class="{'dark-red': (item.value- item.last) < 0 }">
          {{ item.name[1] }}({{ item.value }}): {{ item.value - item.last }} {{ item.unit_name }} * {{ item.price }} руб = {{ ((item.value- item.last)*item.price).toFixed(2) }} руб
        </div>
        <div class="ml3 mt3 f3" :class="{'dark-green': sum > 0, 'dark-red': sum <= 0 }">
          <strong>
            Итого: {{ sum | formatPrice }}руб
          </strong>
        </div>
        <div class="tr mt3 mb2" @click="showChangeSum = !showChangeSum">
          <el-button type="success" plain size="mini" class="tr mt3 mb2">Неверная сумма?</el-button>
        </div>
        <div v-if="showChangeSum">
          <div class="dark-green mb3">Сумма расчитывается на основании придыдущих показаний.</div>
          <div class="dark-blue mb3">Укажите предыдущие показания.</div>
          <div v-for="item in dataForReceipt" :key="item.id" class="ml2 mb2 f7">
            {{ item.name[1] }}:
            <el-input v-model="item.last" type="number" style="width: 120px;" />
            {{ item.unit_name }}
          </div>
        </div>
      </div>
      <div class="drawer__footer">
        <el-button @click="drawer = false">Отмена</el-button>
        <el-button type="primary" :disabled="disableButton" :loading="receipt_loading" @click="downloadReceipt">{{ receipt_loading ? 'Скачиваю ...' : 'Скачать' }}</el-button>
      </div>
    </div>
  </el-drawer>
</template>

<script>
import { getReceiptForParams } from '@/api/all/receipt'
import { saveAs } from 'file-saver'
export default {
  props: {
    steadNumber: {
      type: String,
      default: ''
    },
    dataForReceipt: {
      type: Array,
      default: () => ([])
    }
  },
  data() {
    return {
      drawer: true,
      receipt_loading: false,
      showChangeSum: false
    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    },
    disableButton() {
      if (this.sum <= 0) {
        return true
      }
      let status = false
      this.dataForReceipt.forEach(item => {
        if ((item.value - item.last) < 0) {
          status = true
        }
      })
      return status
    },
    sum() {
      let s = 0
      this.dataForReceipt.forEach(item => {
        s += (item.value - item.last) * item.price
      })
      return s.toFixed(2)
    }
  },
  methods: {
    downloadReceipt() {
      this.receipt_loading = true
      let desc = ''
      this.dataForReceipt.forEach(item => {
        const delta = item.value - item.last
        const s = (delta * item.price).toFixed(2)
        desc += item.name[1] + '(' + item.value + '-' + item.last + '):  ' + delta + ' ' + item.unit_name + ' * ' + item.price + 'руб = ' + s + 'руб@'
      })
      desc += 'Итого: ' + this.sum + ' руб.@'
      const data = {
        stead_num: this.steadNumber,
        purpose: this.dataForReceipt[0].name[0],
        sum: this.sum,
        description: desc
      }
      getReceiptForParams(data)
        .then(response => {
          saveAs(new Blob([response.data], {
            type: response.data.type
          }), 'Квитанция.pdf')
          this.$message.success('Файл успешно скачан.')
          this.receipt_loading = false
        })
    },
    closedDrawer() {
      this.$emit('close')
    }
  }
}
</script>

<style scoped>

  .drawer__content {
    background-color: #eee;
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .drawer__title {
    padding: 5px 20px;
    background-color: rgb(84, 92, 100);
    color: #ffffff;
    text-align: center;
    line-height: 32px;
    font-size: 16px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
  }
  .drawer__footer {
    margin: 50px 0;
    /*display: flex;*/
    text-align: center;
  }
  .drawer__footer .el-button + .el-button {
    margin-left: 20px;
  }
</style>
