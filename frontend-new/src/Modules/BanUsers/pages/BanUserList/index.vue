<template>
  <div class="q-pa-md">
    <FiltersForm v-model:list-query="listQuery" class="q-py-sm" />
    <transition-group
      appear
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
    >
      <q-table
        bordered
        :rows="list"
        :columns="columns"
        row-key="name"
        wrap-cells
        separator="cell"
        hide-bottom
        :pagination="{ rowsPerPage: 0 }"
        binary-state-sort
        :sort-method="customSort"
      >
        <template v-slot:body="props">
          <transition
            appear
            enter-active-class="animated fadeIn"
            leave-active-class="animated fadeOut"
          >
            <q-tr :props="props" :key="props.row.id">
              <q-td auto-width>
                <div>
                  {{ props.row.id }}
                </div>
              </q-td>
              <q-td auto-width>
                <div class="ellipsis">
                  <ShowTime :time="props.row.created_at" />
                </div>
              </q-td>
              <q-td>
                <router-link :to="`/admin/user/show/${props.row.user.uid}`" class="row items-center q-col-gutter-sm">
                  <div>
                    <UserAvatarByUid :uid="props.row.user.uid" size="24px" />
                  </div>
                  <div class="my-table-details">
                    {{ props.row.user.name }}
                  </div>
                </router-link>
              </q-td>
              <q-td class="text-center">
                <div>
                  {{ props.row.type.label }}
                </div>
                <div class="row items-center q-col-gutter-md no-wrap justify-center">
                  <div v-if="props.row.end_ban_time" class="row items-center q-col-gutter-sm no-wrap text-no-wrap">
                    <div>
                      до
                    </div>
                    <ShowTime :time="props.row.end_ban_time" />
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

              </q-td>
              <q-td>
                <div class="row items-center q-col-gutter-sm">
                  <div>
                    <UserAvatarByUid :uid="props.row.author.uid" size="24px" />

                  </div>
                  <div>
                    {{ props.row.author.name }}
                  </div>
                </div>
              </q-td>
              <q-td auto-width>
                <div class="row items-center no-wrap">
                  <div>
                    <ChangeEndBanTimeBtn :ban="props.row" @success="getData" />
                  </div>
                  <div>
                    <q-btn color="negative" icon="delete" flat dense @click="deleteBan(props.row)" />
                  </div>
                </div>
              </q-td>
            </q-tr>
          </transition>
        </template>
      </q-table>
    </transition-group>
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { deleteUserBan, getBanUserList } from 'src/Modules/BanUsers/api/apiBanUser'
import LoadMore from 'components/LoadMore/index.vue'
import { useQuasar } from 'quasar'
import FiltersForm from './components/FiltersForm/index.vue'
import ChangeEndBanTimeBtn from 'src/Modules/BanUsers/components/ChangeEndBanTime/ChangeEndBanTimeBtn.vue'
import ShowTime from 'components/ShowTime/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'

export default defineComponent({
  components: {
    LoadMore,
    FiltersForm,
    ChangeEndBanTimeBtn,
    ShowTime,
    UserAvatarByUid
  },
  props: {},
  setup() {
    const key = ref(1)
    const $q = useQuasar()
    const func = getBanUserList
    const listQuery = ref(
      {
        page: 1,
        limit: 20,
        find: '',
        sortBy: 'created_at',
        descending: false
      }
    )
    const list = ref([])
    const customSort = (rows, sortBy, descending) => {
      listQuery.value.sortBy = sortBy
      listQuery.value.descending = descending
      return rows
    }
    const columns = ref(
      [
        {
          name: 'id',
          label: 'id',
          align: 'center',
        },
        {
          name: 'created_at',
          label: 'от',
          align: 'center',
          sortable: true
        },
        {
          name: 'name',
          label: 'Пользователь',
          align: 'left',
        },
        {
          name: 'end_ban_time',
          label: 'Дата истечения',
          align: 'center',
          sortable: true
        },
        {
          name: 'author',
          label: 'Автор бана',
          align: 'center',
        },
        {
          name: 'actions',
          label: 'Действия',
          align: 'center',
        },
      ]
    )
    const setList = (val) => {
      list.value = val
    }
    const deleteBan = (ban) => {
      $q.dialog({
        title: 'Снять бан?',
        message: 'Удалить ' + ban.user.name + ' из бана?',
        cancel: true,
        ok: {
          label: 'Удалить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        const data = {
          ban_uid: ban.uid
        }
        deleteUserBan(data)
          .then(res => {
            getData()
          })
      })
    }
    const getData = () => {
      key.value++
    }
    onMounted(() => {
      // getData()
    })
    return {
      getData,
      deleteBan,
      customSort,
      func,
      setList,
      columns,
      listQuery,
      key,
      list
    }
  }
})
</script>

<style scoped>

</style>
