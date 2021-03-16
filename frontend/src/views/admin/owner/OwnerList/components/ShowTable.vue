<template>
  <div>
    <el-table
      v-loading="loading"
      :data="list"
      border
      fit
      size="mini"
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column v-if="!mobile" label="№" align="center" width="40">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>
      <el-table-column label="ФИО" min-width="100px">
        <template slot-scope="{row}">
          <span>
            {{ row.fullName }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Участок" align="center" width="100px">
        <template slot-scope="{row}">
          <div class="stead-group">
            <div v-for="stead in row.steads" :Key="stead.id" class="stead-group__button" @click="pushToStead(stead.id)">
              {{ stead.number }}  {{ stead.proportion | propFilter }}
            </div>
          </div>
          <span>
            {{ row.s }}
          </span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" label="Телефон" align="center" width="150px">
        <template slot-scope="{row}">
          <span lass="link-type">
            {{ row.general_phone }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" label="Email" align="center" width="250px">
        <template slot-scope="{row}">
          <span>
            {{ row.email }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Подробнее" align="center" class-name="">
        <template slot-scope="{ row }">
          <div class="owner-info-icon" @click="showInfo(row)">
            <i class="el-icon-info" />
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import waves from '@/directive/waves' // waves directive

export default {
  name: 'OwnerUserList',
  directives: { waves },
  filters: {
    propFilter(val) {
      if (val < 100) {
        return '(' + val + '%)'
      }
      return ''
    },
    ownerFilter(val) {
      if (val && val.length > 0) {
        return 'Собственник'
      }
      return 'Пользователь'
    }
  },
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    list: {
      type: Array,
      default: () => ([])
    }
  },
  data() {
    return {
      // steadsList: [],
      tableKey: 0,
      // list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        title: undefined,
        type: undefined
      },
      temp: {},
      dialogFormVisible: false
    }
  },
  computed: {
    mobile() {
      if (this.$store.state.app.device === 'mobile') {
        return true
      }
      return false
    },
    editFio() {
      let fio = ''
      if (this.temp.last_name) {
        fio = this.temp.last_name + ' '
      }
      if (this.temp.name) {
        fio += this.temp.name + ' '
      }
      if (this.temp.middle_name) {
        fio += this.temp.middle_name
      }
      return fio
    }
  },
  mounted() {
    // this.getList()
    // this.fetchSteads()
  },
  methods: {
    pushToStead(id) {
      this.$route.push('/admin/bookkeping/billing_balance_stead/' + id)
    },
    showInfo(row) {
      console.log('/admin/owner/show-info/' + row.id)
      this.$router.push('/admin/owner/show-info/' + row.id)
    },
    close() {
      this.dialogFormVisible = false
      this.getList()
    }
  }
}
</script>

<style scoped>
 .owner-info-icon {
   font-size: 1.75em;
   color: #1890ff;
   cursor: pointer;
 }
 .owner-info-icon:hover {
   font-size: 2em;
   color: #0051a9;
 }
 .stead-group {
   display: flex;
   flex-wrap: wrap;
   justify-content: center;
 }
 .stead-group__button {
   cursor: pointer;
   border: 1px solid #357edd;
   border-radius: 0.25rem;
   background-color: #cdecff;
   margin-bottom: 0.25rem;
   min-width: 65px;

 }
 .stead-group__button:hover {
   background-color: #97d2ff;
   font-weight: 600;

 }
 .stead-group__button:last-child {
   margin-bottom: 0;
 }
</style>
