<template>
  <div>
    <el-avatar v-if="url" :src="url" />
  </div>
</template>

<script>
import { getAvatarUrl } from '@/Modules/Avatar/api/avatar'

export default {
  props: {
    uid: {
      type: [String, Number],
      required: true
    },
    size: {
      type: String,
      default: '200px'
    }
  },
  data() {
    return {
      url: null
    }
  },
  mounted() {
    this.getData()
  },
  // computed: {
  // url__d() {
  //   return process.env.VUE_APP_BASE_API + '/api/v2/avatar/get-user/' + this.uid
  // }
  // },
  methods: {
    getData() {
      getAvatarUrl(this.uid)
        .then(res => {
          this.url = res.data
        })
    }
  }
}
</script>

<style scoped>

</style>
