<template>
  <div>
    <q-table
      flat
      bordered
      :rows="type"
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
      <template v-slot:body-cell-label="props">
        <q-td :props="props">
          <div class="">
            {{ props.row.label }}
            <q-popup-edit v-model="props.row.label" title="Тип" buttons persistent v-slot="scope" @save="saveData(props.row)">
              <q-input v-model="scope.value" autofocus />
            </q-popup-edit>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-description="props">
        <q-td :props="props">
          <div class="">
            {{ props.row.description }}
            <q-popup-edit v-model="props.row.description" title="Описание" buttons persistent v-slot="scope" @save="saveData(props.row)">
              <q-input v-model="scope.value" autofocus />
            </q-popup-edit>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-public="props">
        <q-td :props="props" auto-width>
          <div>
            <div>
              <q-icon :name="props.row.public ? 'task_alt' : 'highlight_off'" size="2em" :color="props.row.public ? 'teal' : 'red'" />
            </div>
            <q-popup-edit v-model="props.row.public" title="Доступен пользователю" buttons persistent v-slot="scope" @save="saveData(props.row)">
              <q-checkbox v-model="scope.value" :false-value="0" :true-value="1" label="Доступен" autofocus />
            </q-popup-edit>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-action="props">
        <q-td :props="props" auto-width>
          <div>
            <DeleteAppealTypeBtn :appeal-type="props.row" @success="getType" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, nextTick, onMounted, ref } from 'vue'
import { useAppealType } from 'src/Modules/Appeal/hooks/useAppealType'
import { updateAppealType } from 'src/Modules/Appeal/api/appealTypeApi'
import { errorMessage } from 'src/utils/message'
import DeleteAppealTypeBtn from 'src/Modules/Appeal/components/DeleteAppealTypeBtn/index.vue'

export default defineComponent({
  components: {
    DeleteAppealTypeBtn
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const { type, getType } = useAppealType()
    const columns = [
      {
        name: 'number',
        align: 'center',
        label: 'id',
      },
      {
        name: 'label',
        align: 'center',
        label: 'Тип',
      },
      {
        name: 'description',
        align: 'left',
        label: 'Описание'
      },
      {
        name: 'public',
        align: 'center',
        label: 'Публичный'
      },
      {
        name: 'action',
        align: 'center',
      }
    ]
    onMounted(() => {

    })
    const saveData = (appealType) => {
      console.log(appealType)
      nextTick(() => {
        updateAppealType(appealType.id, appealType)
          .then(() => {
            getType()
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    return {
      data,
      type,
      columns,
      saveData,
      getType
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
