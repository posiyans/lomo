<template>
  <div>
    <div @click="showDialogAction">
      <slot>
        <q-btn icon="add" flat color="primary" />
      </slot>
    </div>
    <q-dialog v-model="showDialog" persistent>
      <q-card class="">
        <q-card-section class="row items-center q-px-lg q-col-gutter-md">
          <div>
            <q-avatar icon="close" color="negative" text-color="white" />
          </div>
          <div class="q-ml-sm">{{ dialogText }}</div>
        </q-card-section>
        <q-card-section class="q-gutter-sm">
          <div>
            <q-input v-model="description" autogrow outlined label="Причина бана" />
          </div>
          <div>
            <q-input :disable="permanent" outlined v-model="time" label="Время окончания бана">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date
                      v-model="time"
                      mask="YYYY-MM-DD HH:mm"
                      :options="optionsFn"
                    >
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>

              <template v-slot:append>
                <q-icon name="access_time" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-time v-model="time" mask="YYYY-MM-DD HH:mm" format24h>
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>
          <div>
            <q-checkbox v-model="permanent" label="Забанить навсегда" />
          </div>
          <div>
            <q-checkbox v-model="onlyCurrentItem" label="Запретить только в данном месте " />
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Отмена" color="negative" v-close-popup />
          <q-btn label="OK" color="primary" @click="addBanAction" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { computed, defineComponent, ref } from 'vue'
import { addUserBan } from 'src/Modules/BanUsers/api/apiBanUser.js'
import { date, useQuasar } from 'quasar'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {},
  props: {
    userUid: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'all'
    },
    objectUid: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const description = ref('')
    const permanent = ref(true)
    const onlyCurrentItem = ref(false)
    const time = ref('')
    const showDialog = ref(null)
    const $q = useQuasar()
    const dialogText = computed(() => {
      if (props.type === 'comment') {
        return 'Запретить пользователю оставлять комментарии?'
      } else {
        return 'Отправить пользователя в бан?'
      }
    })
    const showDialogAction = () => {
      showDialog.value = true
      description.value = ''
      permanent.value = true
      time.value = null
    }
    const optionsFn = (val) => {
      return val >= date.formatDate(Date.now(), 'YYYY/MM/DD')
    }
    const addBanAction = () => {
      const data = {
        user_uid: props.userUid,
        type: props.type,
        description: description.value,
        end_ban_time: permanent.value ? null : time.value,
        uid: onlyCurrentItem.value ? props.objectUid : 'all'
      }
      addUserBan(data)
        .then(response => {
          showDialog.value = false
          $q.notify(
            {
              message: 'Ван успешно добавлен',
              position: 'top-right',
              color: 'secondary'
            }
          )
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }
    return {
      showDialogAction,
      time,
      onlyCurrentItem,
      showDialog,
      description,
      optionsFn,
      permanent,
      dialogText,
      addBanAction
    }
  }
})
</script>

<style scoped>

</style>
