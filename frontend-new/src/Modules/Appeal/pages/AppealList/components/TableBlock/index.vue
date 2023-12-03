<template>
  <div>
    <q-table
      flat
      bordered
      :rows="list"
      :columns="filterColumns"
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
      <template v-slot:body-cell-date="props">
        <q-td :props="props" auto-width>
          <div class="">
            <ShowTime :time="props.row.created_at" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-type="props">
        <q-td :props="props" auto-width>
          <div class="">
            {{ props.row.type.label }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-fio="props">
        <q-td :props="props">
          <div class="">
            <router-link :to="`/admin/user/show/${props.row.user.uid}?tab=appeal`">
              {{ props.row.user.fullName }}
            </router-link>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-title="props">
        <q-td :props="props">
          <div class="">
            {{ props.row.title }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-status="props">
        <q-td :props="props">
          <div class="">
            <AppealStatusLabelById :status="props.row.status" add-color />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          {{ props.row }}
          <div class="cursor-pointer" @click="showInfo(props.row)">
            <q-icon name="info" size="24px" color="primary" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import { useRouter } from 'vue-router'
import ShowTime from 'src/components/ShowTime/index.vue'
import AppealTypeNameById from 'src/Modules/Appeal/components/AppealTypeNameById/index.vue'
import AppealStatusLabelById from 'src/Modules/Appeal/components/AppealStatusLabelById/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ShowTime,
    AppealTypeNameById,
    AppealStatusLabelById
  },
  props: {
    list: {
      type: Array,
      default: () => ([])
    },
    addFio: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const $router = useRouter()
    const columns = [
      {
        name: 'number',
        align: 'center',
        label: 'id',
      },
      {
        name: 'date',
        align: 'center',
        label: 'Дата',
      },
      {
        name: 'type',
        align: 'center',
        label: 'Тип',
      },
      {
        name: 'fio',
        align: 'center',
        label: 'ФИО'
      },
      {
        name: 'title',
        align: 'center',
        label: 'Тема'
      },

      {
        name: 'status',
        align: 'center',
        label: 'Статус',
      },
      {
        name: 'actions',
        align: 'center',
        label: ''
      },

    ]
    const authStore = useAuthStore()
    const filterColumns = computed(() => {
      return columns.filter(column => {
        if (column.name === 'fio' && !props.addFio) {
          return false
        } else {
          return true
        }
      })
    })
    const pushToStead = (id) => {
      $router.push('/admin/bookkeping/billing_balance_stead/' + id)
    }
    const showInfo = (appeal) => {
      if (authStore.user.uid === appeal.user.uid) {
        $router.push('/appeal/show/' + appeal.id)
      } else {
        $router.push('/admin/appeal/show/' + appeal.id)
      }

    }
    const propFilter = (val) => {
      if (val < 100) {
        return '(' + val + '%)'
      }
      return ''
    }
    return {
      filterColumns,
      propFilter,
      pushToStead,
      showInfo
    }
  }
})
</script>

<style scoped>

</style>
