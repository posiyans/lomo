<template>
  <div>
    <q-spinner-dots
      v-if="loading"
      color="primary"
      size="2em"
    />
    <SelectSteadDialog v-if="!loading" @add-stead="addStead" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import SelectSteadDialog from 'src/Modules/Stead/components/SelectSteadDialog/index.vue'
import { addSteadToOwnerUser } from 'src/Modules/Owner/api/ownerUserApi'

export default defineComponent({
  components: {
    SelectSteadDialog
  },
  props: {
    ownerUid: {
      type: String,
      required: true
    }
  },
  emits: ['success'],
  setup(props, { emit }) {
    const loading = ref(false)
    const dialogVisible = ref(false)
    const addStead = (stead_id) => {
      if (stead_id) {
        loading.value = true
        const data = {
          stead_id,
          owner_uid: props.ownerUid
        }
        addSteadToOwnerUser(data)
          .then(() => {
          })
          .finally(() => {
            loading.value = false
            emit('success')
          })
      }
    }
    return {
      loading,
      dialogVisible,
      addStead
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
