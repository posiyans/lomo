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
        <RateGroupSelect
          v-model="rateGroupId"
          outlined
          auto-select
          :params="{depends: 1}"
        />
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
        <div class="text-center">
          <q-btn color="negative" flat label="Отмена" v-close-popup />
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
import { defineComponent, ref } from 'vue'
import { getReceiptForStead } from 'src/Modules/Receipt/api/receipt.js'
import SelectStead from 'src/Modules/Stead/components/SelectStead/index.vue'
import RateList from 'src/Modules/Rate/components/ShowRateList/index.vue'
import SteadInfo from 'src/Modules/Stead/components/ShowSteadInfo/index.vue'
import { exportFile } from 'quasar'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'

export default defineComponent({
  components: {
    SelectStead,
    RateList,
    SteadInfo,
    RateGroupSelect
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(null)
    const tab = ref('stead')
    const stead_id = ref('')
    const rateGroupId = ref('')
    const download = () => {
      const data = {
        stead_id: stead_id.value,
        rate_group_id: rateGroupId.value
      }
      getReceiptForStead(data)
        .then(response => {
          let fileName = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
          try {
            fileName = decodeURIComponent(response.headers['content-disposition'].split("filename*=utf-8''")[1].split(';')[0])
          } catch (e) {
          }
          exportFile(fileName, response.data)
        })
    }

    return {
      data,
      stead_id,
      rateGroupId,
      download,
      tab
    }
  }
})
</script>

<style scoped>

</style>
