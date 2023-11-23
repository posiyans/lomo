<template>
  <div @click="deleteBan">
    <slot>
      <q-btn color="negative" icon="delete" flat dense />
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted } from 'vue'
import { deleteUserBan } from 'src/Modules/BanUsers/api/apiBanUser'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    ban: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const $q = useQuasar()
    const deleteBan = () => {
      $q.dialog({
        title: 'Снять бан?',
        message: 'Удалить ' + props.ban.user.name + ' из бана?',
        cancel: true,
        ok: {
          label: 'Удалить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        const data = {
          ban_uid: props.ban.uid
        }
        deleteUserBan(data)
          .then(res => {
            emit('success')
          })
      })
    }
    onMounted(() => {

    })
    return {
      deleteBan
    }
  }
})
</script>

<style scoped>

</style>
