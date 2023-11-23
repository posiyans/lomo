<template>
  <div>
    <div class="row items-center q-col-gutter-md q-pb-sm">
      <FiltersForm v-if="allShow" v-model="listQuery" />
      <div v-if="addShow">
        <AddBanUserBtn :user-uid="userUid" @success="getData" />
      </div>
    </div>
    <q-table
      bordered
      :rows="list"
      :columns="columnsFiltered"
      row-key="name"
      wrap-cells
      separator="cell"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
    >
      <template v-slot:body-cell-id="props">
        <q-td :props="props" auto-width>
          <div :class="{'o-20': props.row.deleted_at }">
            {{ props.row.id }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-author="props">
        <q-td :props="props">
          <router-link :to="`/admin/user/show/${props.row.author.uid}`" :class="{'o-20': props.row.deleted_at }">
            {{ props.row.author.name }}
          </router-link>
        </q-td>
      </template>
      <template v-slot:body-cell-created_at="props">
        <q-td :props="props">
          <div :class="{'o-20': props.row.deleted_at }">
            <ShowTime :time="props.row.created_at" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-date="props">
        <q-td :props="props" :class="{'o-20': props.row.deleted_at }">
          <div class="row items-center justify-center q-col-gutter-md no-wrap">
            <div v-if="props.row.end_ban_time" class="text-no-wrap">
              до
              <ShowTime :time="props.row.end_ban_time" block="span" />
            </div>
            <div v-else>
              навсегда
            </div>
            <div v-if="props.row.description" class="cursor-pointer">
              <q-icon name="info" flat>
                <q-tooltip>
                  {{ props.row.description }}
                </q-tooltip>
              </q-icon>
            </div>
          </div>
          <div>
            {{ props.row.type.label }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" auto-width>
          <div v-if="props.row.deleted_at">
            <div class="text-red text-weight-bold">
              Удалено
            </div>
            <div class="text-small-80">
              <ShowTime :time="props.row.deleted_at" />
            </div>
          </div>
          <div v-else class="row items-center no-wrap">
            <div>
              <DeleteBanUserBtn :ban="props.row" @success="getData" />
            </div>
            <div>
              <ChangeEndBanTimeBtn :ban="props.row" @success="getData" />
            </div>
          </div>
        </q-td>
      </template>
    </q-table>
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { getBanForUser } from 'src/Modules/BanUsers/api/apiBanUser'
import LoadMore from 'src/components/LoadMore/index.vue'
import FiltersForm from './components/FiltersForm/index.vue'
import ChangeEndBanTimeBtn from 'src/Modules/BanUsers/components/ChangeEndBanTime/ChangeEndBanTimeBtn.vue'
import DeleteBanUserBtn from 'src/Modules/BanUsers/components/DeleteBanUserBtn/index.vue'
import AddBanUserBtn from 'src/Modules/BanUsers/components/AddBanUserBtn/index.vue'
import ShowTime from 'components/ShowTime/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    LoadMore,
    FiltersForm,
    ChangeEndBanTimeBtn,
    DeleteBanUserBtn,
    AddBanUserBtn,
    ShowTime
  },
  props: {
    addShow: {
      type: Boolean,
      default: false,
    },
    allShow: {
      type: Boolean,
      default: false,
    },
    userUid: {
      type: String,
      required: true
    }
  },
  setup(props) {
    const ban = ref(null)
    const key = ref(null)
    const func = getBanForUser
    const list = ref([])
    const listQuery = ref(
      {
        user_uid: props.userUid,
        with_trashed: 0,
        page: 1,
        limit: 20
      }
    )
    const authStore = useAuthStore()
    const myRole = computed(() => {
      return authStore.permissions.filter(item => {
        return item === 'user-ban-edit' || item === 'user-ban-show'
      })
    })
    const columnsFiltered = computed(() => {
      return columns.value.filter(item => {
        if (!item.roles) {
          return true
        } else {
          myRole.value.forEach(r => {
            return item.roles.includes(r)
          })
        }
      })
    })
    const columns = ref(
      [
        {
          name: 'id',
          label: 'id',
          align: 'center',
        },
        {
          name: 'date',
          label: 'Дата истечения',
          align: 'center',
        },
        {
          name: 'author',
          label: 'Автор бана',
          align: 'center',
          roles: ['user-ban-show', 'user-ban-edit']
        },
        {
          name: 'created_at',
          label: 'от',
          align: 'center',
        },
        {
          name: 'actions',
          label: 'Действия',
          align: 'center',
          roles: ['user-ban-edit']
        }
      ]
    )
    const getData = () => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
    }

    return {
      myRole,
      columnsFiltered,
      authStore,
      ban,
      getData,
      key,
      func,
      list,
      columns,
      listQuery,
      setList
    }
  }
})
</script>

<style scoped>

</style>
