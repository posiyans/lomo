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
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger'
      }
      return statusMap[status]
    }
  },
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  data() {
    return {
      tabMapOptions: [
        { label: 'Коммунальные', key: '1' },
        { label: 'Взносы', key: '2' }
      ],
      list: null,
      listQuery: {
        page: 1,
        limit: 5,
        type: this.type,
        sort: '+id'
      },
      loading: false
    }
  },
  computed: {
    rate_disc() {
      if (this.rating.type_id == 1) {
        return this.rating.rate.ratio_a + ' руб*кВт/ч'
      }
      if (this.rating.type_id == 2) {
        let text = ''
        if (this.rating.rate.ratio_a > 0) {
          text += this.rating.rate.ratio_a + ' руб с сотки'
        }
        if (this.rating.rate.ratio_b > 0) {
          if (this.rating.rate.ratio_a > 0) {
            text += ' и '
          }
          text += this.rating.rate.ratio_b + ' руб с участка'
        }
        return text
      }
      return ''
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      this.loading = true
      this.$emit('create') // for test
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.loading = false
      })
    }
  }
}
</script>

