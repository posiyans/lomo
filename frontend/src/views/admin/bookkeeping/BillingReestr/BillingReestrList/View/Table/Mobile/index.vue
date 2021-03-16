<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column min-width="160px" label="Заголовок">
      <template slot-scope="{row}">
        <span class="do-not-carry">{{ row.id }}. {{ row.title }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Actions">
      <template slot-scope="scope">
        <router-link :to="'/admin/bookkeping/billing_reestr_edit/'+scope.row.id">
          <el-button type="primary" size="small" icon="el-icon-edit">
            Edit
          </el-button>
        </router-link>
      </template>
    </el-table-column>
    <el-table-column type="expand">
      <template slot-scope="props">
        <div style="font-size: 0.65rem; line-height: 0.75rem">
          <div v-for="item in props.row.options" :key="item.id">
            {{ item.name }} {{ item.rate.description }}
          </div>
        </div>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    list: {
      type: Array,
      default: () => { [] }
    }
  }
}
</script>

<style scoped>

</style>
