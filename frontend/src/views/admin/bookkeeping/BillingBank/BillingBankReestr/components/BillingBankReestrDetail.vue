<template>
  <div class="createPost-container">
    <el-form ref="reestrForm" :model="postForm" :rules="rules" :label-position="labelPosition" class="form-container" label-width="180px">
      <div class="pt1 createPost-main-container" style="padding-top: 0; padding-bottom: 0">
        <el-button v-loading="loading" type="success" @click="submitForm" />
        <el-button v-loading="loading" type="danger" @click="parseFile">
          Сбросить данные
        </el-button>
        <el-button v-loading="loading" type="primary" @click="close">
          Все платежи
        </el-button>
      </div>
      <div class="createPost-main-container billing-bank-reestr-table">
        <el-table
          :data="reestr.data.data"
          border
          style="width: 100%"
          :row-class-name="tableRowClassName"
        >
          <el-table-column
            v-for="i in sort"
            :key="i"
            :label="labelForm[i]"
            align="center"
          >
            <template slot-scope="{row}">
              <span>{{ row[`val`+ i] }}</span>
              <span v-if="i == 5">
                <span v-if="row.stead">
                  --> <el-tag :type="row | steadFilter" @click="editStead(row)">{{ row.stead.number }}</el-tag>
                </span>
                <span v-else>
                  --> <el-tag :type="row | steadFilter" @click="editStead(row)">---</el-tag>
                </span>
              </span>
            </template>
          </el-table-column>
          <el-table-column
            prop="date"
            label="Оплата"
            align="center"
          >
            <template slot-scope="{row}">
              <div v-if="row.dubl">
                <el-tag type="danger" effect="dark" @click="showDubl(row)">
                  Повтор
                </el-tag>
              </div>
              <div v-else>
                <el-tag type="danger" :effect="row.type | type1EffectFilter" @click="selectElect(row)">
                  <i v-if="row.type == 1" class="el-icon-check" />
                  Электоэнергия
                </el-tag>
                <el-tag type="success" :effect="row.type | type2EffectFilter" @click="row.type = 2">
                  <i v-if="row.type == 2" class="el-icon-check" />
                  Взносы
                </el-tag>
                <div v-if="row.type == 1">
                  <el-tag v-if="row.meterReading1">1-{{ row.meterReading1 }}</el-tag>
                  <el-tag v-if="row.meterReading2">2-{{ row.meterReading2 }}</el-tag>
                </div>
              </div>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-form>
    <div v-if="dialogSteadFormVisible">
      <el-dialog title="Уточнить участок" :visible.sync="dialogSteadFormVisible">
        <UserSteadFind :read_only="false" :stead_id="editRow.stead.id" @selectStead="setStead" />
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogSteadFormVisible = false">Отмена</el-button>
          <el-button type="primary" @click="confirmStead">Ок</el-button>
        </span>
      </el-dialog>
    </div>
    <div v-if="dialogDublFormVisible">
      <el-dialog title="Платеж уже есть в базе" :visible.sync="dialogDublFormVisible">
        <div>
          Занесено в базу {{ editRow.dubl.created_at | moment('DD-MM-YYYY HH:mm') }}
        </div>
        <div>
          Платеж № {{ editRow.dubl.id }} на сумму {{ editRow.dubl.price }} руб.<br>
          <el-tag type="success">Подробнее</el-tag>
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" @click="dialogDublFormVisible = false">Ок</el-button>
        </span>
      </el-dialog>
    </div>
    <div v-if="dialogMeterReadingFormVisible">
      <el-dialog title="Уточнить участок" :visible.sync="dialogMeterReadingFormVisible">
        <div class="mb2">{{ editRow.val7 }}</div>
        <el-form label-position="top">
          <el-form-item label="Показания день">
            <el-input
              v-model="editRow.meterReading1"
              placeholder="Показания день"
              prefix-icon="el-icon-search"
            />
          </el-form-item>
          <el-form-item label="Показания ночь">
            <el-input
              v-model="editRow.meterReading2"
              placeholder="Показания ночь"
              prefix-icon="el-icon-search"
            />
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogMeterReadingFormVisible = false">Cancel</el-button>
          <el-button type="primary" @click="dialogMeterReadingFormVisible = false">Confirm</el-button>
        </span>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { fetchBillingBankReestrInfo, updateBillingBankReestr, BillingBankReestrParse, publishBillingBankReestr } from '@/api/admin/billing'
import { searchUser } from '@/api/remote-search'
import { mapState } from 'vuex'
// import { Money } from 'v-money'
import { fetchList } from '@/api/rate'
// import readFileInputEventAsArrayBuffer from './readFileDoc'
import UserSteadFind from '@/components/UserSteadFind'

const defaultForm = {
  title: '',
  ratio_a: 0,
  ratio_b: 0
}

const labelForm = {
  0: 'Дата',
  1: 'Время',
  5: 'Участок',
  6: 'ФИО',
  7: 'Назначение',
  8: 'Сумма'
}

export default {
  filters: {
    type1EffectFilter(val) {
      if (val === 1) {
        return 'dark'
      }
      return 'plain'
    },
    type2EffectFilter(val) {
      if (val === 2) {
        return 'dark'
      }
      return 'plain'
    },
    steadFilter(val) {
      if (val.stead && val.val5 === val.stead.number) {
        return ''
      }
      return 'danger'
    }
  },
  // name: 'ArticleDetail',
  components: { UserSteadFind },
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
      // sort: [0, 1, 5, 6, 8, 7],
      sort: [0, 1, 5, 6, 8, 7],
      reestr: {
        data: ''
      },
      labelForm,
      editRow: {},
      dialogSteadFormVisible: false,
      dialogMeterReadingFormVisible: false,
      dialogDublFormVisible: false,
      rateList: [],
      tempStead: {},
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
    column() {
      return ''
    },
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
    // this.getListRate()
    // if (this.isEdit) {
    // const id = this.$route.params && this.$route.params.id
    // this.fetchData(id)
    // }

    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    // https://github.com/PanJiaChen/vue-element-admin/issues/1221
    this.tempRoute = Object.assign({}, this.$route)
  },
  methods: {
    close() {
      this.$emit('close')
    },
    tableRowClassName({ row, rowIndex }) {
      // console.log(row)
      if (row.dubl) {
        return 'warning-row'
      }
      if (row.error) {
        return 'warning-row'
      }
      return ''
    },
    publishForm() {
      publishBillingBankReestr({ reestr: this.reestr })
    },
    parseFile() {
      BillingBankReestrParse({ reestr_id: this.reestr.id }).then(response => {
        this.reestr = response.data
      })
    },
    showDubl(row) {
      this.editRow = row
      this.dialogDublFormVisible = true
    },
    selectElect(row) {
      row.type = 1
      this.editRow = row
      this.dialogMeterReadingFormVisible = true
    },
    confirmStead() {
      this.dialogSteadFormVisible = false
      if (this.tempStead && this.tempStead.id) {
        this.editRow.stead.id = this.tempStead.id
        this.editRow.stead.number = this.tempStead.number
        if (this.editRow.type) {
          this.editRow.error = false
        }
      }
    },
    setStead(stead) {
      this.tempStead = stead
      // this.editRow.stead.id = stead.id
      // this.editRow.stead.number = stead.number
    },
    editStead(row) {
      this.tempStead = false
      this.editRow = row
      this.dialogSteadFormVisible = true
    },
    // parseFile() {
    //   this.file = this.$refs.file.files[0]
    //   readFileInputEventAsArrayBuffer(this.file, arrayBuffer => {
    //     console.log(arrayBuffer)
    //     // mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
    //     //   .then(result => {
    //     //     this.text = result.value
    //     //     this.strToarray(result.value)
    //     //     this.parserOk = true
    //     //     this.parserButton = true
    //     //   })
    //   })
    // },
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
      fetchBillingBankReestrInfo({ id: id }).then(response => {
        if (response.data.status) {
          this.reestr = response.data.data
          console.log(this.reestr)
        }
      })

      // fetchArticle(id).then(response => {
      //   this.postForm = response.data
      //   // this.postForm.allow_comments = Boolean(response.data.allow_comments)
      //   this.datetime = this.$moment(this.postForm.publish_time)
      //   this.setTagsViewTitle()
      //
      //   // set page title
      //   this.setPageTitle()
      // }).catch(err => {
      //   // console.log(err)
      // })
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
      console.log(this.rate_checked.rate.ratio_a)
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
      updateBillingBankReestr({ reestr: this.reestr })
      // if (this.exampleRate > 0) {
      //   this.$refs['reestrForm'].validate((valid) => {
      //     if (valid) {
      //       this.saveForm()
      //     } else {
      //       this.$message.error('Введите описание!!!')
      //     }
      //   })
      // } else {
      //
      //   this.$message.error('Ничего не начисленно!!!')
      // }
      // this.postForm.public = true
      // this.saveForm()
    },
    saveForm() {
      // createBillingReestr(this.postForm)
      // this.$notify({
      //   title: 'успех',
      //   message: 'Статья успешно опубликована',
      //   type: 'success',
      //   duration: 2000
      // })

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

.billing-bank-reestr-table >>> .warning-row {
  background: #fff1f1;
}

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
