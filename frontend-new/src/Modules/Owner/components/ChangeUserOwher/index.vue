<template>
  <div>
    <q-btn icon="edit_note" flat size="sm" @click="showEditForm" />
    <q-dialog v-model="dialogVisible" full-width>
      <q-card>
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
          <div class="text-right">
            <q-btn label="Сопоставить" :disable="!user" color="primary" />
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

export default defineComponent({
  components: {
    FindUser,
    UserAvatarByUid
  },
  props: {
    owner: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const user = ref(null)
    const dialogVisible = ref(false)
    const inputHint = ref(undefined)
    const list = ref([])
    const showEditForm = () => {
      user.value = null
      dialogVisible.value = true
    }

    return {
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
