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
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum repellendus sit voluptate voluptas eveniet porro. Rerum blanditiis perferendis totam, ea at omnis vel numquam exercitationem aut,
        natus minima, porro labore.
      </q-card-section>
    </q-card>
  </q-dialog>

</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    return {
      dialogVisible
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
