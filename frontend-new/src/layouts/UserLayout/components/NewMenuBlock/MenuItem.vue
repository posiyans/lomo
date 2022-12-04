/* eslint-disable */
<template>
  <div>
    <div v-if="props.item.children">
      <q-btn-dropdown no-caps no-wrap flat :label="props.item.label"  align="left" :class="className"  >
        <q-list class="bg-blue-grey-9 text-white">
          <q-item v-for="children in props.item.children" :key="children.id" clickable class="q-pa-none" >
            <q-item-section>
              <menu-item :item="children" parent />
            </q-item-section>
          </q-item>
        </q-list>
      </q-btn-dropdown>
    </div>
    <div v-else>
      <q-btn  no-caps no-wrap flat :label="props.item.label" align="left" :class="className" @click="push"/>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref, computed, reactive, onMounted, onUpdated } from 'vue'
import { useQuasar } from 'quasar'
import { useRouter, useRoute } from 'vue-router'


export default defineComponent({
  props: {
    item: Object,
    parent: {
      type: Boolean,
      default: false
    },
    vertical: {
      type: Boolean,
      default: false
    }
  },
  setup (props) {
    const $q = useQuasar()
    const router = useRouter()
    const route = useRoute()

    const findActive = (val) => {
      if (val.children) {
        return val.children.find(item => {
          return findActive(item)
        })
      }
      return route.path === val.basePath
    }
    const push = () => {
      router.push(props.item.basePath)
    }
    const className = computed(() => {
      let result = {
        'btn--no-hover': props.parent,
        'text-yellow-8': findActive(props.item),
        'full-width': props.vertical
      }
      return result
    })
    return {
      props,
      push,
      className
    }
  }
})
</script>

<style scoped>
  :deep(.q-btn.btn--no-hover .q-focus-helper) {
    display: none;
  }
</style>
