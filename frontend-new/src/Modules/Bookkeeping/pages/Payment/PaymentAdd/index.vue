<template>
  <div>
    <q-tabs
      v-model="tab"
      class="bg-grey-1"
      active-color="primary"
      active-bg-color="teal-1"
      indicator-color="primary"
      align="left"
      narrow-indicator
    >
      <q-tab no-caps label="из банка" name="bank" />
      <q-tab no-caps label="внести вручную" name="rate" />
    </q-tabs>

    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="bank">
        <PaymentBankParse />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import PaymentBankParse from 'src/Modules/Bookkeeping/components/Payment/PaymentBankParse/index.vue'

export default defineComponent({
  components: {
    PaymentBankParse
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const tab = ref('bank')
    return {
      data,
      tab
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
