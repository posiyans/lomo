<template>
  <div>
    <table v-if="stead">
      <tr>
        <td>
          id
        </td>
        <td>
          {{ stead.id }}
        </td>
        <td />
      </tr>
      <tr>
        <td>
          Номер
        </td>
        <td>
          {{ stead.number }}
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editNumber" />
        </td>
      </tr>
      <tr>
        <td>
          Размер
        </td>
        <td>
          {{ stead.size }} m<sup>2</sup>
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editSize" />
        </td>
      </tr>
      <tr>
        <td>
          Кадастровый номер
        </td>
        <td>
          <a :href="kadastr_url" target="_blank" class="pointer">
            {{ stead.discriptions.kadastr }}
          </a>
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" />
        </td>
      </tr>
      <tr>
        <td>
          Примечание
        </td>
        <td>
          {{ stead.discriptions.note }}
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editNote" />
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { updateStead } from '@/api/admin/stead'
export default {
  props: {
    stead: {
      type: Object,
      dafault: () => ({})
    }
  },
  data() {
    return {
      key: ''
    }
  },
  computed: {
    kadastr_url() {
      return 'https://pkk.rosreestr.ru/#/search/?text=' + this.stead.discriptions.kadastr
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
        inputValue: this.stead.discriptions.note
      }).then(({ value }) => {
        this.stead.discriptions.note = value
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
