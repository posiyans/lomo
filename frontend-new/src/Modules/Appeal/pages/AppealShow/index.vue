<template>
  <div v-if="!loading" class="q-pa-sm">
    <q-card>
      <q-card-section class="q-pb-sm">
        <div class="row items-center">
          <div class="row items-center q-col-gutter-sm">
            <div>
              <div class="text-primary">
                {{ appeal.title }}
              </div>
              <div class="text-small-75 row q-col-gutter-sm">
                <div>
                  Создал
                </div>
                <ShowTime :time="appeal.created_at" class="o-80" />
                <div>
                  {{ appeal.user.fullName }}
                </div>
              </div>
              <div v-if="appeal.status === 'close'" class="text-small-75 row q-col-gutter-sm text-teal">
                <div>
                  Закрыл
                </div>
                <ShowTime :time="appeal.close.date" class="o-80" />
                <div>
                  {{ appeal.close.user }}
                </div>
              </div>
            </div>
            <q-chip class="text-teal">
              {{ appeal.type.label }}
            </q-chip>
          </div>
          <q-space />
          <div>
            <q-btn v-if="appeal.status === 'open'" rounded label="Закрыть" color="negative" @click="closeAppealAction" />
            <q-btn v-if="appeal.status === 'close'" label="Закрыто" color="secondary" disable />
          </div>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <div class="q-gutter-md">
          <div>
            {{ appeal.text }}
          </div>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="bg-grey-1">
        <ChatBlock :appeal="appeal" />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { closeAppeal, getAppeal } from 'src/Modules/Appeal/api/appealApi.js'
import AppealStatusLabelById from 'src/Modules/Appeal/components/AppealStatusLabelById/index.vue'
import AppealTypeNameById from 'src/Modules/Appeal/components/AppealTypeNameById/index.vue'
import { useQuasar } from 'quasar'
import ShowTime from 'components/ShowTime/index.vue'
import ChatBlock from './components/ChatBlock/index.vue'

export default defineComponent({
  components: {
    AppealStatusLabelById,
    AppealTypeNameById,
    ShowTime,
    ChatBlock
  },
  props: {},
  setup(props, { emit }) {
    const appeal = ref({})
    const loading = ref(true)
    const router = useRouter()
    const route = useRoute()
    const $q = useQuasar()
    const getData = () => {
      getAppeal(route.params.id)
        .then(res => {
          appeal.value = res.data.appeal
          loading.value = false
        })
    }
    const closeAppealAction = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Закрыть данное обращение?',
        cancel: {
          label: 'Отмена',
          color: 'negative',
          flat: true
        },
        ok: {
          label: 'Закрыть'
        },
        persistent: true
      }).onOk(() => {
        closeAppeal(appeal.value.id)
          .then(res => {
            getData()
          })
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      })
    }
    onMounted(() => {
      console.log(route.params.id)
      getData()
    })
    return {
      appeal,
      closeAppealAction,
      loading
    }
  }
})
</script>

<style scoped>

</style>
