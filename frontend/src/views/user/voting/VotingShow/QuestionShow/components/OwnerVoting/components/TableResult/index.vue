<template>
  <div>
    <div>
      <div class="text-red cell"> 0 </div> - номер участка проголосовавшего
    </div>
    <div>
      <div class="text-green cell"> 0 </div> - номер участка не проголосовавшего
    </div>
    <table>
      <tr v-for="i in line" :key="'c' + i">
        <td v-for="j in column" :key="'l' + i + '-'+ j">
          <span v-if="i + '-' + j in rezult" :class="{ 'text-red': !rezult[i + '-' + j].answer, 'text-green': rezult[i + '-' + j].answer }">
            {{ rezult[i + '-' + j].number }}
          </span>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { getSteadsList } from '@/api/user/stead'
export default {
  props: {
    steads: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      list: [],
      rezult: {}
      // column: 15
    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    },
    column() {
      if (this.mobile) {
        return 6
      }
      return 15
    },
    line() {
      return Math.ceil(this.list.length / this.column)
    }
  },
  mounted() {
    this.getSteads()
  },
  methods: {
    syncList() {
      let i = 1
      let j = 1
      this.list.forEach(item => {
        if (item.id in this.steads) {
          this.rezult[i + '-' + j] = { number: item.number, answer: true }
        } else {
          this.rezult[i + '-' + j] = { number: item.number, answer: false }
        }
        j += 1
        if (j > this.column) {
          j = 1
          i += 1
        }
      })
    },
    getSteads() {
      const data = {
        limit: 1000,
        page: 1
      }
      getSteadsList(data)
        .then(response => {
          // if (response.data.data) {
          //   this.$message('Данные успешно сохранены')
          this.list = response.data
          // } else {
          //   this.$message.error('Ошибка получения данных')
          // this.$message.error(response.data.data)
          // }
          this.syncList()
        })
    }
  }

}
</script>

<style scoped>
  table {
    border-collapse: collapse;
    width: 100%;
  }
  table tr td {
    border: 1px solid #949494;
    text-align: center;
  }
  .cell {
    border: 1px solid #949494;
    text-align: center;
    width: 50px;
    display: inline-block;
    margin-bottom: 5px;
  }
</style>
