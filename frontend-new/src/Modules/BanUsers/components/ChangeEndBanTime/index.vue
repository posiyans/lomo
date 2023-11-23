<template>
  <div class="q-gutter-md">
    <div>
      <q-input :disable="permanent" outlined v-model="date" label="Время окончания бана">
        <template v-slot:prepend>
          <q-icon name="event" class="cursor-pointer">
            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
              <q-date v-model="date" mask="YYYY-MM-DD HH:mm">
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
              <q-time v-model="date" mask="YYYY-MM-DD HH:mm" format24h>
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
    <div class="row q-col-gutter-sm justify-end">
      <div>
        <q-btn color="negative" flat label="Отмена" v-close-popup />
      </div>
      <div>
        <q-btn color="primary" label="Сохранить" @click="updateEndTime" />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { updateUserBan } from 'src/Modules/BanUsers/api/apiBanUser'

export default defineComponent({
  components: {},
  props: {
    ban: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const date = ref(null)
    const permanent = ref(false)
    const router = useRouter()
    const route = useRoute()
    const updateEndTime = () => {
      const data = {
        ban_uid: props.ban.uid,
        end_ban_time: date.value
      }
      if (permanent.value) {
        data.end_ban_time = null
      }
      updateUserBan(data)
        .then(() => {
          emit('success')
        })
        .catch(err => {
          console.log(err.response)
        })
    }
    onMounted(() => {
      if (props.ban.end_ban_time) {
        permanent.value = false
        date.value = props.ban.end_ban_time
      } else {
        permanent.value = true
      }
    })
    return {
      date,
      permanent,
      updateEndTime
    }
  }
})
</script>

<style scoped>

</style>
