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
      <template v-slot:body-cell-fio="props">
        <q-td :props="props">
          <div class="">
            {{ props.row.fullName }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-stead="props">
        <q-td :props="props">
          <div v-for="item in props.row.steads" :key="item.stead_id" class="cursor-pointer" @click="pushToStead(item.stead_id)">
            <q-chip outline square color="primary" text-color="white">
              {{ item.number }} {{ propFilter(item.proportion) }}
            </q-chip>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="cursor-pointer" @click="showInfo(props.row.uid)">
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
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {
    list: {
      type: Array,
      default: () => ([])
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
        name: 'fio',
        align: 'center',
        label: 'ФИО',
      },
      {
        name: 'stead',
        align: 'center',
        label: 'Участок',
      },
      {
        name: 'phone',
        align: 'center',
        field: 'general_phone',
        label: 'Телефон',
        hide: true
      },
      {
        name: 'email',
        align: 'center',
        label: 'email',
        field: 'email',
        hide: true
      },
      {
        name: 'actions',
        align: 'center',
        label: 'Поробнее'
      }
    ]
    const authStore = useAuthStore()
    const admin = computed(() => {
      return authStore.permissions.includes('owner-show') || authStore.permissions.includes('owner-edit')
    })
    const filterColumns = computed(() => {
      return columns.filter(column => {
        if (column.hide) {
          if (admin.value) {
            return true
          } else {
            return false
          }
        } else {
          return true
        }
      })
    })
    const pushToStead = (id) => {
      $router.push('/admin/bookkeping/billing_balance_stead/' + id)
    }
    const showInfo = (uid) => {
      $router.push('/admin/owner/show-info/' + uid)
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
