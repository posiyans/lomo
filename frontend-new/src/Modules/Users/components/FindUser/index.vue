<template>
  <div>
    <div class="q-pb-sm">
      <q-input
        v-model="listQuery.find"
        label="Поиск"
        :hint="inputHint"
        clearable
        outlined
        dense
        @update:model-value="getData"
      />
    </div>
    <q-table
      hide-bottom
      :rows="list"
      :columns="columns"
      row-key="uid"
    >
      <template v-slot:body-cell-name="props">
        <q-td :props="props">
          <div>
            {{ props.row.last_name }}
            {{ props.row.name }}
            {{ props.row.middle_name }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-phone="props">
        <q-td :props="props">
          <div>
            {{ props.row.options.phone }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-action="props">
        <q-td :props="props">
          <div>
            <q-btn label="Выбрать" color="primary" :outline="!modelValue || modelValue?.uid !== props.row.uid" @click="selectUser(props.row)" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { fetchList } from 'src/Modules/Users/api/user-admin-api.js'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object
    }
  },
  setup(props, { emit }) {
    const columns = [
      {
        name: 'name',
        required: true,
        label: 'ФИО',
        align: 'left'
      },
      {
        name: 'email',
        align: 'center',
        label: 'Email',
        field: 'email'

      },
      {
        name: 'phone',
        align: 'center',
        label: 'Телефон'
      },
      {
        name: 'action',
        align: 'center',
        label: ''
      }
    ]
    const dialogVisible = ref(false)
    const inputHint = ref(undefined)
    const list = ref([])
    const showEditForm = () => {
      dialogVisible.value = true
    }
    const selectUser = (user) => {
      emit('update:model-value', user)
    }
    const getData = () => {
      inputHint.value = undefined
      list.value = []
      if (listQuery.value.find.length > 2) {
        fetchList(listQuery.value)
          .then(res => {
            list.value = res.data.data
            if (list.value.length === 0) {
              inputHint.value = 'Ничего не найдено'
            }
          })
      }
    }
    const listQuery = ref({
      page: 1,
      limit: 5,
      find: ''
    })
    return {
      columns,
      selectUser,
      dialogVisible,
      list,
      inputHint,
      getData,
      listQuery,
      showEditForm
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
