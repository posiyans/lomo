<template>
  <div style="padding: 25px; background-color: white; min-height: 100%">
    <div style="font-size: 1.5rem; font-weight: 600;">
      Предложить запись
    </div>
    <UserAddNewsForm v-model="article" class="q-mb-md" />
    <AddFileBtn :disabled="article.files.length >= maxFiles" @add-file="addFile" />
    <small> До {{ maxFiles }} файлов, размером до {{ maxSize }} Мб</small>
    <FilesListShow v-model="article.files" edit />
    <AddArticleBtn :article="article" />
  </div>
</template>

<script>
import UserAddNewsForm from '@/Modules/Article/Article/components/UserAddNewsForm'
import AddFileBtn from '@/Modules/Files/components/AddFileBtn'
import FilesListShow from '@/Modules/Files/components/FilesListShow'
import AddArticleBtn from '@/Modules/Article/Article/components/AddArticleBtn'
import { uid } from '@/utils/quasar'

export default {
  components: {
    UserAddNewsForm,
    AddFileBtn,
    AddArticleBtn,
    FilesListShow
  },
  data() {
    return {
      maxFiles: 10,
      maxSize: 20,
      article: {
        uid: uid(),
        title: '',
        category_id: 17,
        text: '',
        files: []

      },
      files: []
    }
  },
  methods: {
    addFile(val) {
      if (val.size < this.maxSize * 1024 * 1024) {
        if (this.article.files.length < this.maxFiles) {
          this.article.files.push(val)
        }
      } else {
        this.$alert('Файл должен быть размером меньше 20Мб', 'Ошибка', {
          confirmButtonText: 'OK',
          callback: action => {
          }
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
