<template>
  <div>
    переделать!!!
    <table>
      <tr>
        <td>
          ФИО
        </td>
        <td>
          <span v-if="stead && stead.descriptions && stead.descriptions.fio">
            {{ stead.descriptions.fio }}
          </span>
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" />
        </td>
      </tr>
      <tr>
        <td>
          Адрес
        </td>
        <td>
          <span v-if="stead && stead.options && stead.options.adres">
            {{ stead.options.adres }}
          </span>
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" />
        </td>
      </tr>
      <tr>
        <td>
          Телефон
        </td>
        <td>
          <span v-if="stead && stead.options && stead.options.phone ">
            {{ stead.options.phone }}
          </span>
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editPhone" />
        </td>
      </tr>
      <tr>
        <td>
          Телефон доп.
        </td>
        <td>
          <span v-if="stead && stead.options && stead.options.phone_add">
            {{ stead.options.phone_add }}
          </span>
        </td>
        <td>
          <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editPhoneAdd" />
        </td>
      </tr>
      <tr>
        <td>
          Примечание
        </td>
        <td>
          <span v-if="stead && stead.options && stead.options.note">
            {{ stead.options.note }}
          </span>
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
      default: () => ({})
    }
  },
  data() {
    return {
      stead_loc: {
        descriptions: {},
        options: {}
      }
    }
  },
  computed: {
  },
  mounted() {
    this.stead_loc = this.stead
  },
  methods: {
    editPhone() {
      this.$prompt('Телефон', 'Изменить', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.stead.options.phone
      }).then(({ value }) => {
        this.stead.options.phone = value
        this.saveData()
      }).catch(() => {
      })
    },
    editPhoneAdd() {
      this.$prompt('Телефон дополнительные', 'Изменить', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.stead.options.phone_add
      }).then(({ value }) => {
        this.stead.options.phone_add = value
        this.saveData()
      }).catch(() => {
      })
    },
    editNote() {
      this.$prompt('Примечание', 'Изменить', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.stead.options.note
      }).then(({ value }) => {
        this.stead.options.note = value
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
