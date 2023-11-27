<template>
  <div class="row items-center">
    <q-icon :name="item.icon || 'menu'" color="primary" size="28px" class="q-mr-sm" />
    <div>
      <div class="text-primary">
        {{ numberItem }} {{ item.label }}
      </div>
      <div class="text-grey text-small-80">
        {{ item.path }}
      </div>
    </div>
    <div v-if="!item.readonly" class="q-ml-lg">
      <EditSiteMenuForm :model-value="item" @success="success" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import EditSiteMenuForm from 'src/Modules/SiteMenu/components/EditSiteMenuForm/Btn.vue'

export default defineComponent({
  components: {
    EditSiteMenuForm
  },
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const numberItem = computed(() => {
      if (props.item.parent_sort) {
        return props.item.parent_sort + '.' + props.item.sort
      } else {
        return props.item.sort
      }
    })
    const success = () => {
      emit('success')
    }
    return {
      data,
      numberItem,
      success
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
