<template>
  <div v-if="!loading" class="q-pa-sm">
    <q-card>
      <q-card-section class="q-pb-none">
        <div class="row items-center">
          <div class="row items-center q-col-gutter-sm">
            <div>
              <div class="text-primary">
                {{ appeal.title }}
              </div>
              <div class="text-small-75">
                {{ appeal.user.fullName }}
              </div>
            </div>
            <q-chip class="text-teal">
              {{ appeal.type.label }}
            </q-chip>
          </div>
          <q-space />
          <q-chip>
            <AppealStatusLabelById :status="appeal.status" add-color />
          </q-chip>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <div class="q-gutter-md">
          <div>
            {{ appeal.text }}
          </div>
        </div>
      </q-card-section>
      <q-separator />
      <q-card-section>
        <CommentBlock
          type="appeal"
          :object-uid="appeal.uid"
          no-delete
          no-ban
        />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getAppeal } from 'src/Modules/Appeal/api/appealApi'
import CommentBlock from 'src/Modules/Comments/components/CommentsBlock/index.vue'
import AppealStatusLabelById from 'src/Modules/Appeal/components/AppealStatusLabelById/index.vue'
import AppealTypeNameById from 'src/Modules/Appeal/components/AppealTypeNameById/index.vue'

export default defineComponent({
  components: {
    CommentBlock,
    AppealStatusLabelById,
    AppealTypeNameById
  },
  props: {},
  setup(props, { emit }) {
    const appeal = ref({})
    const loading = ref(true)
    const router = useRouter()
    const route = useRoute()
    const getData = () => {
      getAppeal(route.params.id)
        .then(res => {
          appeal.value = res.data.appeal
          loading.value = false
        })
    }
    onMounted(() => {
      console.log(route.params.id)
      getData()
    })
    return {
      appeal,
      loading
    }
  }
})
</script>

<style scoped>

</style>
