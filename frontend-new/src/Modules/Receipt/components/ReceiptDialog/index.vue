<template>
  <q-dialog
    v-model="show"
    @hide="close"
    :maximized="$q.platform.is.mobile"
  >
    <q-card>
      <q-card-section class="row items-center q-pb-none no-wrap">
        <div class="text-h6">Скачать квитанцию на оплату взносов</div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section class="q-pa-md">
        <q-tabs
            v-model="tab"
            class="bg-grey-1"
            active-color="primary"
            active-bg-color="teal-1"
            indicator-color="primary"
            align="left"
            narrow-indicator
        >
          <q-tab no-caps label="Квитанция" name="stead" />
          <q-tab no-caps label="Тарифы" name="rate" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="stead" class="q-pa-lg">
            <SelectStead v-model="stead_id" outlined dense label="Укажите номер участка." />
            <div v-if="stead_id" class="text-red q-pa-sm">
              <div>
                Внимание! Проверте данные.
              </div>
              <div class="row items-center ">
                Участок № <SteadInfo :stead-id="stead_id" class="text-weight-bold q-px-sm text-primary" />
                  размер
                <SteadInfo :stead-id="stead_id" format="s"  class="text-weight-bold q-px-sm text-primary" />  кв.м.
              </div>
            </div>
            <div class="q-pa-lg">
              <el-button @click="show = false">Отмена</el-button>
              <el-button type="primary" :disabled="!stead_id" @click="download">Скачать</el-button>
            </div>
          </q-tab-panel>
          <q-tab-panel name="rate" >
            <RateList />
          </q-tab-panel>
        </q-tab-panels>

      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script>
// import { fetchList } from '@/api/rate'
import { getReceiptForStead } from 'src/Modules/Receipt/api/receipt.js'
import { saveAs } from 'file-saver'
import SelectStead from 'src/Modules/Stead/components/SelectStead/index.vue'
import RateList from 'src/Modules/Rates/pages/List/index.vue'
import SteadInfo from 'src/Modules/Stead/components/SteadInfo/index.vue'

export default {
  components: {
    SelectStead,
    RateList,
    SteadInfo
  },
  props: {
    dialogVisible: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {
      tab: 'stead',
      getSteadsList: [],
      stead_id: '',
      stead: '',
      show: true,
      rate: []
    }
  },
  mounted () {
    this.show = this.dialogVisible
    // this.getRateList()
  },
  methods: {
    totalFilter (size, rate) {
      let sum = 0
      rate.forEach(i => {
        sum = Number(sum) + Number(i.rate.ratio_a) * Number(size) / 100 + Number(i.rate.ratio_b)
      })
      return sum.toFixed(2)
    },

    download () {
      const data = {
        stead: this.stead_id
      }
      getReceiptForStead(data).then(response => {
        saveAs(new Blob([response.data], {
          type: response.data.type
        }), 'Квитанция_.pdf')
        this.show = false
      })
    },
    setStead (val) {
      if (val.id) {
        this.stead = val
      } else {
        this.stead = ''
      }
    },
    close () {
      this.$emit('close')
    }
    // getRateList () {
    //   this.loading = true
    //   const data = {
    //     type: 2
    //   }
    //   fetchList(data).then(response => {
    //     this.rate = response.data.data
    //     this.loading = false
    //   })
    // }
  }
}
</script>

<style scoped>

</style>
