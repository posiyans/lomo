<template>
  <div>
    <div v-if="authStore.is_guest" class="text-center q-pa-md text-weight-bold cursor-pointer">
      <router-link :to="{ name: 'UserLogin' }">
        Чтоб оставлять комментариии необходимо войти на сайт
      </router-link>
    </div>
    <div v-else-if="message.ban.value.loading" class="text-center q-py-sm">
      <q-spinner-facebook
        color="primary"
        size="2em"
      />
    </div>
    <div v-else-if="message.ban.value.status" class="text-red text-center q-pa-md text-weight-bold text-h6 cursor-pointer">
      <router-link :to="{ name: 'UserProfile' }">
        {{ message.ban.value.errorMessage }}
      </router-link>
    </div>
    <div v-else class="row items-center q-py-sm no-wrap">
      <div class="hidden">
        <input
          ref="fileRef"
          type="file"
          class="hidden"
          @change="change"
        >
      </div>
      <div class="q-pa-md">
        <UserAvatarByUid :uid="authStore.user.uid" size="30px" />
      </div>
      <div class="col">
        <div>
          <div v-if="message.newMessage.value.reply" class="row items-center">
            <div class="q-pa-sm text-primary">
              <q-icon name="reply" style="font-size: 2em;" />
            </div>
            <div class="">
              <div class="text-weight-bold text-primary q-mr-sm">
                {{ message.newMessage.value.reply.user.name }}
              </div>
              <div class="row no-wrap items-center">
                <div v-html="message.newMessage.value.reply.message" class="ellipsis" />
              </div>
            </div>
            <q-space />
            <div class="delete-btn q-px-md" @click="messageBlock.deleteReply">
              <q-icon name="close" />
            </div>
          </div>
          <q-input
            v-model="message.newMessage.value.message"
            autogrow
            dense
            outlined
            :maxlength="4000"
            :counter='message.newMessage.value.message.length > 3000'
            bg-color="white"
            :placeholder="placeholderInput"
          >
            <template v-slot:append>
              <q-icon v-if="file" flat name="attach_file" class="cursor-pointer" @click.stop="attacheFile" />
            </template>
          </q-input>
        </div>
        <div v-if="inputHint" class="row items-center q-pa-sm q-col-gutter-md">
          <div class="text-grey-7">
            {{ inputHint }}
          </div>
          <div class="delete-btn" @click="deleteFile">
            <q-icon name="highlight_off" />
          </div>
        </div>
      </div>
      <div class="">
        <q-btn flat icon="send" :loading="message.uploadMessage.value" :color="message.newMessage.value.message.length > 0 ? 'primary' : 'grey-6'" @click="messageBlock.sendComment" />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, ref } from 'vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    UserAvatarByUid
  },
  props: {
    file: {
      type: Boolean,
      default: false
    },
    messageBlock: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const message = props.messageBlock
    // const message = toRefs(props.messageBlock)
    const fileRef = ref(null)

    const authStore = useAuthStore()

    const placeholderInput = computed(() => {
      if (message.newMessage.value.file) {
        return 'Добавить описание к файлу'
      }
      return 'Написать комментарий...'
    })
    const inputHint = computed(() => {
      if (message.newMessage.value.file) {
        return message.newMessage.value.file.name
      }
      return ''
    })
    const deleteFile = () => {
      message.newMessage.value.file = null
    }
    const change = () => {
      if (fileRef.value.files[0]) {
        message.newMessage.value.file = fileRef.value.files[0]
      }
    }
    const attacheFile = () => {
      fileRef.value.click()
    }
    onMounted(() => {
      if (!authStore.user.email_verified_at) {
        authStore.getMyInfo(true)
      }
    })
    return {
      fileRef,
      inputHint,
      placeholderInput,
      deleteFile,
      attacheFile,
      authStore,
      change,
      message
    }
  }
})
</script>

<style scoped lang="scss">
.delete-btn {
  cursor: pointer;
  color: $grey;
  font-size: 1.5em;

  &:hover {
    color: $negative;
  }
}
</style>
