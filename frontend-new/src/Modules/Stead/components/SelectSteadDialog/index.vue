<template>
  <div>
    <q-btn icon="add" flat color="primary" @click="showDialogAction" />
    <q-dialog v-model="steadAddDialogVisible">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Добавить участок</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <SelectStead v-model="steadId" outlined />
        </q-card-section>
        <q-card-section>
          <div v-if="steadId">
            <div class="row q-col-gutter-sm">
              <div>
                Участок №:
              </div>
              <SteadInfo :stead-id="steadId" />
            </div>
            <div class="row q-col-gutter-sm">
              <div>
                Размер:
              </div>
              <SteadInfo :stead-id="steadId" format="s" />
              <div>
                кв.м
              </div>
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div class="text-right">
            <q-btn label="Добавить" color="primary" @click="add" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import SelectStead from 'src/Modules/Stead/components/SelectStead/index.vue'
import SteadInfo from 'src/Modules/Stead/components/SteadInfo/index.vue'

export default defineComponent({
  components: {
    SelectStead,
    SteadInfo
  },
  props: {},
  setup(props, { emit }) {
    const steadAddDialogVisible = ref(false)
    const steadId = ref('')
    const showDialogAction = () => {
      steadId.value = ''
      steadAddDialogVisible.value = true
    }
    const add = () => {
      emit('add-stead', steadId.value)
      steadAddDialogVisible.value = false
    }
    return {
      steadAddDialogVisible,
      showDialogAction,
      steadId,
      add
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
