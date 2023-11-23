<template>
  <div>
    <q-btn icon="settings" dense flat @click="showDialogAction" />
    <q-dialog v-model="dialogVisible" persistent>
      <q-card style="min-width: 350px">
        <q-card-section>
          <div class="text-h6">Получить ссылку на</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-select
            v-model="model"
            :options="lineList"
            label="Выбрать"
            @update:model-value="sendData"
          />
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <q-btn flat label="Отмена" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import routes from 'src/router/routes.js'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const model = ref(null)
    const dat = ref(routes)
    const lineList = computed(() => {
      const tmp = []
      dat.value.forEach(item => {
        parseChildren(item, item.path).forEach(i => {
          tmp.push(i)
        })
      })
      return tmp
    })
    const parseChildren = (item, basePath = '') => {
      let path = basePath + '/' + item.path
      if (item.path.slice(0, 1) === '/') {
        path = item.path
      } else if (item.path === '') {
        path = basePath
      }
      const tmp = []

      if (item?.meta?.roles && item.meta.roles.length > 0) {

      } else {
        if (item?.meta?.title) {
          tmp.push({
              path,
              label: item?.meta?.title
            }
          )
        }
        if (item.children) {
          item.children.forEach(child => {
            parseChildren(child, path).forEach(i => {
              tmp.push(i)
            })
          })
        }
      }
      return tmp
    }
    const showDialogAction = () => {
      model.value = null
      dialogVisible.value = true

    }
    const sendData = (val) => {
      dialogVisible.value = false
      emit('select', val)
    }
    return {
      dialogVisible,
      showDialogAction,
      model,
      sendData,
      lineList
    }
  }
})
</script>

<style scoped>

</style>
