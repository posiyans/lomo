<template>
  <div>
    <FilterForm v-model="queryParams" @update:model-value="getList" />
    <div style="padding: 10px 0; height: 5px;">
      <q-linear-progress v-if="loading" indeterminate />
    </div>
    <div>
      <q-list bordered separator>
        <template v-for="item in list.segments">
          <q-item v-if="checked || new Date(item.departure) > new Date() || queryParams.type === '2'" :key="item.thread.uid" clickable v-ripple>
            <q-item-section avatar>
              <q-img
                :src="item.thread.carrier.logo"
                spinner-color="white"
              />
            </q-item-section>
            <q-item-section>
              <q-item-label>
                <div class="row items-center q-col-gutter-lg">
                  <div class="q-col-gutter-sm">
                    <div class="q-mr-lg">
                      <a
                        :href="'https://rasp.yandex.ru/thread/' + item.thread.uid"
                        :title="'Расписание электрички ' + item.thread.number + ' ' + item.thread.title"
                        class="q-col-gutter-sm"
                      >
                        <span class="text-weight-bold">{{ item.thread.number }}</span>
                        <span>{{ item.thread.title }}</span>
                      </a>

                    </div>
                    <div class="text-small-80" :style="'color: ' + item.thread.transport_subtype.color">
                      {{ item.thread.transport_subtype.title }}
                    </div>
                  </div>
                  <div>
                    <div class="row items-center justify-between" style="min-width: 220px;">
                      <ShowTime :time="item.departure" format="HH:mm" class="text-weight-bolder" />
                      <ShowDuration :time="item.duration" format="HH:mm" class="text-small-80 text-grey" />
                      <ShowTime :time="item.arrival" format="HH:mm" class="text-weight-bolder" />
                    </div>
                    <div class="text-grey text-small-80 text-center">{{ item.stops }}</div>
                  </div>
                </div>
              </q-item-label>
            </q-item-section>
            <q-item-section top side>
              <div v-if="item.tickets_info" class="text-black text-weight-bold">
                {{ item.tickets_info.places[0].price.whole }}   ₽
              </div>
              <div v-else style="min-width: 40px;">
              </div>
            </q-item-section>
          </q-item>
        </template>
      </q-list>
    </div>
    <div class="">
      <div v-if="queryParams.type === '0'" class="text-center q-pa-sm bg-grey-8 text-white" @click="checked = !checked">{{ checkLabel }}</div>
      <div class="q-pa-lg text-right">
        <div>
          <a
            href="https://rasp.yandex.ru/search/?fromId=c2&fromName=Санкт-Петербург&toId=s9602670&toName=Чаща"
            class="text-primary">
            Данные предоставлены сервисом Яндекс.Расписания
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { fetchYandexSchedule } from 'src/Modules/Yandex/api/schedule.js'
import ShowTime from 'components/ShowTime/index.vue'
import ShowDuration from 'src/components/ShowDuration/index.vue'
import FilterForm from './components/FilterForrm/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    ShowDuration,
    FilterForm
  },
  props: {},
  setup(props, { emit }) {
    const queryParams = ref({
      type: '0',
      back: false
    })
    const list = ref([])
    const checked = ref(false)
    const loading = ref(false)
    const checkLabel = computed(() => {
      if (checked.value) {
        return 'Скрыть ушедшие'
      }
      return 'Показать ушедшие'
    })
    const getList = () => {
      loading.value = true
      fetchYandexSchedule(queryParams.value)
        .then(response => {
          list.value = response.data
        })
        .finally(() => {
          loading.value = false
        })
    }
    onMounted(() => {
      getList()
    })
    return {
      queryParams,
      list,
      getList,
      checked,
      loading,
      checkLabel
    }
  }
})
</script>

<style scoped>

</style>
