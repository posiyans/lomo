<template>
  <div>
    <div class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto" >
      <AvatarUpload :user_id="value.id" v-model="value.avatar"/>
    </div>
    <RoleAndPermision v-model="value">
      <el-tab-pane label="Данные">
        <el-form ref="dataForm" :rules="rules" :model="user" :label-position="labelPosition" label-width="200px" style="">
          <el-form-item label="E-mail">
            <el-input v-model="value.email"></el-input>
          </el-form-item>
          <el-form-item label="Фамилия">
            <el-input v-model="value.last_name"></el-input>
          </el-form-item>
          <el-form-item label="Имя">
            <el-input v-model="value.name"></el-input>
          </el-form-item>
          <el-form-item label="Отчество">
            <el-input v-model="value.middle_name"></el-input>
          </el-form-item>
          <el-form-item label="Телефон">
            <el-input v-model="value.phone"></el-input>
          </el-form-item>
          <el-form-item label="Адрес регистрации/почтовый адрес">
            <el-input type="textarea" v-model="value.adres"></el-input>
          </el-form-item>
          <el-form-item label="Номер Участка(ов)">
            <el-select
              v-model="value.steads"
              multiple
              filterable
              remote
              reserve-keyword
              placeholder="Введите номер участка"
              no-data-text="Данный номер не найден"
            >
              <el-option
                v-for="item in steadsList"
                :key="item.id"
                :label="item.number"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </RoleAndPermision>
    <div class="user-show-footer">
      <el-button type="primary" @click="updateData()">
        Сохранить
      </el-button>
    </div>
  </div>
</template>


<script>
import { getSteadsList } from '@/api/user/stead.js'
import RoleAndPermision from '@/views/admin/users/components/permisions/index'
import AvatarUpload from '@/components/AvatarUpload/index'
import { mapState } from 'vuex'
import { updateUserData } from '@/api/admin/user'

export default {
  components: {
    RoleAndPermision,
    AvatarUpload
  },
  props: {
    value: {
      type: Object,
      defaults: {}
    }
  },
  data() {
    return {
      user: {},
      loading: true,
      steadsList: [],
      rules: {
        // type: [{ required: true, message: 'type is required', trigger: 'change' }],
        // timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        // title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      },
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    }),
    mobile() {
      if (this.device === 'mobile') {
        return true
      }
      return false
    },
    labelPosition() {
      if (this.mobile){
        return 'top'
      }
      return 'left'
    }
  },
  mounted() {
    this.fetchSteads()
  },
  methods: {
    fetchSteads() {
      getSteadsList()
        .then(response => {
          this.steadsList = response.data
        })
    },
    updateData() {
      const data = {
        user: this.value
      }
      updateUserData(data, this.value.id)
        .then(response => {
          this.$notify({
            title: 'Успех',
            message: 'Данные успешно сохранены',
            type: 'success',
            duration: 2000
          })
        })
    },
  }
}
</script>

<style scoped>
 .user-show-footer {
   padding: 20px 0;
 }
</style>
