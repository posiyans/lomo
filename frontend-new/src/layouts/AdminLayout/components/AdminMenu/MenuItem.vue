/* eslint-disable */
<template>
  <q-item v-if="show" clickable v-ripple :active="active" :to="url">
    <q-item-section avatar>
      <q-icon :name="iconName" />
    </q-item-section>

    <q-item-section>
      {{ item.meta.title }}
    </q-item-section>
  </q-item>
  <q-separator v-if="show" />
  <div v-for="child in item.children" :key="child.path" class="q-pl-sm" >
    <menu-item :item="child" :base-path="url" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref, computed, reactive, onMounted, onUpdated } from 'vue'
import { useQuasar } from 'quasar'
import { useRouter, useRoute } from 'vue-router'


export default defineComponent({
  name: 'MenuItem',
  props: {
    item: Object,
    basePath: {
      type: String,
      default: ''
    }
  },
  setup (props) {
    const $q = useQuasar()
    const router = useRouter()
    const route = useRoute()
    const iconName = computed(() => {
      return props.item.meta.icon || 'inbox'
    })
    const active = computed(() => {
      return findActive(props.item)
    })
    const show = computed(() => {
      return !props.item.hidden && props.item.meta && props.item.meta.title || false
    })
    const findActive = (val) => {
      // if (val.children) {
      //   return val.children.find(item => {
      //     return findActive(item)
      //   })
      // }
      return route.path === url.value
    }
    const url = computed(() => {
      let path = props.basePath
      if (path.slice((-1) !== '/') && props.item.path.substr(0,1) !== '/') {
        path += '/'
      }
      path += props.item.path
      return path
    })
    return {
      show,
      iconName,
      active,
      url
    }
  }
})
</script>

<style scoped>
  :deep(.q-btn.btn--no-hover .q-focus-helper) {
    display: none;
  }
</style>
