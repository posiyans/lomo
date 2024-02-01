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
      <q-tab no-caps label="Счета" name="invoice" />
      <q-tab v-if="edit" no-caps label="Добавить" name="add" />
    </q-tabs>

    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="invoice">
        <AdminInvoiceList />
      </q-tab-panel>
      <q-tab-panel name="add">
        <InvoiceGroupList />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import AdminInvoiceList from 'src/Modules/Bookkeeping/pages/Invoice/AdminInvoiceList/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import InvoiceGroupList from 'src/Modules/Bookkeeping/components/InvoiceGroup/InvoiceGroupList/index.vue'

export default defineComponent({
  components: {
    AdminInvoiceList,
    InvoiceGroupList
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const tab = ref('invoice')
    const authStore = useAuthStore()
    const edit = computed(() => {
      return authStore.checkPermission(['invoice-edit'])
    })
    return {
      data,
      edit,
      tab
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
