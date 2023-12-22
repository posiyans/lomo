<template>
  <div v-if="item">
    <div class="row no-wrap relative-position" :class="{ 'reply-block': reply, 'cursor-pointer': reply }">
      <div v-if="fullView" class="q-px-sm">
        <div class="text-center q-pt-sm">
          <UserAvatarByUid :uid="item.user.uid" size="40px" />
        </div>
      </div>
      <div class="col">
        <div class="row items-center q-col-gutter-sm">
          <div class="text-weight-bold text-primary">
            {{ item.user.name }}
          </div>
          <ShowTime v-if="fullView" :time="item.updated_at" class="text-grey-8" style="font-size: 0.9em" />
          <slot name="header"></slot>
        </div>
        <slot></slot>
        <div v-html="messageTextHtml"
             :class=" { 'q-py-sm': fullView, 'text-strike' : item.delete, 'text-grey':  item.delete, ellipsis: reply, 'reply-block__message': reply, 'message-block__message': fullView }" />
        <FilesListShow v-if="!item.delete" :model-value="item.files" show-preview />
      </div>
      <div class="q-px-sm absolute-bottom-right text-grey-8 row ">
        <div v-if="edit" class="q-pr-sm">
          ред.
        </div>
      </div>
    </div>

  </div>
  <div v-else>
    <div class="text-grey reply-block">
      Сообщение удалено
    </div>
  </div>

</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import { deleteMessage } from 'src/Modules/Comments/api/commentApi.js'
import ShowTime from 'src/components/ShowTime/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useQuasar } from 'quasar'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'

export default defineComponent({
  components: {
    UserAvatarByUid,
    FilesListShow,
    ShowTime
  },
  props: {
    item: {
      type: Object,
      required: true
    },
    reply: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const $q = useQuasar()
    const edit = computed(() => {
      return props.item.created_at !== props.item.updated_at
    })
    const fullView = computed(() => {
      return !props.reply
    })
    const messageTextHtml = computed(() => {
      if (props.item.message) {
        // return props.item.message.replace(/\n/g, '<br />').replace(/(http:\/\/[.\w/=&?]+)/gi, "<a href='$1' class='text-primary'>$1</a>")
        return props.item.message.replace(/\n/g, '<br />').replace(/([-a-zA-Z0-9@:%_\+.~#?&\/\/=]{2,256}\.[a-zA-Zа-яА-ЯёЁ]{2,4}\b(\/?[-a-zA-Z0-9а-яА-ЯёЁ@:%_\+.~#?&\/\/=]*)?)/gi, "<a href='$1' class='text-primary' target='_blank'>$1</a>")
      }
      return ''
    })
    const authStore = useAuthStore()
    const deleteAccess = computed(() => {
      return authStore.user.uid === props.item.user.uid
    })
    const replyMessage = () => {
      emit('reply')
    }
    const deleteItem = () => {
      $q.dialog({
        title: 'Внимание',
        message: 'Удалить комментарий?',
        ok: {
          label: 'Удалить',
          color: 'negative'
        },
        cancel: true,
        persistent: true
      }).onOk(() => {
        props.item.delete = true
        const data = {
          uid: props.item.uid
        }
        deleteMessage(data)
          .then(response => {
            emit('reload')
          })
      })
    }
    onMounted(() => {

    })
    return {
      fullView,
      replyMessage,
      deleteItem,
      deleteAccess,
      data,
      messageTextHtml,
      edit
    }
  }
})
</script>

<style scoped lang="scss">
.reply-block {
  border-left-style: solid;
  border-left-width: 4px;
  border-left-color: $grey-5;
  padding-left: 5px;
  color: $grey-8;


  &:hover {
    border-left-color: $grey;
  }
}

.reply-block__message {
  max-width: 70%;
  height: 1.5em;
}

</style>
