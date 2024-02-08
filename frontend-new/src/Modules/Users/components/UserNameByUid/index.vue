<template>
  <div v-if="uid">
    <div v-if="loading">
      <q-skeleton type="text" />
    </div>
    <div v-else>
      {{ before }}{{ user.last_name }}
      {{ user.name }}
      {{ user.middle_name }}{{ after }}
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref, watch } from 'vue'
import { fetchUserInfo } from 'src/Modules/Users/api/user-admin-api'

export default defineComponent({
  components: {},
  props: {
    uid: {
      type: String,
      default: ''
    },
    before: {
      type: String,
      default: ''
    },
    after: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const loading = ref(false)
    const user = ref({})
    const getData = () => {
      if (props.uid) {
        loading.value = true
        const data = {
          uid: props.uid
        }
        fetchUserInfo(data)
          .then(res => {
            user.value = res.data.data
          })
          .finally(() => {
            loading.value = false
          })
      }
    }
    watch(
      () => props.uid,
      () => getData()
    )
    onMounted(() => {
      getData()
    })
    return {
      user,
      loading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

