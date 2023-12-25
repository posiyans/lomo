<template>
  <div class="q-pa-md">
    <FilterBlock v-model="listQuery" />
    <el-table
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column v-if="!mobile" label="№" align="center" width="80">
        <template #default="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="ФИО" min-width="100px">
        <template #default="{row}">
          <div class="row items-center q-col-gutter-sm">
            <div>
              <UserAvatarByUid :uid="row.uid" />
            </div>
            <div>
              <div class="row items-center q-col-gutter-xs">
                <div class="text-no-wrap ">
                  {{ fullNameFilter(row) }}
                </div>
                <div v-if="row.owner" class="text-teal cursor-pointer" @click="toOwner(row.owner.uid)">
                  <q-icon name="home" />
                </div>
              </div>

              <div v-if="row.last_connect" class="text-small-80">
                <ShowTime :time="row.last_connect" />
              </div>
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" label="Телефон" align="center" width="150px">
        <template #default="{row}">
          <span class="link-type">
            {{ row.options.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" label="Email" align="center" width="250px">
        <template #default="{row}">
          <span
            class="link-type"
            :class="{
              'text-gren' : row.email_verified_at,
              'text-grey' : !row.email_verified_at
            }"
            :title="row.email_verified_at ? '' : 'Не подтвержден'"
          >
            {{ row.email }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center">
        <template #default="{ row }">
          <q-btn icon="info" flat :to="`/admin/user/show/${row.uid}`" />
        </template>
      </el-table-column>
    </el-table>
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { fetchList } from 'src/Modules/Users/api/user-admin-api.js'
import ShowTime from 'src/components/ShowTime/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import { useQuasar } from 'quasar'
import FilterBlock from './components/FiltersBlock/index.vue'

export default defineComponent({
  name: 'AdminUserList',
  components: {
    LoadMore,
    FilterBlock,
    ShowTime,
    UserAvatarByUid
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const listQuery = ref({
      page: 1,
      limit: 20,
      find: ''
    })
    const $q = useQuasar()
    const mobile = computed(() => {
      return $q.platform.is.mobile
    })
    const fullNameFilter = computed(() => {
      return user => {
        let result = ''
        if (user.last_name) {
          result += user.last_name
        }
        if (user.name) {
          result += ' ' + user.name
        }
        if (user.middle_name) {
          result += ' ' + user.middle_name
        }
        return result
      }
    })
    const router = useRouter()
    const route = useRoute()
    const func = fetchList
    const setList = (val) => {
      list.value = val
    }
    const handleFilter = () => {
      key.value++
    }
    const toOwner = (uid) => {
      router.push('/admin/owner/show-info/' + uid)
    }
    onMounted(() => {

    })
    return {
      key,
      func,
      toOwner,
      list,
      mobile,
      setList,
      listQuery,
      fullNameFilter,
      handleFilter
    }
  }
})
</script>

<style scoped>

</style>
