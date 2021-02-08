<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column align="center" label="ID" width="80">
      <template slot-scope="{ row }">
        <span>
          {{ row.id }}
        </span>
      </template>
    </el-table-column>
    <el-table-column min-width="300px" label="Назначение">
      <template slot-scope="{row}">
        <span>{{ row.title }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Описание" style="padding-top: 0;">
      <template slot-scope="{row}">
        <div style="font-size: 0.65rem; line-height: 0.75rem">
          <div v-for="item in row.options" :key="item.id">
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
