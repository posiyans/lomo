<template>
  <div class="bg-grey-2 q-pa-lg">
    <div class="row items-center q-gutter-md justify-center">
      <CardBlock
        label="Комментарии"
        :count-label="countComments"
        icon="question_answer"
        color="purple"
        class="bg-white card-panel"
        @click="$router.push('/admin/comment/list')"
      />
      <CardBlock
        label="На модерации"
        :count-label="countArticle"
        icon="article"
        color="teal"
        class="bg-white card-panel"
        @click="$router.push('/admin/article/list?status=2')"
      />
      <CardBlock label="Обращенией" :count-label="countAppealOpen + '(' + countAppeal + ')'" color="cyan" icon="message" class="bg-white card-panel"
                 @click="$router.push('/admin/appeal/list')"
      />
      <CardBlock label="Пользователей" :count-label="countUser" color="blue" icon="people_alt" class="bg-white card-panel"
                 @click="$router.push('/admin/user/list')"

      />
      <CardBlock label="Участков" :count-label="countStead" icon="dashboard" class="bg-white card-panel"
                 @click="$router.push('/admin/stead/list')"
      />
      <CardBlock label="Собственники" :count-label="countOwner" icon="dashboard" color="deep-orange" class="bg-white card-panel"
                 @click="$router.push('/admin/owner/list')"
      />
    </div>
  </div>
</template>

<script>
import { fetchAppelList } from 'src/Modules/Appeal/api/appeal-admin-api.js'
import { fetchList } from 'src/Modules/Users/api/user-admin-api.js'
import { fetchAdminArticleList } from 'src/Modules/Article/Article/api/articleAdminApi.js'
import CardBlock from './components/CardBlock/index.vue'
import { getSteadsList } from 'src/Modules/Stead/api/stead'
import { getAllMessage } from 'src/Modules/Comments/api/commentApi'
import { fetchOwnerUserList } from 'src/Modules/Owner/api/ownerUserApi'

export default {
  components: {
    CardBlock
  },
  data() {
    return {
      countArticle: null,
      countUser: null,
      countAppeal: 0,
      countAppealOpen: 0,
      countStead: 0,
      countComments: 0,
      countOwner: 0
    }
  },
  mounted() {
    this.getArticle()
    this.getUser()
    this.getAppels()
    this.getStead()
    this.getComments()
    this.getOwners()
  },
  methods: {
    getArticle() {
      const data = {
        page: 1,
        limit: 1,
        status: 3
      }
      fetchAdminArticleList(data)
        .then(res => {
          this.countArticle = res.data.meta.total
        })
    },
    getUser() {
      fetchList()
        .then(response => {
          this.countUser = response.data.meta.total
        })
    },
    getStead() {
      getSteadsList()
        .then(response => {
          this.countStead = response.data.data.length
        })
    },
    getComments() {
      getAllMessage({ page: 1, limit: 1 })
        .then(response => {
          this.countComments = response.data.meta.total
        })
    },
    getOwners() {
      fetchOwnerUserList({ page: 1, limit: 1 })
        .then(response => {
          this.countOwner = response.data.meta.total
        })
    },
    getAppels() {
      fetchAppelList().then(response => {
        this.countAppeal = response.data.meta.total
      })
      const data = {
        status: 'open'
      }
      fetchAppelList(data).then(response => {
        this.countAppealOpen = response.data.meta.total
      })
    },
    handleSetLineChartData(type) {
      this.$emit('handleSetLineChartData', type)
    }
  }
}
</script>

<style lang="scss" scoped>
.card-panel {
  min-height: 108px;
  cursor: pointer;
  overflow: hidden;
  line-height: 18px;
  color: rgba(0, 0, 0, 0.45);
  font-size: 16px;
}

@media (max-width: 550px) {
  .card-panel-description {
    display: none;
  }

  .card-panel-icon-wrapper {
    float: none !important;
    width: 100%;
    height: 100%;
    margin: 0 !important;

    .svg-icon {
      display: block;
      margin: 14px auto !important;
      float: none !important;
    }
  }
}
</style>
