<template>
  <div class="createPost-container">
    <div class="ml4-ns">
      <el-button @click="$router.push('/bookkeping/billing_reestr')">Начисления</el-button>
    </div>
    <el-form ref="reestrForm" :model="postForm" :rules="rules" :label-position="labelPosition" class="form-container" label-width="180px">
      <div class="pt1 createPost-main-container" style="padding-top: 0; padding-bottom: 0">
        <div style="display: inline-block; padding-right: 10px; padding-top: 20px;">
          <b>Сделать нацислеения всем садоводам</b>
        </div>
        <div v-if="isEdit" style="display: inline-block;">
          <el-button v-loading="loading" type="danger" @click="submitForm">
            Пересчитать
          </el-button>
        </div>
        <div v-else style="display: inline-block;">
          <el-button v-loading="loading" type="danger" @click="submitForm">
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
            :value="item"
          />
        </el-select>
        <el-form-item style="margin-bottom: 10px;" prop="title" label="Описание">
          <el-input v-model="postForm.title" placeholder="На что и за какой период делается начисление" />
        </el-form-item>
        <el-form-item label="Начислить на 1 сотку:">
          <money v-model="postForm.ratio_a" placeholder="Начисление на 1 сотку участка" v-bind="money_a" />
        </el-form-item>
        <el-form-item label="Начислить на 1 участок:">
          <money v-model="postForm.ratio_b" placeholder="Начисление на 1 участок, внезависимости от площади" v-bind="money_b" />
        </el-form-item>

        <div style="color: #1b5fab;">
          Например на 1 участок площадью 6 соток будет начисленно {{ exampleRate }} рублей
        </div>
      </div>
    </el-form>
    {{ postForm }}
  </div>
</template>

<script>
import { fetchArticle } from '@/api/article'
import { createBillingReestr } from '@/api/admin/billing'
import { searchUser } from '@/api/remote-search'
import { mapState } from 'vuex'
import { Money } from 'v-money'
import { fetchList } from '@/api/rate'

const defaultForm = {
  title: '',
  ratio_a: 0,
  ratio_b: 0
}

export default {
  name: 'ArticleDetail',
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
      userListOptions: [],
      rules: {
        // image_uri: [{ validator: validateRequire }],
        title: [{ validator: validateRequire }]
        // text: [{ validator: validateRequire }],
      },
      tempRoute: {}
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    }),
    exampleRate() {
      return (6 * this.postForm.ratio_a + this.postForm.ratio_b).toFixed(2)
    },
    labelPosition() {
      return this.device === 'desktop' ? 'left' : 'top'
    },
    contentShortLength() {
      return this.postForm.resume.length
    },
    displayTime: {
      // set and get is useful when the data
      // returned by the back end api is different from the front end
      // back end return => "2013-06-25 06:59:25"
      // front end need timestamp => 1372114765000
      get() {
        return (+new Date(this.datetime))
      },
      set(val) {
        this.datetime = new Date(val)
      }
    }
  },
  created() {
    this.getListRate()
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id
      this.fetchData(id)
    }

    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    // https://github.com/PanJiaChen/vue-element-admin/issues/1221
    this.tempRoute = Object.assign({}, this.$route)
  },
  methods: {
    create_UUID() {
      var dt = new Date().getTime()
      var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random() * 16) % 16 | 0
        dt = Math.floor(dt / 16)
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16)
      })
      return uuid
    },
    fetchData(id) {
      fetchArticle(id).then(response => {
        this.postForm = response.data
        // this.postForm.allow_comments = Boolean(response.data.allow_comments)
        this.datetime = this.$moment(this.postForm.publish_time)
        this.setTagsViewTitle()

        // set page title
        this.setPageTitle()
      }).catch(() => {
        // console.log(err)
      })
    },
    setTagsViewTitle() {
      const title = 'Статья id'
      const route = Object.assign({}, this.tempRoute, { title: `${title}-${this.postForm.id}` })
      this.$store.dispatch('tagsView/updateVisitedView', route)
    },
    setPageTitle() {
      const title = 'Статья id'
      document.title = `${title} - ${this.postForm.id}`
    },
    changeRate() {
      this.postForm.title = this.rate_checked.description + ' ' + new Date().getFullYear() + ' год'
      // console.log(this.rate_checked.rate.ratio_a)
      this.postForm.ratio_a = +this.rate_checked.rate.ratio_a
      // this.postForm.ratio_a = 2
      this.postForm.ratio_b = +this.rate_checked.rate.ratio_b
      // this.postForm.ratio_b = 3
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
            this.saveForm()
          } else {
            this.$message.error('Введите описание!!!')
          }
        })
      } else {
        this.$message.error('Ничего не начисленно!!!')
      }
      // this.postForm.public = true
      // this.saveForm()
    },
    saveForm() {
      createBillingReestr(this.postForm)
      this.$notify({
        title: 'успех',
        message: 'Статья успешно опубликована',
        type: 'success',
        duration: 2000
      })
    },
    draftForm() {
      // this.postForm.public = false
      this.saveForm()
    },
    getRemoteUserList(query) {
      searchUser(query).then(response => {
        if (!response.data.items) return
        this.userListOptions = response.data.items.map(v => v.name)
      })
    }
  }
}
</script>

<style scoped>
  .createPost-main-container {
    padding: 40px 45px 20px 50px;
  }
  .createPost-container {
    position: relative;
    .postInfo-container {
      position: relative;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
    .word-counter {
      width: 100px;
      position: absolute;
      right: 10px;
      top: 0px;
    }
  }

  .article-textarea >>> {
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

      .postInfo-container {
        position: relative;
        margin-bottom: 10px;

        .postInfo-container-item {
          float: left;
        }
      }
    }
  }
</style>
