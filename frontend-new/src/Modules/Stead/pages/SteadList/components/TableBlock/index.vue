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
          <div class="my-table-details">
            <q-chip outline square color="primary" text-color="white">
              {{ props.row.number }}
            </q-chip>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-size="props">
        <q-td :props="props" auto-width>
          <div class="my-table-details">
            {{ props.row.size }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-owner="props">
        <q-td :props="props">
          <div v-for="owner in props.row.owners" :key="owner.uid" class="cursor-pointer" @click="toOwner(owner.uid)">
            <OwnerUserNameAndProportionBlock :owner="owner" class="justify-center" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="q-gutter-sm">
            <q-btn label="Инфо" color="primary" :to="`/admin/stead/info/${props.row.id}`" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import OwnerUserNameAndProportionBlock from 'src/Modules/Owner/components/OwnerUserNameAndProportionBlock/index.vue'
import { useRouter } from 'vue-router'

export default defineComponent({
  components: {
    OwnerUserNameAndProportionBlock
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
        label: '№'
      },
      {
        name: 'size',
        align: 'center',
        label: 'Размер, кв.м'
      },
      {
        name: 'owner',
        align: 'center',
        label: 'Собственник'
      },
      // {
      //   name: 'balans',
      //   align: 'center',
      //   label: 'Баланс',
      // },
      {
        name: 'actions',
        align: 'center',
        label: ''
      }
    ]
    const toOwner = (uid) => {
      router.push('/admin/owner/show-info/' + uid)
    }
    return {
      toOwner,
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
