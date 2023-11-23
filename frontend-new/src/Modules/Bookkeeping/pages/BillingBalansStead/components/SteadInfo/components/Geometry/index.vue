<template>
  <div>
    <el-table
      :data="stead.descriptions.geodata.geometry.coordinates[0][0]"
      style="width: 100%"
    >
      <el-table-column

        label="x"
        width="180"
      >
        <template slot-scope="{row}">
          <span>
            {{ row[0] }}
          </span>
        </template>
      </el-table-column>
      <el-table-column
        label="y"
        width="180"
      >
        <template slot-scope="{row}">
          <span>
            {{ row[1] }}
          </span>
        </template>
      </el-table-column>
      <el-table-column
        label="y"
        width="180"
      >
        <template slot-scope="{row}">
          <span>
            {{ row[1] }}
          </span>
        </template>
      </el-table-column>
    </el-table>
    <YandexMap :coords="krd" :center="center" />
    <pre>
      {{ center }}
      {{ krd }}
    </pre>
  </div>
</template>

<script>
import { updateStead } from '@/api/admin/stead'
import YandexMap from '@/components/YandexMap'
export default {
  components: {
    YandexMap
  },
  props: {
    stead: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      key: ''
    }
  },
  computed: {
    kadastr_url() {
      return 'https://pkk.rosreestr.ru/#/search/?text=' + this.stead.descriptions.kadastr
    },
    krd() {
      return this.stead.coordinates.krd
      // const kdr = []
      // this.stead.discriptions.geodata.geometry.coordinates[0][0].forEach(item => {
      //   kdr.push([item[1], item[0]])
      // })
      // return [kdr, []]
    },
    center() {
      return this.stead.coordinates.center
      // return [this.stead.discriptions.geodata.properties.center.y, this.stead.discriptions.geodata.properties.center.x]
    }
  },
  methods: {
    editSize() {
      this.$prompt('Размер', 'Изменить', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.stead.size
      }).then(({ value }) => {
        this.stead.size = value
        this.saveData()
      }).catch(() => {
      })
    },
    editNote() {
      this.$prompt('Примечание', 'Изменить', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.stead.descriptions.note
      }).then(({ value }) => {
        this.stead.descriptions.note = value
        this.saveData()
      }).catch(() => {
      })
    },
    editNumber() {
      this.$prompt('Номер участка', 'Изменить', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.stead.number
      }).then(({ value }) => {
        this.stead.number = value
        this.saveData()
      }).catch(() => {
      })
    },
    saveData() {
      updateStead(this.stead.id, this.stead)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    }
  }
}
</script>

<style scoped>
  table {
    border-collapse: collapse;
    margin: 25px;
  }
  td, th {
    border: 1px solid #606266;
    padding: 5px 10px;
    text-align: center;
    color: #000000;
  }
</style>
