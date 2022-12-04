<template>
  <q-avatar :square="square" :rounded="rounded" :size="size">
    <q-img :src="url" spinner-color="white">
      <template v-slot:loading>
        <q-spinner-gears color="white" size="80%" />
      </template>
    </q-img>
  </q-avatar>
</template>

<script>

export default {
  props: {
    uid: {
      type: [String, Number],
      required: true
    },
    size: {
      type: String,
      default: '150px'
    },
    square: {
      type: Boolean,
      default: false
    },
    rounded: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    url () {
      return process.env.BASE_API + '/api/v2/avatar/get-user/?uid=' + this.uid
    }
  }
}
</script>

<style scoped>

</style>
