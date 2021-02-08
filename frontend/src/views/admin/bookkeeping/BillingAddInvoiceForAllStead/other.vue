<template>
  <div>
    <div class="page-title">Сделать разовое начисление всем садоводам</div>
    <el-form ref="reestrForm" :model="postForm" :rules="rules" :label-position="labelPosition" class="form-container" label-width="180px">
      <el-form-item label="Начислить">
        <el-select
          v-model="postForm.type"
          placeholder="Тип"
          class="filter-container__item"
          style="width: 160px"
        >
          <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item style="" prop="title" label="Назначение">
        <el-input v-model="postForm.title" placeholder="На что или за что делается начисление" />
      </el-form-item>
      <el-form-item label="Начислить на 1 сотку:">
        <money v-model="postForm.ratio_a" placeholder="Начисление на 1 сотку участка" v-bind="money_a" />
      </el-form-item>
      <el-form-item label="Начислить на 1 участок:">
        <money v-model="postForm.ratio_b" placeholder="Начисление на 1 участок, внезависимости от площади" v-bind="money_b" />
      </el-form-item>
      <el-form-item label="">
        <div style="color: #1b5fab;">
          Например на 1 участок площадью 6 соток будет начисленно {{ exampleRate }} рублей
        </div>
      </el-form-item>
      <el-form-item>
        <div>
          <el-button v-loading="loading" type="danger" @click="submitForm">
            Начислить
          </el-button>
        </div>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { createBillingReestr, fetchReestr, updateBillingReestr } from '@/api/admin/billing'
import { Money } from 'v-money'
// import { fetchList } from '@/api/rate'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

const defaultForm = {
  title: '',
  ratio_a: 0,
  ratio_b: 0,
  one_time: true,
  type: ''
}

export default {
  components: { Money },
  props: {
    isEdit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        this.$message({
          message: rule.field + ' Обязательное поле',
          type: 'error'
        })
        callback(new Error(' Обязательное поле'))
      } else {
        callback()
      }
    }
    return {
      money_a: {
        decimal: ',',
        thousands: ' ',
        prefix: '',
        suffix: ' руб/сотка',
        precision: 2,
        masked: false
      },
      id: '',
      receiptTypes: [],
      money_b: {
        decimal: ',',
        thousands: ' ',
        prefix: '',
        suffix: ' руб/участок',
        precision: 2,
        masked: false
      },
      rateList: [],
      rate_checked: '',
      datetime: +new Date(),
      postForm: Object.assign({}, defaultForm),
      loading: false,
      rules: {
        title: [{ validator: validateRequire }]
      }
    }
  },
  computed: {
    exampleRate() {
      return (6 * this.postForm.ratio_a + this.postForm.ratio_b).toFixed(2)
    },
    labelPosition() {
      return this.$store.state.app.device === 'desktop' ? 'left' : 'top'
    }
  },
  created() {
    this.getTypeList()
    if (this.isEdit) {
      this.id = this.$route.params && this.$route.params.id
      this.fetchData(this.id)
    }
  },
  methods: {
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = []
            response.data.data.forEach(item => {
              if (item.depends === 1) {
                this.receiptTypes.push(item)
              }
            })
            if (this.receiptTypes.length === 1) {
              this.postForm.type = this.receiptTypes[0].id
            }
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    },
    fetchData(id) {
      fetchReestr(id).then(response => {
        if (response.data.status) {
          this.postForm = response.data.data
          this.setPageTitle()
        }
      }).catch({})
    },
    setTagsViewTitle() {
      const title = 'Статья id'
      const route = Object.assign({}, this.tempRoute, { title: `${title}-${this.postForm.id}` })
      this.$store.dispatch('tagsView/updateVisitedView', route)
    },
    setPageTitle() {
      const title = 'Начисление'
      document.title = `${title} - ${this.postForm.title}`
    },
    changeRate() {
      this.postForm.title = this.rate_checked.discription + ' ' + new Date().getFullYear() + ' год'
      this.postForm.ratio_a = +this.rate_checked.rate.ratio_a
      this.postForm.ratio_b = +this.rate_checked.rate.ratio_b
    },
    submitForm() {
      if (this.exampleRate > 0) {
        this.$refs['reestrForm'].validate((valid) => {
          if (valid) {
            if (this.isEdit) {
              this.updateForm()
            } else {
              this.saveForm()
            }
          } else {
            this.$message.error('Введите описание!!!')
          }
        })
      } else {
        this.$message.error('Ничего не начисленно!!!')
      }
    },
    updateForm() {
      this.$confirm('Вы точно хотите изменить начисления?', 'Внимание', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        updateBillingReestr(this.id, this.postForm).then(response => {
          if (response.data.status) {
            this.$notify({
              title: 'успех',
              message: 'Начисления перепроведенны',
              type: 'success',
              duration: 2000
            })
            this.$router.push('/bookkeping/billing_reestr')
          }
        })
      }).catch(() => {
      })
    },
    saveForm() {
      this.$confirm('Вы точно хотите начислить?', 'Внимание', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        createBillingReestr(this.postForm).then(response => {
          if (response.data.status) {
            this.$notify({
              title: 'успех',
              message: 'Начисления сделаны',
              type: 'success',
              duration: 2000
            })
            this.$router.push('/bookkeping/billing_reestr')
          }
        })
      }).catch(() => {
      })
    }
  }
}
</script>

<style scoped>
  .createPost-container {
    position: relative;
    padding: 40px 45px 20px 25px;
  }

  @media screen and (max-width: 700px) {
    .createPost-container {
      padding: 40px 6px 20px 6px;
    }
  }
</style>
