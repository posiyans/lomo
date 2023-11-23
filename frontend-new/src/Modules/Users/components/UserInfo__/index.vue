/* eslint-disable */
<template>
  <div>
    <div class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto">
      <ChangeUserAvatar :uid="user.uid" />
    </div>
    <RoleAndPermission v-model="user">
      <el-tab-pane label="Данные">
        <el-form ref="dataForm" :rules="rules" :model="user" :label-position="labelPosition" label-width="200px" style="">
          <el-form-item label="E-mail">
            <el-input v-model="user.email"></el-input>
          </el-form-item>
          <el-form-item label="Фамилия">
            <el-input v-model="user.last_name"></el-input>
          </el-form-item>
          <el-form-item label="Имя">
            <el-input v-model="user.name"></el-input>
          </el-form-item>
          <el-form-item label="Отчество">
            <el-input v-model="user.middle_name"></el-input>
          </el-form-item>
          <el-form-item label="Телефон">
            <el-input v-model="user.phone"></el-input>
          </el-form-item>
          <el-form-item label="Адрес регистрации/почтовый адрес">
            <el-input type="textarea" v-model="user.adres"></el-input>
          </el-form-item>
          <el-form-item label="Номер Участка(ов)">
            <el-select
              v-model="user.steads"
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
    </RoleAndPermission>
    <div class="user-show-footer">
      <el-button type="primary" @click="updateData()">
        Сохранить
      </el-button>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { getSteadsList } from 'src/Modules/Stead/api/stead.js'
import RoleAndPermission from 'src/Modules/Users/components/UserRoleAndPermissions/index.vue'
import ChangeUserAvatar from 'src/Modules/Avatar/components/ChangeUserAvatar/index.vue'
import { updateUserData } from 'src/Modules/Users/api/user-admin-api.js'

export default {
  components: {
    RoleAndPermission,
    ChangeUserAvatar
  },
  props: {
    modelValue: {
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
    mobile() {
      return this.$q.platform.is.mobile
    },
    labelPosition() {
      if (this.mobile) {
        return 'top'
      }
      return 'left'
    }
  },
  mounted() {
    this.user = Object.assign({}, this.modelValue)
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
        user: this.user
      }
      updateUserData(data, this.modelValue.id)
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
