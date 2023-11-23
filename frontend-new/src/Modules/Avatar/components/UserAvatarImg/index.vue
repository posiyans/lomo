<template>
  <q-img v-if="uid" :src="url" spinner-color="white">
    <slot></slot>
    <template v-slot:loading>
      <q-spinner-gears color="secondary" size="0.5em" />
    </template>
  </q-img>
</template>

<script>
import { computed, defineComponent } from 'vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {},
  props: {
    uid: String
  },
  setup(props) {
    const authStore = useAuthStore()
    const url = computed(() => {
      return process.env.BASE_API + '/api/v2/avatar/user/get?uid=' + props.uid + '&sail=' + authStore.key
    })

    return {
      url
    }
  }
})
</script>

<style scoped>

</style>
