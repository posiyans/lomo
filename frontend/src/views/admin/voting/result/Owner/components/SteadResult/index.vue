<template>
  <div>
    <el-table
      :key="tableKey"
      :data="steads"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column label="участок" align="center" width="80">
        <template slot-scope="{row}">
          <span :class="{red: !listo[row.id], green: listo[row.id]}">{{ row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column v-for="q in questions" :key="'q' + q.id" :label="q.text" align="center">
        <el-table-column v-for="a in q.answers" :key="'a' + a.id" :label="a.text" align="center">
          <template slot-scope="{row}">
            <span v-if="listo[row.id] && listo[row.id][q.id] == a.id" style="color: green; font-size: 2em;"><i class="el-icon-success" /></span>
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
      key: 1,
      func: fetchSteadList,
      list: [],
      listo: {},
      tableKey: 0,
      listLoading: false,
      steads: [],
      voting: [],
      listQuery: {
        page: 1,
        limit: 20
      }
    }
  },
  mounted() {
    // this.getSteads()
  },
  methods: {
    getResult(steads) {
      const find = []
      this.steads.forEach(stead => {
        if (!(stead.id in this.listo)) {
          // console.log('нет результата ' + stead.number)
          find.push(stead.id)
        }
      })
      console.log(find)
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
            // this.tableKey += 1
            // this.listo = response.data.data
          } else {
            this.$message.error('Ошибка получения данных')
          }
          console.log(this.listo)
        })
    },

    setList(val) {
      this.steads = val
      this.getResult()
    }
  }

}
</script>

<style scoped>
  .red {
       color: red;
     }
  .green {
    color: green;
  }
</style>
