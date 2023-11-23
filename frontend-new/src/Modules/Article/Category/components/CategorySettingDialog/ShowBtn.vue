<template>
  <div>
    <q-btn size="12px" flat dense round icon="more_vert" @click="showSetting" />
    <q-dialog v-model="dialogVisible">
      <q-card style="min-width: 600px;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Редактировать раздел</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <CategorySettingDialog :item="item" @success="success" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import CategorySettingDialog from 'src/Modules/Article/Category/components/CategorySettingDialog/index.vue'

export default defineComponent({
  components: {
    CategorySettingDialog
  },
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const dialogVisible = ref(false)
    const router = useRouter()
    const route = useRoute()
    const showSetting = () => {
      dialogVisible.value = true
    }
    const success = () => {
      dialogVisible.value = false
      emit('success')
    }
    onMounted(() => {

    })
    return {
      data,
      dialogVisible,
      showSetting,
      success
    }
  }
})
</script>

<style scoped>

</style>
