<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn label="Подробнее" color="primary" />
      </slot>
    </div>
    <q-dialog
      v-model="dialogVisible"
      :maximized="dialogMaximized"
      @hide="close"
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6 row items-center q-col-gutter-xs">
            <div>
              Счет № {{ invoice.id }} от
            </div>
            <ShowTime :time="invoice.created_at" format="DD-MM-YYYY" />
          </div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <InvoiceInfo :invoice="invoice" @success="close" :edit="edit" />
        </q-card-section>
      </q-card>
    </q-dialog>

  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import InvoiceInfo from 'src/Modules/Bookkeeping/components/Invoice/InvoiceInfo/index.vue'
import ShowTime from 'components/ShowTime/index.vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    InvoiceInfo,
    ShowTime
  },
  props: {
    invoice: {
      type: Object,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const $q = useQuasar()
    const dialogMaximized = computed(() => {
      return $q.screen.width < 600
    })
    const showDialog = () => {
      dialogVisible.value = true
    }
    const close = () => {
      dialogVisible.value = false
      emit('reload')
    }
    return {
      dialogVisible,
      showDialog,
      dialogMaximized,
      close
    }
  }
})
</script>

<style scoped lang='scss'>

</style>