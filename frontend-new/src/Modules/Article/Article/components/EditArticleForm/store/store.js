import { defineStore } from 'pinia'

export const useEditArticleStore = defineStore('editStore', {
  state: () => ({
    article: {
      public: false,
      title: '',
      text: '',
      resume: '',
      files: [],
      news: 1,
      id: undefined,
      category_id: '',
      allow_comments: 1,
      uid: ''
    }
  }),
  getters: {
  },
  actions: {
    increment () {
      this.counter++
    }
  }
})
