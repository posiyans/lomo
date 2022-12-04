<template>
  <div>
    <div v-if="ban" class="red no-send " align="middle">
      Вам запрещено оставлять комментарии!!!
    </div>
    <div v-if="user" class="row items-center">
      <UserAvatar :uid="user.id" class="q-px-sm" />
      <div class="col-grow">
        <el-input
          v-model="newComment"
          type="textarea"
          autosize
          placeholder="Написать комментарий..."
        />
      </div>
      <div>
        <div class="q-px-sm cursor-pointer" style="font-size: 2em;" @click="sendComment">
          <i :class="icon" />
        </div>
      </div>
    </div>
    <div v-else class="text-center q-pa-md text-weight-bold">
      Чтоб оставлять комментариии необходимо войти на сайт
    </div>
  </div>
</template>

<script>
import UserAvatar from '@/Modules/Avatar/components/UserAvatar'
import { sendMessage } from '@/Modules/Comments/api/comment'

export default {
  components: {
    UserAvatar
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
      newComment: '',
      items: [],
      send: false
    }
  },
  computed: {
    icon() {
      return this.send ? 'el-icon-loading' : 'el-icon-s-promotion'
    },
    user() {
      if (this.$store.getters.user.allPermissions.includes('guest')) {
        return false
      }
      return this.$store.getters.user
    },
    ban() {
      if (this.$store.getters.user.allPermissions.includes('ban-comment')) {
        return true
      }
      return false
    }
  },
  methods: {
    sendComment() {
      if (this.newComment) {
        this.send = true
        const data = {
          type: this.type,
          uid: this.objectUid,
          message: this.newComment
        }
        sendMessage(data)
          .then(response => {
            this.newComment = ''
          })
          .catch(res => {
            this.$message({
              message: 'Не удалось добавить комментарий',
              type: 'error'
            })
          })
          .finally(() => {
            this.send = false
            this.$emit('reload')
          })
      }
    }
  }
}
</script>

<style scoped>
</style>
