<template>
  <div>
    <MessageBlock :type="type" :object-uid="objectUid" :reload="key" />
    <div v-if="ban" class="red no-send " align="middle">
      Вам запрещено оставлять комментарии.
    </div>
    <SendCommentBlock :type="type" :object-uid="objectUid" @reload="reload" />
  </div>
</template>

<script>
import SendCommentBlock from '@/Modules/Comments/components/SendCommentBlock'
import MessageBlock from '@/Modules/Comments/components/MessageBlock'
export default {
  components: {
    SendCommentBlock,
    MessageBlock
  },
  props: {
    type: {
      type: String,
      default: 'article'
    },
    objectUid: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      key: 1
    }
  },
  computed: {
    ban() {
      if (this.$store.getters.user.allPermissions.includes('ban-comment')) {
        return true
      }
      return false
    }
  },
  methods: {
    reload() {
      this.key++
    }
  }
}
</script>

<style scoped>

</style>
