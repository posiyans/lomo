<template>
  <div>
    <q-table
      flat bordered
      :rows="list"
      :columns="columns"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      row-key="id"
    >
      <template v-slot:body-cell-number="props">
        <q-td :props="props" auto-width>
          <div>
            {{ props.row.id }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-fio="props">
        <q-td :props="props">
          <div class="row items-center q-col-gutter-sm text-grey-7">
            <div>
              <UserAvatarByUid :uid="props.row.uid" />
            </div>
            <div>
              <div class="row items-center q-col-gutter-xs">
                <div class="text-no-wrap ">
                  {{ fullNameFilter(props.row) }}
                </div>
                <div v-if="props.row.owner" class="text-teal cursor-pointer" @click="toOwner(props.row.owner.uid)">
                  <q-icon name="home" />
                </div>
              </div>

              <div v-if="props.row.last_connect" class="text-small-80">
                <ShowTime :time="props.row.last_connect" />
              </div>
            </div>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-phone="props">
        <q-td :props="props" class="text-grey-7">
          {{ props.row.options.phone }}
        </q-td>
      </template>
      <template v-slot:body-cell-email="props">
        <q-td :props="props">
                    <span
                      class="link-type"
                      :class="{
              'text-teal' : props.row.email_verified_at,
              'text-grey' : !props.row.email_verified_at
            }"
                      :title="props.row.email_verified_at ? '' : 'Не подтвержден'"
                    >
            {{ props.row.email }}</span>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="q-gutter-sm text-grey-7">
            <q-btn icon="info" flat :to="`/admin/user/show/${props.row.uid}`" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import OwnerUserNameAndProportionBlock from 'src/Modules/Owner/components/OwnerUserNameAndProportionBlock/index.vue'
import { useRouter } from 'vue-router'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import ShowTime from 'src/components/ShowTime/index.vue'

export default defineComponent({
  components: {
    OwnerUserNameAndProportionBlock,
    UserAvatarByUid,
    ShowTime
  },
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  setup(props, { emit }) {
    const router = useRouter()
    const columns = [
      {
        name: 'number',
        align: 'center',
        label: '№',
      },
      {
        name: 'fio',
        align: 'left',
        label: 'ФИО',
      },
      {
        name: 'phone',
        align: 'center',
        label: 'Телефон',
      },
      {
        name: 'email',
        align: 'center',
        label: 'Email',
      },
      {
        name: 'actions',
        align: 'center',
        label: '',
      }
    ]
    const toOwner = (uid) => {
      router.push('/admin/owner/show-info/' + uid)
    }
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
    return {
      toOwner,
      fullNameFilter,
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
