<template>
  <div>
    <div class="row items-center q-col-gutter-sm">
      <div>
        <el-radio-group v-model="queryParams.back" @change="getList">
          <el-radio-button v-for="dt in directions" :key="dt.key" :label="dt.key">{{ dt.label }}</el-radio-button>
        </el-radio-group>
      </div>
      <div>
        <el-radio-group v-model="queryParams.type" @change="getList">
          <el-radio-button v-for="dt in dates" :key="dt.key" :label="dt.key">{{ dt.label }}</el-radio-button>
        </el-radio-group>
      </div>
    </div>
    <div class="">

      <div v-for="item in list.segments" :key="item.thread.uid">
        <div v-if="checked || new Date(item.departure) > new Date() || queryParams.type === '2'" class="row items-center q-col-gutter-lg q-pa-sm" :class="{ isGone: new Date(item.departure) < new Date() && queryParams.type !== '2' }">
          <div>
            <q-avatar>
              <q-img
                  :src="item.thread.carrier.logo"
                  spinner-color="white"
              />
            </q-avatar>
          </div>
          <div class="q-mr-lg">
            <div class="row items-center q-col-gutter-sm">
              <a
              :href="'https://rasp.yandex.ru/thread/' + item.thread.uid"
              :title="'Расписание электрички ' + item.thread.number + ' ' + item.thread.title"
              class="row items-center q-col-gutter-sm"
            >
              <div class="text-weight-bold q-p">{{ item.thread.number }}</div>
              <div class="SegmentTitle__title">{{ item.thread.title }}</div>
            </a>
            </div>
            <div class="text-small-80"  :style="'color: ' + item.thread.transport_subtype.color">
              {{ item.thread.transport_subtype.title }}
            </div>
          </div>
          <div class="col-grow">
            <div class="row items-center q-col-gutter-lg">
              <ShowTime :time="item.departure" format="HH:mm" class="text-weight-bolder" />
              <ShowDuration :time="item.duration" format="HH:mm" class="text-small-80 text-grey" />
              <ShowTime :time="item.arrival" format="HH:mm" class="text-weight-bolder" />
            </div>
            <div class="text-grey text-small-80 q-py-sm">{{ item.stops }}</div>
          </div>
          <div class="q-mr-lg text-weight-bold">
            <div v-if="item.tickets_info" class="">
              {{ item.tickets_info.places[0].price.whole }}   ₽
            </div>
          </div>
        </div>
          <q-separator/>
      </div>
      <div v-if="queryParams.type === '0'" class="text-center q-pa-sm bg-grey-8 text-white" @click="checked = !checked">{{ checkLabel }}</div>
      <div class="q-pa-lg text-right">
        <div>
          <a href="http://rasp.yandex.ru" class="text-primary">Данные предоставлены сервисом Яндекс.Расписания</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchYandexSchedule } from 'src/Modules/Yandex/api/schedule.js'
import ShowTime from 'components/ShowTime/index.vue'
import ShowDuration from 'src/components/ShowDuration/index.vue'

export default {
  components: {
    ShowTime,
    ShowDuration
  },
  data () {
    return {
      directions: [
        {
          key: false,
          label: 'Туда'
        },
        {
          key: true,
          label: 'Обратно'
        }
      ],
      dates: [
        {
          key: '0',
          label: 'Сегодня'
        },
        {
          key: '1',
          label: 'Завтра'
        },
        {
          key: '2',
          label: 'На все дни'
        }
      ],
      queryParams: {
        type: '0',
        back: false
      },
      checked: false,
      list: {}
    }
  },
  computed: {
    mobile () {
      return this.$q.platform.is.mobile
    },
    timeNow () {
      return new Date()
    },
    checkLabel () {
      if (this.checked) {
        return 'Скрыть ушедшие'
      }
      return 'Показать ушедшие'
    }
  },
  mounted () {
    this.getList()
  },
  methods: {
    colorFilter (val) {
      return 'color:' + val
    },
    getList () {
      fetchYandexSchedule(this.queryParams).then(response => {
        this.list = response.data
      })
    }
  }
}
</script>

<style scoped>

</style>
