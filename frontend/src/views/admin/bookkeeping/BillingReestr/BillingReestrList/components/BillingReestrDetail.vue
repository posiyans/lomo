<template>
  <div class="createPost-container">
    <div class="ml4-ns">
      <el-button @click="$router.push('/bookkeping/billing_reestr')">Начисления</el-button>
    </div>
    <el-form ref="reestrForm" :model="postForm" :rules="rules"  :label-position="labelPosition" class="form-container" label-width="180px">
      <div class="pt1 createPost-main-container" style="padding-top: 0; padding-bottom: 0">
        <div style="display: inline-block; padding-right: 10px; padding-top: 20px;">
          <b>Сделать начисления всем садоводам</b>
        </div>
        <div v-if="isEdit" style="display: inline-block;">
          <el-button v-loading="loading"  type="danger" @click="submitForm">
            Пересчитать
          </el-button>
        </div>
        <div v-else style="display: inline-block;">
          <el-button v-loading="loading"  type="danger" @click="submitForm">
            Начислить
          </el-button>
        </div>
      </div>
      <div class="createPost-main-container" style="max-width: 650px;">
        <el-select
          v-model="rate_checked"
          placeholder="Заполнить"
          @change="changeRate"
        >
          <el-option
            v-for="item in rateList"
            :key="item.id"
            :label="item.name"
            :value="item">
          </el-option>
        </el-select>
        <el-form-item style="margin-bottom: 10px;" prop="title" label="Описание">
          <el-input v-model="postForm.title" placeholder="На что и за какой период делается начисление"/>
        </el-form-item>
        <el-form-item label="Начислить на 1 сотку:">
          <money v-model="postForm.ratio_a" placeholder="Начисление на 1 сотку участка" v-bind="money_a">
          </money>
        </el-form-item>
        <el-form-item label="Начислить на 1 участок:">
          <money v-model="postForm.ratio_b" placeholder="Начисление на 1 участок, внезависимости от площади" v-bind="money_b">
          </money>
        </el-form-item>

        <div style="color: #1b5fab;">
          Например на 1 участок площадью 6 соток будет начисленно {{ exampleRate }} рублей
        </div>
        <div v-if="postForm.updated_at" class="mt2" style="color: #ff0000">
          Начисленно {{postForm.updated_at | moment("DD MMMM YYYY в HH:mm")}}
        </div>
      </div>
    </el-form>
  </div>
</template>

<script>
import { createBillingReestr, fetchReestr, updateBillingReestr } from '@/api/admin/billing'
import { Money } from 'v-money'
import { fetchList } from '@/api/rate'

const defaultForm = {
  title: '',
  ratio_a: 0,
  ratio_b: 0
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
        title: [{ validator: validateRequire }],
      },
    }
  },
  computed: {
    exampleRate() {
      return (6 * this.postForm.ratio_a + this.postForm.ratio_b).toFixed(2)
    },
    labelPosition() {
      return this.$store.state.app.device === 'desktop' ? 'left' : 'top'
    },
  },
  created() {
    this.getListRate()
    if (this.isEdit) {
      this.id = this.$route.params && this.$route.params.id
      this.fetchData(this.id)
    }
  },
  methods: {
    fetchData(id) {
      fetchReestr(id).then(response => {
        if (response.data.status) {
          this.postForm = response.data.data
          this.setPageTitle()
        }
      }).catch(err => {
        // console.log(err)
      })
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
    getListRate() {
      fetchList({ type: 2 }).then(response => {
        this.rateList = response.data.data
      })
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
      updateBillingReestr(this.id, this.postForm).then(response => {
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
    },
    saveForm() {
      createBillingReestr(this.postForm).then(response => {
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
    },
  }
}
</script>

<style scoped>
  .createPost-main-container {
    padding: 40px 45px 20px 50px;
  }
  .createPost-container {
    position: relative;
  }

  .article-textarea /deep/ {
    textarea {
      padding-right: 40px;
      resize: none;
      border: none;
      border-radius: 0px;
      border-bottom: 1px solid #bfcbd9;
    }
  }

  @media screen and (max-width: 700px) {
    .createPost-main-container {
      padding: 40px 6px 20px 6px;
    }
  }
</style>
