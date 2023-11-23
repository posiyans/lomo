<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-xs q-mb-sm">
      <div>
        <q-input v-model="listQuery.find" label="Поиск" dense outlined clearable class="filter-item" @update:model-value="handleFilter" />
      </div>
      <ArticleCategorySelect
        v-model="listQuery.category"
        @input="handleFilter"
        clearable
        outlined
        dense
        @update:modelValue="handleFilter"
        class="filter-item"
      />
      <StatusSelect
        v-model="listQuery.status"
        label="Статус"
        clearable
        dense
        outlined
        class="filter-item"
        @update:modelValue="handleFilter"
      />
      <div>
        <q-btn color="primary" icon="search" @click="handleFilter">
          Показать
        </q-btn>
      </div>
    </div>

    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template #default="{ row }">
          <el-tag :type="statusFilter(row.public)">
            {{ row.id }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column v-if="!mobile" width="150px" align="center" label="Дата">
        <template #default="scope">
          <ShowPublicTime :time="scope.row.updated_at" />
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" class-name="status-col" label="Статус" width="120">
        <template #default="{row}">
          <StatusShow v-model="row.public" color />
        </template>
      </el-table-column>

      <el-table-column min-width="300px" label="Заголовок">
        <template #default="{row}">
          <router-link :to="'/admin/article/edit/'+row.id" class="link-type">
            <span>{{ row.title }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column label="Автор">
        <template #default="{row}">
          <span>{{ row.user?.last_name }} {{ row.user?.name }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" label="Раздел">
        <template #default="{row}">
          <CategoryShow v-model="row.category_id" />
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" align="center" class-name="status-col" label="Комментарии" width="150">
        <template #default="{row}">
          <CommentEnableStatus v-model="row.allow_comments" color />
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="120">
        <template #default="scope">
          <q-btn color="primary" size="sm" icon="edit" label="Edit" :to="'/admin/article/edit/'+scope.row.id" />
        </template>
      </el-table-column>
    </el-table>

    <LoadMore :key="key" v-model:list-query="listQuery" :func="fetchAdminArticleList" @setList="setList" />
  </div>
</template>

<script>
import { fetchAdminArticleList } from 'src/Modules/Article/Article/api/articleAdminApi.js'
import StatusShow from 'src/Modules/Article/Article/components/StatusShow'
import StatusSelect from 'src/Modules/Article/Article/components/StatusSelect'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect'
import CategoryShow from 'src/Modules/Article/Category/components/CategoryShow'
import ShowPublicTime from 'src/components/ShowTime/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import CommentEnableStatus from 'src/Modules/Comments/components/CommentEnableStatus/index.vue'

export default {
  name: 'AdminArticleList',
  components: {
    StatusShow,
    StatusSelect,
    ArticleCategorySelect,
    CategoryShow,
    ShowPublicTime,
    LoadMore,
    CommentEnableStatus
  },
  data() {
    return {
      list: null,
      total: 0,
      key: 1,
      fetchAdminArticleList,
      listLoading: false,
      listQuery: {
        page: 1,
        limit: 20,
        find: '',
        status: '',
        category: ''
      }
    }
  },
  computed: {
    mobile() {
      return false
    }
  },
  mounted() {
    // this.getList()
  },
  methods: {
    statusFilter(status) {
      return status ? 'success' : 'info'
    },
    setList(val) {
      this.list = val
    },
    getList() {
      this.listLoading = true
      fetchAdminArticleList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total
        this.listLoading = false
      })
    },
    handleFilter() {
      this.getList()
    }
  }
}
</script>

<style scoped>
.edit-input {
  padding-right: 100px;
}

.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
