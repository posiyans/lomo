<template>
  <div>
    <el-table
      v-loading="loading"
      :data="list"
      border
      fit
      size="mini"
      highlight-current-row
      :style="fullTable ? 'width: 100%;' : 'width: 500px;'"
    >
      <el-table-column v-if="!mobile" label="№" align="center" width="40">
        <template #default="scope">
          <span>{{ scope.$index + offset + 1 }}</span>
        </template>
      </el-table-column>
      <el-table-column label="ФИО" min-width="100px">
        <template #default="{row}">
          <span>
            {{ row.fullName }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Участок" align="center" width="100px">
        <template #default="{row}">
          <div class="stead-group">
            <div v-for="stead in row.steads" :Key="stead.id" class="stead-group__button" @click="pushToStead(stead.stead_id)">
              {{ stead.number }} {{ propFilter(stead.proportion) }}
            </div>
          </div>
          <span>
            {{ row.s }}
          </span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile && fullTable" label="Телефон" align="center" width="150px">
        <template #default="{row}">
          <span lass="link-type">
            {{ row.general_phone }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile && fullTable" label="Email" align="center" width="250px">
        <template #default="{row}">
          <span>
            {{ row.email }}
          </span>
        </template>
      </el-table-column>
      <el-table-column v-if="fullTable" label="Подробнее" align="center" class-name="">
        <template #default="{ row }">
          <div class="owner-info-icon" @click="showInfo(row)">
            <q-icon name="info" />
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>

export default {
  name: 'OwnerUserList',
  props: {
    offset: {
      type: Number,
      default: 0
    },
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
      return this.$q.platform.is.mobile
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
    },
    fullTable() {
      // return this.$store.state.user.roles.indexOf('access-to-personal') !== -1
      return true
    }
  },
  mounted() {
    // this.getList()
    // this.fetchSteads()
  },
  methods: {
    propFilter(val) {
      if (val < 100) {
        return '(' + val + '%)'
      }
      return ''
    },
    pushToStead(id) {
      this.$router.push('/admin/bookkeping/billing_balance_stead/' + id)
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
