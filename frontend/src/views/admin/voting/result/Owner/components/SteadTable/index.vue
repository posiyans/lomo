<template>
  <div>
    <el-table
      :key="tableKey"
      v-loading="loading"
      :data="steads"
      border
      :row-class-name="tableRowClassName"
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column fixed label="участок" align="center" width="80">
        <template slot-scope="{row}">
          <span :class="{red: !listo[row.id], green: listo[row.id]}">{{ row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column v-for="q in questions" :key="'q' + q.id" :label="q.text" class="do-not-carry" align="center">
        <el-table-column v-for="a in q.answers" :key="'a' + a.id" :label="a.text" align="center">
          <template slot-scope="{row}">
            <span v-if="listo[row.id] && listo[row.id][q.id] == a.id" :key="keyc" style="color: green; font-size: 2em;"><i class="el-icon-success" /></span>
          </template>
        </el-table-column>
      </el-table-column>
    </el-table>
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
import { fetchSteadList } from '@/api/stead.js'
import { fetchVotingResults } from '@/api/admin/voting'

import LoadMore from '@/components/LoadMore'

export default {
  components: {
    LoadMore
  },
  props: {
    questions: {
      type: Array,
      default: () => { [] }
    }
  },
  data() {
    return {
      keyc: 1,
      key: 1,
      func: fetchSteadList,
      list: [],
      listo: {},
      tableKey: 0,
      loading: true,
      listLoading: false,
      steads: [],
      voting: [],
      listQuery: {
        page: 1,
        limit: 1000
      }
    }
  },
  mounted() {
    // this.getSteads()
  },
  methods: {
    tableRowClassName({ row }) {
      if (!(row.id in this.listo)) {
        return 'warning-row'
      }
      return ''
    },
    getResult(val) {
      // this.loading = true
      const find = []
      val.forEach(stead => {
        if (!(stead.id in this.listo)) {
          // console.log('нет результата ' + stead.number)
          find.push(stead.id)
        }
      })
      console.log(find)
      // this.listo = Object.assign({}, this.listo)
      fetchVotingResults({ steads: find, voting_id: this.questions[0].voting_id })
        .then(response => {
          console.log(response.data)
          if (response.data.data) {
            response.data.data.forEach(item => {
              if (item.stead_id in this.listo) {
                this.listo[item.stead_id][item.question_id] = item.answer_id
              } else {
                this.listo[item.stead_id] = { [item.question_id]: item.answer_id }
              }
            })
          } else {
            this.$message.error('Ошибка получения данных')
          }
          this.listo = Object.assign({}, this.listo)
          // this.steads = val
          this.loading = false
          console.log(this.listo)
        })
    },

    setList(val) {
      this.steads = val
      this.getResult(val)
    }
  }

}
</script>
<style>
  .el-table .warning-row {
    background: #fff4f4;
  }
</style>
<style scoped>

  .red {
       color: red;
     }
  .green {
    color: green;
  }
</style>
