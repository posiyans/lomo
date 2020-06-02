<template>
  <div>
    <el-table
      :key="tableKey"
      :data="userArray"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column label="Пользователь"  align="center">
        <template slot-scope="{row}">
          <span>{{ row.user_name }}</span>
        </template>
      </el-table-column>
      <el-table-column v-for="q in questions"  :label="q.text" align="center" :key="q.id">
        <el-table-column  v-for="a in q.answers" :label="a.text"  align="center" :key="a.id">
          <template slot-scope="{row}">
            <span v-if="a.userAnswers[row.id]" style="color: green; font-size: 2em;" ><i class="el-icon-success"></i></span>
          </template>
        </el-table-column>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
  export default {
    props:{
      questions: {
        type: Array,
        defaults: []
      }
    },
    comments: {
    },
    data() {
      return {
        list: [],
        listo: {},
        tableKey: 0,
        listLoading: false,
        steads: {},
        users: {},
        userArray: [],
        voting: []
      }
    },
    mounted() {
      this.parseUsers()
    },
    methods: {
      parseUsers() {
        this.questions.forEach(question => {
          question.answers.forEach(answer => {
            // answer.userAnswers.forEach(user => {
              for (const key in answer.userAnswers) {
              this.users[key] = answer.userAnswers[key]
            }
          })
        })
        const data = []
        for (const key in this.users) {
          data.push(this.users[key])
        }
        this.userArray = data
      },
    },
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
