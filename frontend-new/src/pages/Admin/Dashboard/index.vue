<template>
  <div class="bg-grey-2 q-pa-lg">
    <div class="row items-center q-gutter-md justify-center">
      <transition-group
        appear
        enter-active-class="animated fadeInDown"
        leave-active-class="animated fadeOut"
      >
        <template v-for="item in cards">
          <CardBlock
            v-if="item.show"
            :key="item.label"
            :label="item.label"
            :count-label="item.value"
            :icon="item.icon"
            :color="item.color"
            class="bg-white card-panel"
            @click="router.push(item.clickAction)"
          />
        </template>
      </transition-group>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { fetchList } from 'src/Modules/Users/api/user-admin-api.js'
import { fetchAdminArticleList } from 'src/Modules/Article/Article/api/articleAdminApi.js'
import CardBlock from './components/CardBlock/index.vue'
import { getSteadsList } from 'src/Modules/Stead/api/stead'
import { getAllMessage } from 'src/Modules/Comments/api/commentApi'
import { fetchOwnerUserList } from 'src/Modules/Owner/api/ownerUserApi'
import { useRouter } from 'vue-router'
import { fetchAppealList } from 'src/Modules/Appeal/api/appealApi'


export default defineComponent({
  components: {
    CardBlock
  },
  props: {},
  setup(props, { emit }) {
    const router = useRouter()
    const cards = ref([
      {
        getData: () => {
          return getAllMessage({ page: 1, limit: 1 })
        },
        label: 'Комментарии',
        value: null,
        icon: 'question_answer',
        color: 'purple',
        clickAction: '/admin/comment/list',
        show: false
      },
      {
        getData: () => {
          const data = {
            page: 1,
            limit: 1,
            status: 3
          }
          return fetchAdminArticleList(data)
        },
        label: 'На модерации',
        value: null,
        icon: 'article',
        color: 'teal',
        clickAction: '/admin/article/list?status=3',
        show: false
      },
      {
        getData: () => {
          const data = {
            status: 'open',
            page: 1,
            limit: 1
          }
          return fetchAppealList(data)
        },
        label: 'Обращенией',
        value: null,
        icon: 'message',
        color: 'cyan',
        clickAction: '/admin/appeal/list',
        show: false
      },
      {
        getData: () => {
          return fetchList()
        },
        label: 'Пользователей',
        value: null,
        icon: 'people_alt',
        color: 'blue',
        clickAction: '/admin/user/list',
        show: false
      },
      {
        getData: () => {
          const data = {
            page: 1,
            limit: 1
          }
          return getSteadsList(data)
        },
        label: 'Участков',
        value: null,
        icon: 'dashboard',
        color: 'teal',
        clickAction: '/admin/stead/list',
        show: false
      },
      {
        getData: () => {
          const data = {
            page: 1,
            limit: 1
          }
          return fetchOwnerUserList(data)
        },
        label: 'Собственники',
        value: null,
        icon: 'dashboard',
        color: 'deep-orange',
        clickAction: '/admin/owner/list',
        show: false
      },
    ])
    onMounted(() => {
      cards.value.forEach(item => {
        item.getData()
          .then(res => {
            item.value = res.data.meta.total || 0
            item.show = true
          })
      })
    })
    return {
      cards,
      router
    }
  }
})
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
