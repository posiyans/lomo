<template>
  <div class="app-container">
    <h2>Расписание электричек</h2>
    <div>
      <el-radio-group v-model="queryParams.back" @change="getList">
         <el-radio-button v-for="dt in directions"  :label="dt.key">{{dt.label}}</el-radio-button>
      </el-radio-group>
      <el-radio-group v-model="queryParams.type" @change="getList">
        <el-radio-button v-for="dt in dates"  :label="dt.key">{{dt.label}}</el-radio-button>
      </el-radio-group>
    </div>
    <section class="SearchSegments">
      <template v-for="item in list.segments">
        <article v-if="checked || new Date(item.departure) > new Date() || queryParams.type === '2'" class="SearchSegment" :class="{ isGone: new Date(item.departure) < new Date()  &&  queryParams.type !== '2' }">
          <div class="SegmentHeader">
            <div class="SegmentTitle">
              <div class="SegmentTitle__header"><a
                :href="'https://rasp.yandex.ru/thread/' + item.thread.uid"
                :title="'Расписание электрички ' +  item.thread.number + ' ' + item.thread.title"
                class="Link SegmentTitle__link">
                <span class="SegmentTitle__number">{{ item.thread.number }}</span>
                <span class="SegmentTitle__title">{{ item.thread.title }}</span></a></div>
              <div class="SegmentTransport SegmentTitle__transport">
                <span  class="SegmentTransport__item SegmentTransport__item_subtype" :style="item.thread.transport_subtype.color | colorFilter">{{item.thread.transport_subtype.title}}</span>
              </div>
            </div>
          </div>
          <div class="SearchSegment__timeAndStations">
            <div class="SearchSegment__times">
              <div class="SearchSegment__dateTime Time_important">
                <span v-if="queryParams.type === '2'" class="SearchSegment__time">
                  {{ item.departure.substr(0,5)}}
                </span>
                <span v-else class="SearchSegment__time">
                  {{item.departure | moment('HH:mm')}}
                </span>
              </div>
              <div v-if="!mobile" class="SearchSegment__duration">
                <span v-if="item.duration > 60">{{ item.duration*1000 | duration('hours') }} ч</span>
                <span v-if="item.duration % 3600 > 0">
                  {{ item.duration*1000 | duration('minutes') }} мин
                </span>
              </div>
              <div v-if="!mobile" class="SearchSegment__dateTime">
                 <span v-if="queryParams.type === '2'" class="SearchSegment__time">
                  {{item.arrival.substr(0,5)}}
                </span>
                <span v-else class="SearchSegment__time">
                  {{item.arrival | moment('HH:mm')}}
                </span>
              </div>
            </div>
            <div class="RailwayTimes SearchSegment__timesRailway"></div>
            <div class="SearchSegment__stations">
              <div class="SearchSegment__stationHolder"></div>
              <div class="SearchSegment__stationHolder"></div>
            </div>
            <div class="SearchSegment__stops">{{item.stops}}</div>
          </div>
          <div  class="SearchSegment__scheduleAndPrices">
            <div class="SegmentPrices">
              <div class="TariffsListItem__classTitle"></div>
              <span v-if="item.tickets_info" class="Price TariffsListItem__price">{{ item.tickets_info.places[0].price.whole }} ₽</span>
              <div>{{item.days}}</div>
            </div>
          </div>
        </article>
      </template>
      <div v-if="queryParams.type === '0'" class="more" @click="checked = !checked">{{checkLabel}}</div>
      <div class="yandexLink">
        <div>
          <a href="http://rasp.yandex.ru">Данные предоставлены сервисом Яндекс.Расписания</a>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { fetchYandexSchedule } from '@/api/yandex/schedule.js'
import {mapState} from "vuex";
export default {
  filters: {
    colorFilter(val) {
      return 'color:' + val
    }
  },
  data() {
    return {
      directions: [
        {
          key: false,
          label: 'Туда'
        },
        {
          key: true,
          label: 'Обратно'
        },
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
    ...mapState({
      device: state => state.app.device
    }),
    mobile() {
      if (this.device === 'mobile') {
        return true
      }
      return false
    },
    timeNow() {
      return new Date()
    },
    checkLabel() {
      if (this.checked) {
        return 'Скрыть ушедшие'
      }
      return 'Показать ушедшие'
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      fetchYandexSchedule(this.queryParams).then(response => {
        this.list = response.data
      })
    }
  }
}
</script>

<style scoped>
  .more {
    background-color: #5c6268;
    color: #fff;
    text-align: center;
    padding-bottom: 10px;
    padding-top: 10px;
    cursor: pointer;
  }
  .isGone {
    opacity:.4;
  }
  .SearchSegments {
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .SearchSegment {
    display: flex;
    align-items: flex-start;
    font-size: 13px;
    line-height: 16px;
    border-top: 1px solid
    rgba(0,0,0,.1);
    padding-top: 8px;
    padding-bottom: 8px;
    position: relative;
  }
  .SegmentHeader {
    flex: 0 0 40%;
    max-width: 40%;
    padding: 8px 8px 0;
    box-sizing: border-box;
  }
  .SegmentTitle {
    position: relative;
    padding: 0 0 4px 4px;
    color: #005bf5;
  }
    SegmentTitle__title {
      display: inline;
      font-size: 13px;
      line-height: 16px;
      font-weight: 400;

  }
  .SearchSegment__duration {
    font-size: 11px;
    line-height: 18px;
    white-space: nowrap;
    color:       #777;
  }
  .SegmentTitle__header {
    font-size: 13px;
    line-height: 16px;
    font-weight: 400;
    margin: 0 0 4px;
  }
  .SegmentTitle__number {
    /*margin: 0 .9em 0 0;*/
    font-weight: 600;

  }
  .SegmentTitle__link {
    cursor: pointer;
    outline: none;
    background-color:
      transparent;
    text-decoration: none;
  }
  .SegmentTitle__link:hover {
    color:
      #d00;
  }
  .SearchSegment__dateTime.Time_important {
    font-weight: 700;
  }
  .SearchSegment__times {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .SearchSegment__stops {
    font-size: 11px;
    line-height: 15px;
    box-sizing: border-box;
    margin: 2px -30% 0 0;
    color:
      #999;
  }
  .SearchSegment__scheduleAndPrices {
    flex: 0 0 20.66%;
    max-width: 20.66%;
    position: relative;
    text-align: right;
  }
  .SegmentTransport {
    color: #444;
    font-size: 11px;
    line-height: 15px;
  }
  .SearchSegment__timeAndStations {
    flex: 0 0 33.33%;
    max-width: 33.33%;
  }
  .SearchSegment__scheduleAndPrices, .SearchSegment__timeAndStations, .SearchSegment__totalDuration {
    box-sizing: border-box;
    padding: 4px 8px;
  }
  .Price {
    font-family: rub,Arial;
    font-weight: 700;
  }
  .yandexLink {
    color: #1d68a7;
    text-align: right;
    padding: 10px;
  }
</style>
