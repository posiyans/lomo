<template>
  <div class="row items-center q-col-gutter-md">
    <div class="filter-item">
      <q-input
        :model-value="listQuery.find"
        outlined
        dense
        label="Найти"
        clearable
        @update:model-value="updateFind"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  components: {},
  props: {
    listQuery: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const updateFind = (val) => {
      const tmp = Object.assign({}, props.listQuery)
      tmp.find = val
      emit('update:list-query', tmp)
    }
    return {
      updateFind
    }
  }
})
</script>

<style scoped>

</style>
