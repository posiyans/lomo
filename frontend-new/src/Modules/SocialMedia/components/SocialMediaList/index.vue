<template>
  <div>
    <q-list bordered separator>
      <q-item clickable v-ripple>
        <q-item-section>
          <div class="row q-col-gutter-md items-center">
            <div>
              Вконтакте
            </div>
            <LoginByVkBtn :disable="!!listObject.vkontakte" />
            <div v-if="loading">
              <q-spinner-dots
                color="primary"
                size="2em"
              />
            </div>
            <div v-if="!loading && listObject.vkontakte" class="text-weight-bold text-secondary">
              <q-icon name="done" size="2em" />
            </div>
          </div>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, ref } from 'vue'
import LoginByVkBtn from 'src/Modules/SocialMedia/vendors/Vk/components/LoginByVkBtn/index.vue'
import { getSocialMedia } from 'src/Modules/SocialMedia/api/apiSocialMedia'

export default defineComponent({
  components: {
    LoginByVkBtn
  },
  props: {
    userUid: {
      type: String,
      default: null
    }
  },
  setup(props, { emit }) {
    const list = ref([])
    const loading = ref(false)
    const listObject = computed(() => {
      const tmp = {}
      list.value.forEach(item => {
        tmp[item.provider_name] = item.provider_id
      })
      return tmp
    })
    const getData = () => {
      loading.value = true
      const data = {
        user_uid: props.userUid
      }
      getSocialMedia(data)
        .then(res => {
          list.value = res.data.data
        })
        .finally(() => {
          loading.value = false
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      loading,
      listObject
    }
  }
})
</script>

<style scoped>

</style>
