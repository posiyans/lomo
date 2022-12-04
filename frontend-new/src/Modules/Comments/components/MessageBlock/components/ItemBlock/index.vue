<template>
  <div>
    <div class="row items-center no-wrap relative-position rounded-borders">
      <div class="q-px-sm">
        <div class="text-center q-pt-sm">
          <UserAvatar :uid="item.user.id" size="40px" />
        </div>
      </div>
      <div class="col-grow">
        <div class="row items-center">
          <div class="text-weight-bold text-primary q-mr-sm">
            {{item.user.name}}
          </div>
          <ShowTime :time="showTime" class="text-grey-8" style="font-size: 0.9em"/>
        </div>
        <div v-html="item.message" class="q-py-sm" />
        <q-separator/>
      </div>

      <div class="q-px-sm absolute-bottom-right text-grey-8 row ">
        <div v-if="edit" class="q-pr-sm">
          ред.
        </div>
      </div>
      <div v-if="deleteAccess" class="absolute-top-right text-red q-pa-sm cursor-pointer" @click="deleteItem">
        <i class="el-icon-delete-solid" />
      </div>
    </div>

  </div>
</template>

<script>
import UserAvatar from 'src/Modules/Avatar/components/UserAvatar/index.vue'
import { deleteMessage } from 'src/Modules/Comments/api/comment'
import ShowTime from 'src/components/ShowTime/index.vue'
export default {
  components: {
    UserAvatar,
    ShowTime
  },
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  computed: {
    edit () {
      return this.item.created_at !== this.item.updated_at
    },
    showTime () {
      return this.edit ? this.item.updated_at : this.item.created_at
    },
    // user() {
    //   if (this.$store.getters.user.allPermissions.includes('guest')) {
    //     return false
    //   }
    //   return this.$store.getters.user
    // },
    deleteAccess () {
      // if (this.user) {
      //   if (this.$store.getters.user.allPermissions.includes('delete-comment')) {
      return true
      //   }
      //   return this.user.info.id === this.item.user_id
      // }
      // return false
    }
  },
  methods: {
    deleteItem () {
      this.$confirm('Вы точно хотите удалить комментарий?', 'Внимание!!!', {
        confirmButtonText: 'Удалить',
        cancelButtonText: 'Отмена',
        type: 'warning'
      }).then(() => {
        deleteMessage(this.item.uid)
          .then(response => {
            this.$emit('reload')
          })
      })
    }
  }
}
</script>

<style scoped>

</style>
