<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column align="center" label="ID" width="80">
      <template #default="{ row }">
        <span>
          {{ row.id }}
        </span>
      </template>
    </el-table-column>
    <el-table-column min-width="300px" label="Назначение">
      <template #default="{row}">
        <span>{{ row.title }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Описание" style="padding-top: 0;">
      <template #default="{row}">
        <div style="font-size: 0.65rem; line-height: 0.75rem">
          <div v-for="item in row.options" :key="item.id">
            {{ item.name }}
            <span v-if="item.rate">
              {{ item.rate.description }}
            </span>
          </div>
        </div>
      </template>
    </el-table-column>
    <el-table-column align="center" label="">
      <template #default="{row}">
        <router-link :to="'/admin/bookkeping/billing_reestr_edit/'+row.id">
          <el-button type="primary">
            Edit
          </el-button>
        </router-link>
        <span>
          <el-button type="danger" plain @click="deleteInvoice(row)">Удалить</el-button>
        </span>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import { DeleteReestr } from 'src/Modules/Bookkeeping/api/billingAminApi.js'

export default {
  props: {
    list: {
      type: Array,
      default: () => []
    }
  },
  computed: {},
  methods: {
    deleteInvoice(row) {
      this.$confirm('Вы точно хотите удалить начисление?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        DeleteReestr(row.id)
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно удалены')
              this.$emit('reload')
            } else if (response.data.data) {
              this.$message.error(response.data.data)
            }
          })
      }).catch(() => {

      })
    }
  }
}
</script>

<style scoped>

</style>
