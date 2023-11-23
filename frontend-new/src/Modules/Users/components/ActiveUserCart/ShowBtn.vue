<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn icon="info" flat color="primary" />
      </slot>
    </div>
    <q-dialog
      v-model="dialogVisible"
      maximized
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card>
        <q-bar class="bg-primary text-white">
          {{ activeUserStore.fullName }}
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>
        <q-card-section class="q-pt-none">
          <AdminUserCart />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AdminUserCart from './index.vue'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'

export default defineComponent({
  components: {
    AdminUserCart
  },
  props: {
    userId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const dialogVisible = ref(false)
    const activeUserStore = useActiveUserStore()

    const showDialog = () => {
      activeUserStore.init(props.userId)
      activeUserStore.getUserInfo()
      dialogVisible.value = true
    }
    onMounted(() => {

    })
    return {
      dialogVisible,
      activeUserStore,
      showDialog,
      data
    }
  }
})
</script>

<style scoped>

</style>
