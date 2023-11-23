<template>
  <div>
    <div @click.prevent="dialogVisible = true">
      <q-btn label="Редактировать" flat dense />
    </div>
    <q-dialog
      v-model="dialogVisible"
      :maximized="$q.platform.is.mobile"
      @hide="$emit('hide')"
    >
      <q-card style="min-width: 350px;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Редактировать</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <EditSiteMenuForm :model-value="modelValue" @update:model-value="updateValue" @success="success" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import MenuSiteSelect from 'src/Modules/SiteMenu/components/MenuSiteSelect/index.vue'
import EditSiteMenuForm from 'src/Modules/SiteMenu/components/EditSiteMenuForm/index.vue'

export default defineComponent({
  components: {
    MenuSiteSelect,
    EditSiteMenuForm
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const updateValue = (val) => {
      emit('update:model-value', val)
    }
    const success = () => {
      dialogVisible.value = false
      emit('success')
    }

    return {
      dialogVisible,
      updateValue,
      success
    }
  }
})
</script>

<style scoped>

</style>
