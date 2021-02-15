<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column label="Название">
      <template slot-scope="{row}">
        <span>{{ row.name }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Тариф">
      <template slot-scope="{row}">
        <span>{{ row.rate.description }}</span>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import { fetchList } from '@/api/rate'

export default {
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  data() {
    return {
      list: null,
      listQuery: {
        type: this.type
      },
      loading: false
    }
  },
  computed: {
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      this.loading = true
      fetchList(this.listQuery).then(response => {
        if (response.data.status) {
          this.list = response.data.data
        }
        this.loading = false
      })
    }
  }
}
</script>

