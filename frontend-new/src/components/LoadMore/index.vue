<template>
  <div>
    <div v-if="loading" class="text-center">
      <q-spinner-facebook
        color="primary"
        size="4em"
      />
    </div>
    <div v-else>
      <q-btn
        v-if="showBtn"
        class="full-width bg-grey-2 text-primary"
        :loading="listLoading"
        no-caps
        :label="loadMore"
        @click="add"
      />
      <pagination
        v-show="total > 0"
        :total="total"
        v-model:page="currentPage"
        v-model:limit="pageLimit"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, onUnmounted, ref, watch } from 'vue'
import Pagination from 'src/components/Pagination/index.vue'
import { scrollTo } from 'src/utils/scroll-to'
import { useRoute, useRouter } from 'vue-router'

export default defineComponent({
  components: {
    Pagination
  },
  props: {
    changeUrl: {
      type: Boolean,
      default: false
    },
    listQuery: { type: Object },
    func: { type: Function }
  },
  setup(props, { emit }) {
    const timer = ref(null)
    const timer2 = ref(null)
    const data = ref(false)
    const addList = ref(false)
    const total = ref(2)
    const offset = ref(0)
    const router = useRouter()
    const route = useRoute()
    const autoScroll = ref(true)
    const showCount = computed(() => {
      return props.listQuery.page * props.listQuery.limit
    })
    const list = ref([])
    const loading = ref(true)
    const listLoading = ref(false)
    const currentPage = computed({
      get() {
        return props.listQuery.page
      },
      set(val) {
        const tmp = Object.assign({}, props.listQuery)
        tmp.page = val
        emit('update:listQuery', tmp)
      }
    })
    const pageLimit = computed({
      get() {
        return props.listQuery.limit
      },
      set(val) {
        const tmp = Object.assign({}, props.listQuery)
        tmp.limit = val
        tmp.page = 1
        emit('update:listQuery', tmp)
      }
    })
    const showBtn = computed(() => {
      return total.value > showCount.value
    })
    const loadMore = computed(() => {
      if (total.value > showCount.value) {
        return 'Показать еще'
      }
      return ''
    })
    const changeUri = () => {
      if (props.changeUrl) {
        const oldQuery = route.query
        const query = Object.assign(Object.assign({}, oldQuery), props.listQuery)
        router.replace({ path: route.path, query })
      }
    }
    const getList = () => {
      listLoading.value = true
      emit('loading', true)
      const tmp = Object.assign({}, props.listQuery)
      if (addList.value) {
        tmp.page = +tmp.page + addList.value
      }
      props.func(tmp)
        .then(response => {
          total.value = response.data.meta.total || response.data.total || 0
          offset.value = response.data.meta.offset || response.data.offset || 0
          if (!addList.value) {
            list.value = []
          }
          response.data.data.forEach(val => {
            list.value.push(val)
          })
          emit('setList', list.value)
          emit('setOffset', offset.value)
        })
        .catch(er => {
          total.value = 0
          offset.value = 0
          list.value = []
          const errorMessage = er.response.data.error || er.response.data.errors || er.response.data.data
          emit('error', errorMessage)
        })
        .finally(() => {
          loading.value = false
          listLoading.value = false
          emit('loading', false)
          if (autoScroll.value) {
            scrollTo(0, 500)
          }
          autoScroll.value = true
        })
    }
    const add = () => {
      autoScroll.value = false
      addList.value = +addList.value + 1
      getList()
    }
    const deepEqual = (obj1, obj2) => {
      for (const propName in obj1) {
        if (!obj2.hasOwnProperty(propName)) { // Есть ли свойства в обоих объектах
          return false
        }
        if (obj1[propName] && obj1[propName].toString() !== obj2[propName].toString()) { // проверка объекта в объекте
          return false
        }
      }
      return true
    }
    watch(
      () => props.listQuery,
      () => {
        if (timer.value) {
          clearTimeout(timer.value)
        }
        timer.value = setTimeout(() => {
          changeUri()
          addList.value = false
          getList()
        }, 500)
      }
    )
    watch(
      props.listQuery,
      () => {
        if (timer.value) {
          clearTimeout(timer.value)
        }
        timer.value = setTimeout(() => {
          changeUri()
          addList.value = false
          getList()
        }, 500)
      }
    )
    watch(
      route,
      () => {
        if (!deepEqual(props.listQuery, route.query)) {
          setDefaultParams()
        }
      }
    )
    const setDefaultParams = () => {
      const tmp = Object.assign({}, props.listQuery)
      let update = false
      for (const key in tmp) {
        if (!route.query[key] || route.query[key].toString() !== props.listQuery[key].toString()) {
          update = true
        }
        tmp[key] = route.query[key] || props.listQuery[key]
      }
      if (update) {
        emit('update:listQuery', tmp)
      }
      return update
    }
    onMounted(() => {
      timer2.value = setTimeout(() => {
        if (!setDefaultParams()) {
          getList()
        }
      }, 200)
    })
    onUnmounted(() => {
      if (timer.value) {
        clearTimeout(timer.value)
      }
      if (timer2.value) {
        clearTimeout(timer2.value)
      }
    })
    return {
      data,
      add,
      pageLimit,
      loading,
      loadMore,
      showBtn,
      listLoading,
      currentPage,
      total
    }
  }
})
</script>

<style scoped lang='scss'>

</style>