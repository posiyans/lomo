<template>
  <div>
    <q-btn icon="edit_note" flat size="sm" @click="showEditForm" />
    <q-dialog v-model="dialogVisible">
      <q-card v-if="owner.user_uid" style="min-width: 450px;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6 text-negative">Удалить пользователя</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <div class="text-center q-gutter-md">
            <div>
              Отвязать пользователя
            </div>
            <div class="row items-center justify-center q-col-gutter-md">
              <div>
                <UserAvatarByUid :uid="owner.user_uid" />
              </div>
              <div>
                <UserNameByUid :uid="owner.user_uid" />
              </div>
            </div>
            <div>
              от Собственника
            </div>
            <div class="text-weight-bold text-secondary">{{ owner?.surname }} {{ owner?.name }} {{ owner?.middle_name }}</div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <div class="q-gutter-sm">
            <q-btn label="Отмена" color="negative" flat v-close-popup />
            <q-btn label="Отвязать" color="negative" @click="deleteUser" />
          </div>
        </q-card-actions>
      </q-card>
      <q-card v-else style="min-width: 650px;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Найти пользователя</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <div style="min-height: 350px;">
            <FindUser v-model="user" class="q-pb-sm" />
            <div v-if="user" class="text-center q-gutter-md">
              <div>
                Собственник
              </div>
              <div class="text-weight-bold text-secondary">{{ owner?.surname }} {{ owner?.name }} {{ owner?.middle_name }}</div>
              <div>
                это пользователь в системе
              </div>
              <div class="row items-center justify-center">
                <div>
                  <UserAvatarByUid :uid="user.uid" />
                </div>
                <div>
                  <div class="text-weight-bold text-negative">
                    {{ user.last_name }}
                    {{ user.name }}
                    {{ user.middle_name }}
                  </div>
                  <div>
                    {{ user.email }}
                  </div>
                  <div>
                    {{ user.options.phone }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div v-if="owner.user_uid">
              <q-btn label="Удалить" color="negative" />
            </div>
            <q-space />
            <div>
              <q-btn label="Отмена" color="negative" flat v-close-popup />
              <q-btn label="Сопоставить" :disable="!user" color="primary" @click="saveData" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import FindUser from 'src/Modules/Users/components/FindUser/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import { compareOwnerAndUser, deleteUserFromOwner } from 'src/Modules/Owner/api/ownerUserApi'
import { errorMessage, successMessage } from 'src/utils/message'
import UserNameByUid from 'src/Modules/Users/components/UserNameByUid/index.vue'

export default defineComponent({
  components: {
    FindUser,
    UserAvatarByUid,
    UserNameByUid
  },
  props: {
    owner: {
      type: Object,
      required: true
    }
  },
  emits: ['success'],
  setup(props, { emit }) {
    const user = ref(null)
    const dialogVisible = ref(false)
    const inputHint = ref(undefined)
    const list = ref([])
    const showEditForm = () => {
      user.value = null
      dialogVisible.value = true
    }
    const deleteUser = () => {
      const data = {
        owner_uid: props.owner.uid
      }
      deleteUserFromOwner(data)
        .then(res => {
          successMessage('Успех')
          dialogVisible.value = false
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }
    const saveData = () => {
      const data = {
        owner_uid: props.owner.uid,
        user_uid: user.value.uid
      }
      compareOwnerAndUser(data)
        .then(res => {
          successMessage('Успех')
          dialogVisible.value = false
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }
    return {
      saveData,
      deleteUser,
      dialogVisible,
      list,
      inputHint,
      user,
      showEditForm
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
