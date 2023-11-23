<template>
  <div>
    {{ userUid }}
    {{ list }}
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getListOfOwnerStead } from 'src/Modules/Stead/api/stead'

export default defineComponent({
  components: {},
  props: {
    ownerUid: {
      type: String,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const list = ref([])
    const router = useRouter()
    const route = useRoute()
    const getData = () => {
      const data = {
        'user_uid': props.userUid
      }
      getListOfOwnerStead(data)
        .then(res => {
          list.value = res.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      list
    }
  }
})
</script>

<style scoped>

</style>
