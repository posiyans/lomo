import { defineStore } from 'pinia'
import { Notify, uid } from 'quasar'
import { userAddArticle } from 'src/Modules/Article/Article/api/article'
import { errorMessage } from 'src/utils/message'

export const useNewArticleStore = defineStore('newArticleStore', {
  state: () => ({
    article: {
      uid: uid(),
      title: '',
      category_id: '',
      text: '',
      files: []
    },
    loading: {
      upload: false
    }
  }),
  getters: {},
  actions: {
    clear() {
      this.article.uid = uid()
      this.article.title = ''
      this.article.text = ''
      this.article.files = []
    },
    async saveFiles() {
      for (const file of this.article.files) {
        await file.sendFileToServer()
        console.log('upload File')
      }
    },
    saveArticle(callback) {
      this.loading.upload = true
      const data = {
        title: this.article.title,
        text: this.article.text,
        category_id: this.article.category_id,
        uid: this.article.uid
      }
      userAddArticle(data)
        .then(async res => {
          await this.saveFiles()
          callback()
          this.clear()
          Notify.create({
            message: 'Данные успешно отправлены, после проверки она появится на сайте',
            color: 'secondary'
          })
        })
        .catch(er => {
          if (er.response.status === 403) {
            errorMessage('Вам запрещено предлагать записи')
          } else {
            errorMessage(er.response.data.errors)
          }
        })
        .finally(() => {
          this.loading.upload = false
        })
    }
  }
})
