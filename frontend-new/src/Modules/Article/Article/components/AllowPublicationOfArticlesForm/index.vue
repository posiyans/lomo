<template>
  <div>
    <q-select
      outlined
      :loading="loading"
      v-model="allowPublic"
      :options="options"
      label="Предлагать статьи"
      :disable="!writeMode"
      map-options
      emit-value
      @update:model-value="changeValue"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { changeAllowPublicationArticle, getAllowPublicationArticle } from 'src/Modules/Article/Article/api/article'
import { errorMessage } from 'src/utils/message'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const allowPublic = ref(1)
    const loading = ref(false)
    const options = [
      {
        label: 'Отключенно',
        value: 1
      },
      {
        label: 'Только собственники',
        value: 2
      },
      {
        label: 'Все пользователи',
        value: 3
      }
    ]
    const authStore = useAuthStore()
    const writeMode = computed(() => {
      return authStore.checkPermission(['article-edit'])
    })
    const changeValue = () => {
      loading.value = true
      const data = {
        value: allowPublic.value
      }
      changeAllowPublicationArticle(data)
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
        .finally(() => {
          loading.value = false
        })
    }

    onMounted(() => {
      loading.value = true
      getAllowPublicationArticle({ admin: 1 })
        .then(res => {
          allowPublic.value = res.data.data
        })
        .finally(() => {
          loading.value = false
        })
    })
    return {
      allowPublic,
      loading,
      writeMode,
      changeValue,
      options
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
