<template>
  <div class="q-pa-md">
    <transition-group
      appear
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
    >
      <div v-for="item in commentsList.list.value" :key="item.id" class="relative-position">
        <ItemBlock :item="item" class="q-pb-xs">
          <template v-slot:header>
            <div>
              <router-link :to="item.parentObject.url" class="text-primary">
                для {{ item.parentObject.label }}
              </router-link>
            </div>
          </template>
        </ItemBlock>
        <q-separator />
        <div class="absolute-top-right row q-pr-lg">
          <div class="cursor-pointer message-btn message-btn_delete  q-px-xs" @click="deleteItem(item)">
            <q-icon name="close">
              <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                <strong>Удалить</strong>
              </q-tooltip>
            </q-icon>
          </div>
          <AddBanUserBtn :user-uid="item.user.uid" :type="item.parentObject.type" :object-uid="item.parentObject.uid">
            <div class="cursor-pointer message-btn message-btn_delete  q-px-xs">
              <q-icon name="block">
                <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">
                  <span>Бан пользователя</span>
                </q-tooltip>
              </q-icon>
            </div>
          </AddBanUserBtn>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ItemBlock from 'src/Modules/Comments/components/MessageBlock/components/ItemBlock/index.vue'
import { deleteMessage } from 'src/Modules/Comments/api/commentApi'
import { useQuasar } from 'quasar'
import AddBanUserBtn from 'src/Modules/BanUsers/components/AddBanUserBtn/index.vue'

export default defineComponent({
  components: {
    ItemBlock,
    AddBanUserBtn
  },
  props: {
    commentsList: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const $q = useQuasar()
    const list = props.commentsList.list.value
    // const list = props.commentsList.list.value
    const columns = ref(
      [
        {
          name: 'id',
          label: 'id',
          align: 'center',
        },
        {
          name: 'message',
          label: 'текс',
          align: 'left',
          sortable: true
        }
      ]
    )

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
          .catch(er => {
            item.delete = false
            $q.dialog({
              title: 'Ошибка удаления',
              message: er.response.data.errors,
              persistent: true
            })
          })
          .finally(() => {
            props.commentsList.listQuery.value.key++

          })
      })
    }

    onMounted(() => {

    })
    return {
      data,
      columns,
      deleteItem,
      list
    }
  }
})
</script>

<style scoped>

</style>
