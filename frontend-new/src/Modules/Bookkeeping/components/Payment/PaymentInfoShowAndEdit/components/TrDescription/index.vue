<template>
  <tr>
    <td>Примечание</td>
    <td>
      <div v-if="editDescription" class="row items-center">
        <div>
          <q-input
            v-model="payment.description"
            outlined
            autogrow
            dense
          />

        </div>
        <div>
          <q-btn flat padding="sm" icon="save" color="green" @click="saveDescription" />
        </div>
      </div>
      <div v-else class="row items-center">
        <div v-html="descriptionHtml" />
        <q-space />
        <div v-if="edit">
          <q-btn icon="edit" flat color="primary" dense size="sm" @click="editDescription = !editDescription" />
        </div>
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'

export default defineComponent({
  components: {
    SteadSelect
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const editDescription = ref(false)
    const { payment, getPaymentData, loading, updatePaymentData } = useCurrentPayment()

    const saveDescription = async () => {
      await updatePaymentData()
      getPaymentData()
      editDescription.value = false
    }
    const descriptionHtml = computed(() => {
      if (payment.value?.description) {
        return payment.value?.description.replace(/\n/g, '<br />') || ''
      }
      return ''
    })
    return {
      data,
      descriptionHtml,
      saveDescription,
      editDescription,
      payment,
      loading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
