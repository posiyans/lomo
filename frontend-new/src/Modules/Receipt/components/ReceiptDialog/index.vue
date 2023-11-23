<template>

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
    <q-tab-panel name="stead" class="q-pa-lg row justify-center">
      <div class="" style="max-width: 500px;">
        <SelectStead v-model="stead_id" outlined auto-select label="Укажите номер участка" style="min-width: 300px;" />
        <div style="min-height: 150px;">
          <div v-if="stead_id" class="text-red q-pa-sm">
            <div>
              Внимание! Проверте данные.
            </div>
            <div class="row items-center ">
              Участок №
              <SteadInfo :stead-id="stead_id" class="text-weight-bold q-px-sm text-primary" />
              размер
              <SteadInfo :stead-id="stead_id" format="s" class="text-weight-bold q-px-sm text-primary" />
              кв.м.
            </div>
          </div>
        </div>
        <div class="q-pa-lg">
          <q-btn color="negative" flat label="Отмена" @click="show = false" />
          <q-btn color="primary" :disabled="!stead_id" label="Скачать" @click="download" />
        </div>
      </div>
    </q-tab-panel>
    <q-tab-panel name="rate">
      <RateList />
    </q-tab-panel>
  </q-tab-panels>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getReceiptForStead } from 'src/Modules/Receipt/api/receipt.js'
import SelectStead from 'src/Modules/Stead/components/SelectStead/index.vue'
import RateList from 'src/Modules/Rates/components/ShowRateList/index.vue'
import SteadInfo from 'src/Modules/Stead/components/SteadInfo/index.vue'
import { exportFile } from 'quasar'

export default defineComponent({
  components: {
    SelectStead,
    RateList,
    SteadInfo
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(null)
    const tab = ref('stead')
    const stead_id = ref('')
    const router = useRouter()
    const route = useRoute()

    onMounted(() => {

    })
    return {
      data,
      stead_id,
      tab
    }
  },
  methods: {
    totalFilter(size, rate) {
      let sum = 0
      rate.forEach(i => {
        sum = Number(sum) + Number(i.rate.ratio_a) * Number(size) / 100 + Number(i.rate.ratio_b)
      })
      return sum.toFixed(2)
    },

    download() {
      const data = {
        stead: this.stead_id
      }
      getReceiptForStead(data)
        .then(response => {
          let fileName = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
          try {
            fileName = decodeURIComponent(response.headers['content-disposition'].split("filename*=utf-8''")[1].split(';')[0])
          } catch (e) {
          }
          exportFile(fileName, response.data)
          this.show = false
        })
    },
    setStead(val) {
      if (val.id) {
        this.stead = val
      } else {
        this.stead = ''
      }
    },
    close() {
      this.$emit('close')
    }
  }
})
</script>

<style scoped>

</style>
