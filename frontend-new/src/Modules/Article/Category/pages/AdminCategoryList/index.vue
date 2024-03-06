<template>
  <div class="q-pa-sm">
    <div class="text-h6 q-pa-sm row items-center q-col-gutter-md">
      <div>
        Разделы статей
      </div>
      <AddCategoryBtn v-if="showSettingBtn" @success="getData" />
    </div>
    <q-list bordered class="rounded-borders" style="">
      <template v-for="(item, index) in list" :key="item.id">
        <q-item>
          <q-item-section avatar top>
            <q-icon name="subject" color="black" size="28px" />
          </q-item-section>
          <q-item-section top class="col-2">
            <q-item-label class="q-mt-sm cursor-pointer">
              <router-link :to="`/article/list/${item.id}`">
                {{ item.name }}
              </router-link>
              <q-btn v-if="item.public" class="gt-xs" size="12px" color="secondary" flat dense round icon="people_alt" />
              <q-btn v-if="item.default" class="gt-xs" size="12px" color="primary" flat dense round icon="home" />
            </q-item-label>
          </q-item-section>

          <q-item-section top>
            <q-item-label caption lines="2">
              {{ item.description }}
            </q-item-label>
            <q-item-label caption lines="1">
              {{ `/article/list/${item.id}` }}
            </q-item-label>
          </q-item-section>
          <q-item-section v-if="showSettingBtn" top side>
            <div class="text-grey-8">
              <CategorySettingDialogBtn :item="item" @success="getData" />
            </div>
          </q-item-section>
        </q-item>
        <q-separator spaced v-if="list.length - 1 > index" />
      </template>
    </q-list>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { fetchCategoryList } from 'src/Modules/Article/Category/api/category'
import CategorySettingDialogBtn from 'src/Modules/Article/Category/components/CategorySettingDialog/ShowBtn.vue'
import AddCategoryBtn from 'src/Modules/Article/Category/components/AddCategoryBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    CategorySettingDialogBtn,
    AddCategoryBtn
  },
  props: {},
  setup(props, { emit }) {
    const list = ref([])
    const authStore = useAuthStore()
    const showSettingBtn = computed(() => {
      return authStore.checkPermission(['article-edit'])
    })
    const getData = () => {
      const data = {
        markDefault: true
      }
      fetchCategoryList(data)
        .then(res => {
          list.value = res.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      getData,
      showSettingBtn,
      list
    }
  }
})
</script>

<style scoped>

</style>
