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
      <el-table-column label="участок"  align="center" width="80">
        <template slot-scope="{row}">
          <span :class="{red: !listo[row.id], green: listo[row.id]}">{{ row.number }}</span>
        </template>
      </el-table-column>
<!--      <el-table-column label="Голосовал"  align="center" width="110">-->
<!--        <template slot-scope="{row}">-->
<!--          <span v-if="listo[row.id]">{{ listo[row.id].user_id }}</span>-->
<!--        </template>-->
<!--      </el-table-column>-->
      <el-table-column v-for="(q, qi) in questions"  :label="q.text" align="center">
        <el-table-column  v-for="(a, ai) in q.answers" :label="a.text"  align="center">
          <template slot-scope="{row}">
            <span v-if="listo[row.id] && listo[row.id][q.id] ==  a.id" style="color: green; font-size: 2em;" ><i class="el-icon-success"></i></span>
          </template>
        </el-table-column>
      </el-table-column>
    </el-table>
    {{questions}}
    {{listo}}
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
import { fetchSteadList } from '@/api/stead.js'
import LoadMore from '@/components/LoadMore'

export default {
  props: {
    questions: {
      type: Array,
      defaults: []
    }
  },
  components: {
    LoadMore
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
      },
    }
  },
  mounted() {
    // this.getSteads()
  },
  methods: {
    setList(val) {
      this.steads = val
      // this.listLoading = false
    },
    // getSteads() {
    //   console.log('get stead')
    //   const data = {
    //     limit: 1000
    //   }
    //   fetchSteadList(this.listQuery).then(response => {
    //     console.log('getstead')
    //     console.log(response)
    //     // response.data.data.forEach(item => {
    //     //   // this.steads[item.id] = item.number
    //     // })
    //     this.steads = response.data.data
    //     this.listLoading = true
    //   })
    //
    //
    // },
    getList() {
      const temp = {}
      console.log('getlist')
      console.log(this.questions)
      this.questions.forEach(item => {
        item.answers.forEach(answer => {
          answer.userAnswers.forEach(user => {
            if (user.stead_id in temp) {
              temp[user.stead_id][user.question_id] = user.answer_id
            } else {
              temp[user.stead_id] = {[user.question_id]: user.answer_id, user_id: user.user_id}
            }
            // const [user.stead_id] = { [user.question_id]: user.answer_id}}
            // this.list.push(  {[user.stead_id]: { [user.question_id]: user.answer_id}} )
            // this.list.push({[user.stead_id]: 'trete'})
          })
        })
      })
      this.listo = temp
      for(const key in temp) {
        this.list.push({id: key, quest: temp[key]})
      }
      // this.listLoading = true
      this.tableKey += 1
    }
  },
  watch: {
    questions(value) {
      console.log(value)
     this.getList()
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
