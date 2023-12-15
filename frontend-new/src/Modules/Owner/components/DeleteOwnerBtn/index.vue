<template>
  <q-fab-action color="negative" text-color="red" flat icon="delete" @click="dialogVisible = true" />
  <q-dialog v-model="dialogVisible">
    <q-card>
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Удалить собственника</div>
        <q-space />
        <q-btn icon="close" flat round dense v-close-popup />
      </q-card-section>

      <q-card-section>
        <div class="q-pa-lg">
          Удалить собственника ивсе его данные из системы?
        </div>
        <div class="text-right q-pt-lg">
          <q-btn color="negative" icon="delete" label="Удалить">
            <q-popup-proxy>
              <q-banner>
                <template v-slot:avatar>
                  <q-icon name="info" color="primary" />
                </template>
                Я подтверждаю свое действие
                <template v-slot:action>
                  <q-btn flat color="negative" label="Удалить" @click="deleteOwner" />
                </template>
              </q-banner>
            </q-popup-proxy>
          </q-btn>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>

</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { deleteOwnerUser } from 'src/Modules/Owner/api/ownerUserApi'

export default defineComponent({
  components: {},
  props: {
    ownerUid: {
      type: String,
      required: true
    }
  },
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const deleteOwner = () => {
      deleteOwnerUser(props.ownerUid)
        .then(() => {
          dialogVisible.value = false
          emit('success')
        })
    }
    return {
      dialogVisible,
      deleteOwner
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
