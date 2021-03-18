<template>
  <div class="app-container">
    <div class="page-title">Собственник</div>
    <div class="pt3">
      <table :key="key">
        <tr>
          <th class="tl" style="width: 180px;">Поле</th>
          <th class="tc">Значение</th>
        </tr>
        <tr v-for="field in fields" :key="field.name">
          <td class="tl">
            {{ field.label }}
          </td>
          <td class="">
            <div class="relative w-100">
              <div v-if="!field.edit" class="mr6">
                {{ form[field.name] | valueFilter }}
              </div>
              <div v-if="field.edit">
                <!--                <el-input v-model="form[field.name]" size="mini" :placeholder="field.label"></el-input>-->
                <EditForm v-model="form[field.name]" :field="field" :type="field.type" :label="field.label" :mask="field.mask" @close="close" />
              </div>
              <div v-if="!field.edit" class="table-line-setting-icon" @click="editField(field)">
                <!--                <i v-if="field.edit" class="el-icon-check dark-green" />-->
                <!--                <i v-if="field.edit" class="el-icon-close dark-red" />-->
                <i class="el-icon-edit-outline" />
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="relative w-100">
              <div>
                Участок
              </div>
              <div class="table-line-setting-icon" @click="AddSteadShow">
                <i class="el-icon-circle-plus-outline dark-green" />
              </div>
            </div>
          </td>
          <td>
            <div class="relative">
              <div class="stead-group">
                <div v-for="stead in form.steads" :key="stead.id" class="stead-group__button" @click="openSteadInfo(stead.stead_id)">
                  {{ stead.number }} {{ stead.proportion | propFilter }}
                </div>
              </div>
              <div class="table-line-setting-icon">
                <i class="el-icon-edit-outline" />
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <el-dialog
      title="Добавить участок собственнику"
      :destroy-on-close="true"
      :visible.sync="addSteadFormShow"
      width="30%"
    >
      <AddSteadForm :owner-id="id" :name="fullname" @close="closeAddForm" />
    </el-dialog>
  </div>
</template>

<script>
import { getOwnerUserInfo, getOwnerFieldList, updateOwnerUserInfo } from '@/api/admin/owner/owner-api'
import EditForm from './componets/EditForm'
import AddSteadForm from './componets/AddsteadForm'

export default {
  components: {
    EditForm,
    AddSteadForm
  },
  filters: {
    propFilter(val) {
      if (val < 100) {
        return '(' + val + '%)'
      }
      return ''
    },
    valueFilter(val) {
      if (val) {
        return val
      }
      return '-'
    }
  },
  data() {
    return {
      key: 0,
      id: '',
      fields: [],
      addSteadFormShow: false,
      form: {
        surname: '',
        name: '',
        middle_name: '',
        date_birth: null,
        email: '',
        general_phone: '',
        phones: '',
        address: '',
        address_notifications: '',
        member: ''
      }
    }
  },
  computed: {
    fullname() {
      return this.form.surname + ' ' + this.form.name + ' ' + this.form.middle_name
    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
    this.getFields()
    this.getOwner()
  },
  methods: {
    closeAddForm() {
      this.addSteadFormShow = false
    },
    openSteadInfo(id) {
      this.$router.push('/admin/bookkeping/billing_balance_stead/' + id)
    },
    AddSteadShow() {
      this.addSteadFormShow = true
    },
    close(val) {
      this.fields.forEach(item => {
        if (item.name === val.field) {
          item.edit = false
          this.form[item.name] = val.value
          this.update(item.name, this.form[item.name])
          // updateOwnerUserInfo(this.id, { [item.name]: this.form[item.name] })
        }
      })
      this.key++
      console.log(val.field)
    },
    editField(field) {
      this.fields.forEach(item => {
        item.edit = false
      })
      field.edit = !((field.edit))
      this.key++
    },
    update(field, value) {
      updateOwnerUserInfo(this.id, { fields: { [field]: value }})
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            // this. = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    getFields() {
      getOwnerFieldList()
        .then(response => {
          if (response.data.status) {
            for (const i in response.data.data) {
              const item = response.data.data[i]
              item.name = i
              this.fields.push(item)
            }
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    getOwner() {
      getOwnerUserInfo(this.id)
        .then(response => {
          if (response.data.status) {
            this.form = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    onSubmit() {
      // addOwnerUser({ user: this.form })
      //   .then(response => {
      //     console.log(response.data)
      //       if (response.data.status) {
      //         console.log('push')
      //         this.$router.push('/admin/owner/show-info/' + response.data.data)
      //       } else {
      //         console.log('er')
      //         if (response.data.data) {
      //           this.$message.error(response.data.data)
      //         }
      //       }
      //   })
      console.log('submit!')
    }
  }
}
</script>

<style scoped>
  .table-line-setting-icon {
    position: absolute;
    right: 5px;
    top: 0;
    color: #89b1ff;
    cursor: pointer;
  }
  table {
    border-collapse: collapse;
    margin: 25px;
  }
  div[contenteditable="true"] {
    background-color: #fff4e6;
    border: 1px solid #000;
  }

  td, th {
    border: 1px solid #000000;
    padding: 3px 10px;
    line-height: 1.5em;
    /*text-align: center;*/
  }
  .stead-group {
    justify-content: center;
  }
  .stead-group__button {
    cursor: pointer;
    border: 1px solid #357edd;
    border-radius: 0.25rem;
    background-color: #cdecff;
    margin-bottom: 0.25rem;
    min-width: 65px;
    max-width: 80px;
    text-align: center;
  }
  .stead-group__button:hover {
    background-color: #97d2ff;
    font-weight: 600;

  }
  .stead-group__button:last-child {
    margin-bottom: 0;
  }
</style>
