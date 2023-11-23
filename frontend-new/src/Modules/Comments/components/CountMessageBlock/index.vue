<template>
  <div>
    <slot>
      <q-btn
        color="grey-2"
        text-color="primary"
        icon="message"
        :label="label"
        unelevated
      />
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getMessage } from 'src/Modules/Comments/api/commentApi'

export default defineComponent({
  components: {},
  props: {
    type: {
      type: String,
      required: true
    },
    uid: {
      type: String,
      required: true
    }
  },
  setup(props) {
    const count = ref({
      data: []
    })
    const router = useRouter()
    const route = useRoute()
    const label = computed(() => {
      if (count.value && count.value.data.length > 0) {
        return count.value.data.length
      }
      return ''
    })
    onMounted(() => {
      const data = {
        type: props.type,
        uid: props.uid
      }
      getMessage(data)
        .then(res => {
          count.value = res.data
        })
    })
    return {
      count,
      label
    }
  }
})
</script>

<style scoped>

</style>
