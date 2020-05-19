<template>
  <div v-if="listLoading">
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
      <el-table-column v-for="q in questions"  :label="q.text" align="center" :key="q.id">
        <el-table-column  v-for="a in q.answers" :label="a.text"  align="center" :key="a.id">
          <template slot-scope="{row}">
            <span v-if="listo[row.id] && listo[row.id][q.id] ==  a.id" style="color: green; font-size: 2em;" ><i class="el-icon-success"></i></span>
          </template>
        </el-table-column>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
  import { fetchList } from '@/api/stead.js'
  export default {
    props:{
      questions: {
        type: Array,
        defaults: []
      }
    },
    data() {
      return {
        list: [],
        listo: {},
        tableKey: 0,
        listLoading: false,
        steads: {},
        voting: []
      }
    },
    mounted() {
      this.getSteads()
    },
    methods: {
      getSteads(){
        const data ={
          limit: 1000
        }
        fetchList(data).then(response => {
          response.data.data.forEach(item => {
            this.steads[item.id] = item.number
          })
          this.steads = response.data.data
          this.listLoading = true
        })


      },
      getList(){
        const temp = {}
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
    watch:{
      questions(value) {
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
