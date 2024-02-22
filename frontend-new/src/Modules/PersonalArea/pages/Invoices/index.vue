<template>
  <div>
    <UserSteadsSelected>
      <template v-slot:default="{stead}">
        <PaymentAndInvoiceForStead :stead-id="stead" />
      </template>
    </UserSteadsSelected>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import PaymentAndInvoiceForStead from 'src/Modules/Bookkeeping/components/PaymentAndInvoiceForStead/index.vue'
import UserSteadsSelected from 'src/Modules/PersonalArea/components/UserSteadsSelected/index.vue'

export default defineComponent({
  components: {
    UserSteadsSelected,
    PaymentAndInvoiceForStead
  },
  props: {},
  setup(props, { emit }) {
    return {}
  }
})
</script>

<style scoped lang='scss'>

</style>

