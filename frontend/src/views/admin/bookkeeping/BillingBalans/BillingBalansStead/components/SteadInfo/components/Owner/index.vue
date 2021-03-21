<template>
  <div>
    <table>
      <tr v-for="item in owners" :key="item.uid">
        <td>
          ФИО
        </td>
        <td>
          <span>
            {{ item.owner_fullName }} {{ item.proportion | propFilter }}
          </span>
        </td>
        <td v-if="personal">
          <div class="pointer" @click="showOwner(item.id)">
            <el-button type="primary" size="mini" plain icon="el-icon-edit" />
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { updateStead } from '@/api/admin/stead'
import { getOwnerForStead } from '@/api/admin/stead'
export default {
  filters: {
    propFilter(val) {
      if (val < 100) {
        return '(' + val + '%)'
      }
      return ''
    }
  },
  props: {
    stead: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      owners: [],
      stead_loc: {
        descriptions: {},
        options: {}
      }
    }
  },
  computed: {
    personal() {
      return this.$store.state.user.info.allPermissions.includes('access-to-personal')
    }
  },
  mounted() {
    this.stead_loc = this.stead
    this.getOwnerList()
  },
  methods: {
    showOwner(id) {
      this.$router.push('/admin/owner/show-info/' + id)
    },
    getOwnerList() {
      getOwnerForStead(this.stead.id)
        .then(response => {
          if (response.data.status) {
            this.owners = response.data.data
          } else {
            if (response.data.error) {
              this.$message.error(response.data.error)
            }
          }
        })
    },
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
