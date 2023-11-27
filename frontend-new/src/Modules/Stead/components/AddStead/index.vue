<template>
  <div>
    <q-btn icon="add" flat color="primary" @click="showDialog" />
    <q-dialog v-model="dialogVisible">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Close icon</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <div class="q-gutter-md">

            <div>
              <q-input v-model="data.number" outlined label="Номер участка" />
            </div>
            <div>
              <InputNumber v-model="data.size" outlined label="Размер участка, м^2" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import InputNumber from 'components/Input/InputNumber/index.vue'

export default defineComponent({
  components: {
    InputNumber
  },
  props: {},
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const data = ref({
      number: '',
      size: ''
    })
    const router = useRouter()
    const route = useRoute()
    const showDialog = () => {
      dialogVisible.value = true
    }
    onMounted(() => {

    })
    return {
      data,
      dialogVisible,
      showDialog
    }
  }
})
</script>

<style scoped>

</style>
