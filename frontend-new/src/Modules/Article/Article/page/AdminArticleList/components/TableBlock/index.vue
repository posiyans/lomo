<template>
  <div>
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
          <StatusShow v-model="row.status" color class="ellipsis" />
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
      <el-table-column v-if="!mobile" align="center" class-name="status-col" label="Комментарии" width="150" class="relative-position">
        <template #default="{row}">
          <div>
            <ShowCountComments type="article" :object-uid="row.uid" hide-zero class="absolute-top-right">
              <template #default="{ label }">
                <q-avatar color="grey-3" text-color="black" class="absolute-top-right" size="20px">
                  {{ label }}
                </q-avatar>
              </template>
            </ShowCountComments>
            <CommentEnableStatus v-model="row.allow_comments" color class="ellipsis" />
          </div>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="120">
        <template #default="scope">
          <q-btn color="primary" size="sm" icon="edit" label="Edit" :to="'/admin/article/edit/'+scope.row.id" />
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { fetchAdminArticleList } from 'src/Modules/Article/Article/api/articleAdminApi.js'
import StatusShow from 'src/Modules/Article/Article/components/StatusShow'
import CategoryShow from 'src/Modules/Article/Category/components/CategoryShow'
import ShowPublicTime from 'src/components/ShowTime/index.vue'
import CommentEnableStatus from 'src/Modules/Comments/components/CommentEnableStatus/index.vue'
import ShowCountComments from 'src/Modules/Comments/components/ShowCountComments/index.vue'

export default {
  components: {
    StatusShow,
    CategoryShow,
    ShowPublicTime,
    ShowCountComments,
    CommentEnableStatus
  },
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  data() {
    return {

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
  methods: {
    statusFilter(status) {
      return status ? 'success' : 'info'
    }
  }
}
</script>

<style scoped>

</style>
