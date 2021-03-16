<template>
  <div>
    <div class="page-title">Сделать начисления всем садоводам</div>
    <el-form ref="reestrForm" :model="postForm" :rules="rules" :label-position="labelPosition" class="form-container" label-width="180px">
      <el-form-item label="Начислить">
        <el-select
          v-model="postForm.type"
          placeholder="Тип"
          class="filter-container__item"
          style="width: 160px"
          @change="changeType"
        >
          <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
        </el-select>
        <el-select
          v-model="postForm.receipt"
          placeholder="Выбрать"
          multiple
          collapse-tags
          class="filter-container__item"
          @change="changeRate"
        >
          <el-option
            v-for="item in rateList"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-date-picker
          v-if="showDate"
          v-model="postForm.nowDate"
          type="month"
          format="MMMM yyyy"
          value-format="yyyy-MM-dd"
          class="filter-container__item"
          placeholder="Месяц на который делается начисление"
          @change="changeRate"
        />
        <div class="filter-container__item" @click="showDate = !showDate">
          <i class="el-icon-setting" />
        </div>
      </el-form-item>
      <el-form-item style="" prop="title" label="Описание">
        <el-input v-model="postForm.title" placeholder="На что и за какой период делается начисление" />
      </el-form-item>
      <div class="admin-parge-title text-green">Начислить</div>
      <el-form-item v-for="item in receiptArray" :key="item.id" :label="item.name">
        <el-input v-model="item.rate.description" placeholder="" disabled style="width: 220px;" />
      </el-form-item>
      <el-form-item label="" />
      <el-form-item>
        <el-button v-loading="loading" type="danger" @click="submitForm">
          Начислить
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { createBillingReestr, updateBillingReestr } from '@/api/admin/billing'
// import { Money } from 'v-money'
// import { fetchList } from '@/api/rate'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'
import { fetchList } from '@/api/admin/setting/rate'

export default {
  // components: { Money },
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
      showDate: false,
      nowDate: new Date(),
      receiptTypes: [],
      id: '',
      money_b: {
        decimal: ',',
        thousands: ' ',
        prefix: '',
        suffix: ' руб/участок',
        precision: 2,
        masked: false
      },
      rateList: [],
      datetime: +new Date(),
      postForm: {
        type: '',
        receipt: [],
        nowDate: new Date(),
        title: ''
      },
      loading: false,
      rules: {
        title: [{ validator: validateRequire }]
      }
    }
  },
  computed: {
    receiptArray() {
      return this.rateList.filter(i => this.postForm.receipt.indexOf(i.id) !== -1)
    },
    exampleRate() {
      return (6 * this.postForm.ratio_a + this.postForm.ratio_b).toFixed(2)
    },
    labelPosition() {
      return this.$store.state.app.device === 'desktop' ? 'left' : 'top'
    }
  },
  created() {
    // поставить предыдущий месяц

    this.postForm.nowDate = this.$moment(this.postForm.nowDate.setMonth(this.postForm.nowDate.getMonth() - 1)).format('YYYY-MM-DD')

    this.getTypeList()
    if (this.isEdit) {
      this.id = this.$route.params && this.$route.params.id
      this.fetchData(this.id)
    }
  },
  methods: {
    changeType() {
      this.postForm.receipt = []
      this.postForm.title = ''
      this.getListRate()
    },
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = []
            response.data.data.forEach(item => {
              // if (!item.auto_invoice) {
              this.receiptTypes.push(item)
              // }
            })
            if (this.receiptTypes.length === 1) {
              this.listQuery.type = this.receiptTypes[0].id
            }
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    },
    fetchData(id) {
      // fetchReestr(id).then(response => {
      //   if (response.data.status) {
      //     this.postForm = response.data.data
      //   }
      // }).catch({})
    },
    changeRate() {
      // this.postForm.title = this.receipt.discription + ' ' + new Date().getFullYear() + ' год'
      const type = this.receiptTypes.find(i => i.id === this.postForm.type)
      this.postForm.title = type.name + ' '
      if (type.payment_period === 12) {
        this.postForm.title += this.$moment(this.postForm.nowDate).format('y')
      }
      if (type.payment_period === 6) {
        if (this.$moment().format('Q') < 3) {
          this.postForm.title += this.$moment(this.postForm.nowDate).format('1 полугодие YYYY')
        } else {
          this.postForm.title += this.$moment(this.postForm.nowDate).format('2 полугодие YYYY')
        }
      }
      if (type.payment_period === 3) {
        this.postForm.title += this.$moment(this.postForm.nowDate).format('Qo квартал YYYY')
      }
      if (type.payment_period === 1) {
        this.postForm.title += this.$moment(this.postForm.nowDate).format('MMMM YYYY')
      }
      this.postForm.title += ' ('
      this.postForm.receipt.forEach((item, i) => {
        if (i > 0) {
          this.postForm.title += ', '
        }
        this.postForm.title += this.rateList.find(i => i.id === item).name
      })
      this.postForm.title += ')'
      // this.postForm.ratio_b = +this.receipt.rate.ratio_b
    },
    getListRate() {
      fetchList({ type: this.postForm.type })
        .then(response => {
          if (response.data.status) {
            this.rateList = response.data.data
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    },
    submitForm() {
      // if (this.exampleRate > 0) {
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
      // } else {
      //   this.$message.error('Ничего не начисленно!!!')
      // }
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
            this.$router.push('/admin/bookkeping/billing_reestr')
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
            this.$router.push('/admin/bookkeping/billing_reestr')
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
