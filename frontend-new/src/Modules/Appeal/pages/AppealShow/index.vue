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
            <div class="row items-center bg-grey-2 q-pa-none" style="border-radius: 16px;">
              <div class="text-teal" :class="{'q-pa-sm' : !showAppealTypeSetting, 'q-pl-mh' : showAppealTypeSetting}">
                {{ appeal.type.label }}
                <q-tooltip>
                  {{ appeal.type.description }}
                </q-tooltip>
              </div>
              <q-fab
                v-if="showAppealTypeSetting"
                flat
                text-color="black"
                icon="more_vert"
                direction="down"
                padding="xs"
              >
                <ChangeAppealTypeBtn :appeal="appeal" @success="getData" />
              </q-fab>
            </div>
          </div>
          <q-space />
          <div>
            <q-btn v-if="appeal.status === 'open'" rounded outline label="Закрыть обращение" color="negative" @click="closeAppealAction" />
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
        <div v-if="appeal.files && appeal.files?.length > 0" class="q-pa-md">
          <div v-if="appeal.files?.length > 0" class="text-weight-bold">Файлы:</div>
          <FilesListShow :model-value="appeal.files" show-preview />
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section class="bg-grey-1 q-pa-none">
        <ChatBlock :appeal="appeal" />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { closeAppeal, getAppeal } from 'src/Modules/Appeal/api/appealApi.js'
import AppealStatusLabelById from 'src/Modules/Appeal/components/AppealStatusLabelById/index.vue'
import AppealTypeNameById from 'src/Modules/Appeal/components/AppealTypeNameById/index.vue'
import { useQuasar } from 'quasar'
import ShowTime from 'components/ShowTime/index.vue'
import ChatBlock from './components/ChatBlock/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { useFile } from 'src/Modules/Files/hooks/useFile'
import ChangeAppealTypeBtn from 'src/Modules/Appeal/components/ChangeAppealTypeBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ChangeAppealTypeBtn,
    AppealStatusLabelById,
    AppealTypeNameById,
    ShowTime,
    FilesListShow,
    ChatBlock
  },
  props: {},
  setup(props, { emit }) {
    const appeal = ref({
      files: []
    })
    const loading = ref(true)
    const router = useRouter()
    const route = useRoute()
    const $q = useQuasar()
    const authStore = useAuthStore()
    const showAppealTypeSetting = computed(() => {
      return appeal.value.status !== 'close' && authStore.checkPermission('appeal-edit')
    })
    const getData = () => {
      getAppeal(route.params.id)
        .then(res => {
          appeal.value = res.data.data
          const tmp = []
          if (res.data.data.files) {
            res.data.data.files.forEach(item => {
              const file = useFile()
              file.init(item)
              tmp.push(file)
            })
          }
          appeal.value.files = tmp
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
      showAppealTypeSetting,
      loading,
      getData
    }
  }
})
</script>

<style scoped>

</style>
