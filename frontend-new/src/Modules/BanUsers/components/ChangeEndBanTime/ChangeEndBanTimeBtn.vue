<template>
  <div>
    <div @click="showDialogAction">
      <slot>
        <q-btn color="primary" icon="settings" flat dense />
      </slot>
    </div>
    <q-dialog v-model="showDialog">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Изменить срок бана</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section class="q-pa-lg">
          <ChangeEndBanTime :ban="ban" @success="reloadData" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ChangeEndBanTime from 'src/Modules/BanUsers/components/ChangeEndBanTime/index.vue'

export default defineComponent({
  components: {
    ChangeEndBanTime
  },
  props: {
    ban: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const showDialog = ref(false)
    const router = useRouter()
    const route = useRoute()
    const showDialogAction = () => {
      showDialog.value = true
    }
    const reloadData = () => {
      showDialog.value = false
      emit('success')
    }
    onMounted(() => {

    })
    return {
      data,
      showDialog,
      reloadData,
      showDialogAction
    }
  }
})
</script>

<style scoped>

</style>
