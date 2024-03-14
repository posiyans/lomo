<template>
  <div v-if="item">
    <div class="row no-wrap" :class="{ 'reply-block': reply, 'cursor-pointer': reply }">
      <div class="col-grow" style="width: calc(100% - 65px)">
        <div class="row items-center q-col-gutter-sm no-wrap">
          <div class="row items-center">
            <div v-if="fullView" class="text-center q-pr-sm">
              <UserAvatarByUid :uid="item.user.uid" size="30px" />
            </div>
            <div>
              <div class="text-weight-bold text-primary ellipsis">
                {{ item.user.name }}
              </div>
              <ShowTime v-if="fullView" :time="item.updated_at" class="text-grey-8 text-small-75 ellipsis" />
            </div>
          </div>
          <div>
            <slot name="description"></slot>
          </div>
          <q-space />
          <div>
            <slot name="header"></slot>
          </div>
        </div>
        <slot></slot>
        <div v-if="messageTextHtml" v-html="messageTextHtml"
             class="q-py-sm"
             style="margin-left: 40px;"
             :class="messageTextClass" />
        <FilesListShow v-if="!item.delete" :model-value="item.files" default-view="image" />
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
import { computed, defineComponent, ref } from 'vue'
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
    const $q = useQuasar()
    const edit = computed(() => {
      return props.item.created_at !== props.item.updated_at
    })
    const fullView = computed(() => {
      return !props.reply
    })
    const messageTextClass = computed(() => {
      return {
        'text-strike': props.item.delete,
        'text-grey': props.item.delete,
        'reply-block__message': props.reply,
        'message-block__message': fullView.value
      }

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
    return {
      fullView,
      replyMessage,
      messageTextClass,
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
  padding-left: 8px;
  color: $grey-8;

  &:hover {
    border-left-color: $grey;
  }
}

.reply-block__message {
  max-width: 70vw;
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  height: 2.5em;
}

</style>
