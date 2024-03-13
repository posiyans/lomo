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
      <router-link :to="{ name: 'UserProfile', query: {tab: 'ban'}  }">
        {{ message.ban.value.errorMessage }}
      </router-link>
    </div>
    <div v-else class="row q-py-sm q-pl-sm no-wrap">
      <div class="q-pr-sm xs-hide q-pt-xs">
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
            v-model.trim="message.newMessage.value.message"
            autogrow
            dense
            outlined
            :maxlength="4000"
            :counter='message.newMessage.value.message.length > 3000'
            bg-color="white"
            :placeholder="placeholderInput"
          >
            <AddFileBtn v-if="file" parent-type="comment" multiple parent-uid="no-uid" @add-files="change">
              <q-icon flat name="attach_file" size="sm" class="cursor-pointer q-pt-xs text-grey-8" />
            </AddFileBtn>
          </q-input>
        </div>
        <div v-if="inputHint" class="q-pt-xs">
          <FilesListShow
            v-model="message.newMessage.value.files"
            edit
            class="row q-col-gutter-sm"
          />
        </div>
      </div>
      <div>
        <q-btn flat icon="send" :loading="message.uploadMessage.value" :color="message.messageSend.value ? 'primary' : 'grey-6'" @click="messageBlock.sendComment" />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, watch } from 'vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    UserAvatarByUid,
    AddFileBtn,
    FilesListShow
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
    const authStore = useAuthStore()

    const placeholderInput = computed(() => {
      if (message.newMessage.value.files.length > 0) {
        return 'Добавить описание'
      }
      return 'Написать комментарий...'
    })
    const inputHint = computed(() => {
      if (message.newMessage.value.files.length > 0) {
        return true
      }
      return false
    })
    const change = (files) => {
      if (files.length > 0) {
        files.forEach(file => {
          if (message.newMessage.value.files.length < 10) {
            message.newMessage.value.files.push(file)
          } else {
            errorMessage('Не более 10 файлов')
          }
        })
      }
    }
    watch(
      () => authStore.user,
      () => message.getStatusBan()
    )
    onMounted(() => {
      if (!authStore.user.email_verified_at) {
        authStore.getMyInfo(true)
      }
    })
    return {
      inputHint,
      placeholderInput,
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
