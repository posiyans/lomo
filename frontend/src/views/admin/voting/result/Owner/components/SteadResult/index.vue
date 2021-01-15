<template>
  <div>
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="поиск" class="filter-item"  style="width: 150px;" @keyup.enter.native="findStead"></el-input>
      <el-button type="primary" class="filter-item" @click="findStead">Найти</el-button>
    </div>
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
          <span :class="{red: !listo[row.id], green: listo[row.id]}" @click="showB(row)">{{ row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column v-for="q in questions" :key="'q' + q.id" :label="q.text" class="do-not-carry" align="center">
        <el-table-column v-for="a in q.answers" :key="'a' + a.id" :label="a.text" align="center">
          <template slot-scope="{row}">
            <span v-if="listo[row.id] && listo[row.id][q.id] == a.id" :key="keyc" style="color: green; font-size: 2em;" @click="showB(row)"><i class="el-icon-success" /></span>
          </template>
        </el-table-column>
      </el-table-column>
    </el-table>
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
    <template v-if="preview">
      <image-viewer v-if="showViewer" :on-close="closeViewer" :url-list="previewSrcList" />
    </template>
  </div>
</template>

<script>
import { fetchSteadList } from '@/api/admin/stead.js'
import { fetchVotingResults, getBulletinList } from '@/api/admin/voting'
import ImageViewer from './image-viewer'

import LoadMore from '@/components/LoadMore'

export default {
  components: {
    LoadMore,
    ImageViewer
  },
  props: {
    questions: {
      type: Array,
      default: () => { [] }
    },
    id: {
      type: [Number, String],
      default: 0
    }
  },

  data() {
    return {
      prevOverflow: '',
      preview: true,
      showViewer: false,
      previewSrcList: [],
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
        limit: 20,
        title: ''
      }
    }
  },
  mounted() {
    // this.getSteads()
  },
  methods: {
    findStead() {
      this.key++
    },
    closeViewer() {
      document.body.style.overflow = this.prevOverflow
      this.showViewer = false
    },
    showB(row) {
      console.log(row)
      const data = {
        stead: row.id,
        voting: this.id
      }
      getBulletinList(data)
        .then(response => {
          if (response.data.status) {
            this.previewSrcList = response.data.data
            this.prevOverflow = document.body.style.overflow
            document.body.style.overflow = 'hidden'
            this.showViewer = true
          }
        })
    },
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
