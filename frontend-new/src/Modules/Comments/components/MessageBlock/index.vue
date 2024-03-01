<template>
  <div>
    <div v-for="item in list.showMessage.value" :key="item.id" class="q-mb-sm relative-position">
      <ItemBlock
        :ref="val => refBlock[item.uid] = val"
        :item="item"
        @reload="getData"
        @scroll="scrollTo"
      >
        <ItemBlock
          reply
          v-if="item.options?.reply"
          :item="refBlock[item.options.reply]?.item"
          @click="scrollTo(item.options.reply)"
        />
        <template v-slot:header>
          <div v-if="item.actions.write && !list.ban.value.status" class="row no-wrap">
            <div class="cursor-pointer message-btn message-btn_reply q-px-xs" @click="messageBlock.replyMessage(item)">
              <q-icon name="reply">
                <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                  <strong>Ответить</strong>
                </q-tooltip>
              </q-icon>
            </div>
            <div v-if="item.actions.delete" class="cursor-pointer message-btn message-btn_delete  q-px-xs" @click="deleteItem(item)">
              <q-icon name="close">
                <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                  <strong>Удалить</strong>
                </q-tooltip>
              </q-icon>
            </div>
            <AddBanUserBtn v-if="item.actions.ban" :user-uid="item.user.uid" :type="messageBlock.objectType" :object-uid="messageBlock.objectUid">
              <div class="cursor-pointer message-btn message-btn_delete  q-px-xs">
                <q-icon name="block">
                  <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                    <span>Бан пользователя</span>
                  </q-tooltip>
                </q-icon>
              </div>
            </AddBanUserBtn>
          </div>
        </template>
      </ItemBlock>
      <q-separator class="q-mt-md" />
    </div>
    <div v-if="list.showMessage.value.length < list.messageList.value.length" class="q-px-lg text-primary cursor-pointer" @click="messageBlock.nextMessage">
      Показать следующие...
    </div>
    <div v-if="list.messageLoading.value" class="text-center text-primary q-pa-sm text-weight-bold">
      Загрузка ...
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref, watch } from 'vue'
import { deleteMessage } from 'src/Modules/Comments/api/commentApi.js'
import ItemBlock from './components/ItemBlock/index.vue'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import AddBanUserBtn from 'src/Modules/BanUsers/components/AddBanUserBtn/index.vue'

export default defineComponent({
  components: {
    ItemBlock,
    AddBanUserBtn
  },
  props: {
    messageBlock: {
      type: Object,
      required: true
    },
    noBan: {
      type: Boolean,
      default: false
    },
    noDelete: {
      type: Boolean,
      default: false
    },
    noRepeat: {
      type: Boolean,
      default: false
    },
  },
  setup(props, { emit }) {
    // const list = toRefs(props.messageBlock)
    const list = props.messageBlock
    const refBlock = ref({})
    const $q = useQuasar()
    const authStore = useAuthStore()
    const showBanBtn = computed(() => {
      return !props.noBan && authStore.permissions.includes('comment-ban')
    })
    const deleteAccess = computed(() => {
      return (item) => {
        if (props.noDelete) {
          return false
        }
        if (authStore.user.uid === item.user.uid) {
          return true
        }
        return authStore.permissions.includes('comment-delete')
      }
    })
    const replyMessage = (item) => {
      list.newMessage.reply = item
      window.scrollTo(0, document.body.scrollHeight);
    }
    onMounted(() => {
      getData()
    })
    watch(
      () => props.reload,
      () => getData()
    )
    const getData = () => {
      list.key.value++
    }
    const deleteItem = (item) => {
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
        item.delete = true
        const data = {
          uid: item.uid
        }
        deleteMessage(data)
          .then(response => {
            getData()
          })
          .catch(er => {
            item.delete = false
            $q.dialog({
              title: 'Ошибка удаления',
              message: er.response.data.errors,
              persistent: true
            })
          })
      })
    }
    const scrollTo = (commentUid) => {
      if (refBlock.value[commentUid]) {
        const comment = refBlock.value[commentUid].$el
        window.scrollTo({
          behavior: 'smooth',
          top:
            comment.getBoundingClientRect().top -
            document.body.getBoundingClientRect().top -
            120,
        })
        comment.classList.add("bg-grey-4")
        setTimeout(() => {
          comment.classList.remove("bg-grey-4")
        }, 3000)
      } else {
        console.log('element id: ' + commentUid + ' no found')
      }
    }
    return {
      authStore,
      showBanBtn,
      deleteAccess,
      deleteItem,
      getData,
      scrollTo,
      refBlock,
      replyMessage,
      list
    }
  }
})
</script>

<style scoped lang="scss">
.message-btn {
  color: $grey;
  font-size: 1em;
}

.message-btn_delete {
  &:hover {
    color: $negative;
  }
}

.message-btn_reply {
  &:hover {
    color: $primary;
  }
}
</style>
