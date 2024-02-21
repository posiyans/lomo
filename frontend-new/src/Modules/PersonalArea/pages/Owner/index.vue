<template>
  <div>
    <q-card>
      <q-card-section class="">
        <div class="page-title">
          Собственник
        </div>
        <ShowOwnerInfo :owner-uid="authStore.user.owner.uid" />
        <div class="q-pt-md">
          <q-banner inline-actions class="text-white bg-red">
            При несоответствии данных просьба сообщить об этом правлению
          </q-banner>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowOwnerInfo from 'src/Modules/Owner/components/ShowOwnerInfo/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ShowOwnerInfo
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const authStore = useAuthStore()
    return {
      data,
      authStore
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
