/* eslint-disable */
<template>
  <q-item v-if="show" clickable v-ripple :active="active" :to="url" @click="actionShow">
    <q-item-section avatar>
      <q-icon :name="iconName" />
    </q-item-section>
    <q-item-section>
      {{ item.meta.title }}
    </q-item-section>
    <q-item-section avatar v-if="item.children && item.children.length > 0">
      <q-icon color="grey" name="keyboard_arrow_down" :class="{ 'rotate-180': showSubmenu,'rotate-0': !showSubmenu}" />
    </q-item-section>
  </q-item>
  <q-separator v-if="show" />
  <q-slide-transition>
    <div v-if="showSubmenu" class="bg-blue-grey-3">
      <div v-for="child in item.children" :key="child.path" class="q-pl-sm">
        <menu-item :item="child" :base-path="url" />
      </div>
    </div>
  </q-slide-transition>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'


export default defineComponent({
  name: 'MenuItem',
  props: {
    item: Object,
    basePath: {
      type: String,
      default: '/'
    }
  },
  setup(props) {
    const showSubmenu = ref(false)
    const $q = useQuasar()
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    const iconName = computed(() => {
      return props.item.meta.icon || 'inbox'
    })
    const active = computed(() => {
      return findActive(props.item)
    })
    const show = computed(() => {
      if (!props.item.hidden && props.item.meta && props.item.meta.title) {
        if (props.item.meta.roles) {
          let access = false
          authStore.permissions.forEach(item => {
            if (props.item.meta.roles.includes(item)) {
              access = true
            }
          })
          return access
        }
        return true
      } else {
        return false
      }
    })
    const findActive = (val) => {
      if (val.children) {
        return val.children.find(item => {
          return findActive(item)
        })
      }
      return route.path === url.value
    }
    const url = computed(() => {
      let path = props.basePath
      if (path.slice((-1) !== '/') && props.item.path.substr(0, 1) !== '/') {
        path += '/'
      }
      path += props.item.path
      return path
    })

    const actionShow = () => {
      showSubmenu.value = !showSubmenu.value
    }
    return {
      actionShow,
      show,
      iconName,
      showSubmenu,
      active,
      url
    }
  }
})
</script>

<style scoped>
.rotate-180 {
  animation: rotate-180;
  animation-duration: 0.3s;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}

@keyframes rotate-180 {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(180deg);
  }
}

.rotate-0 {
  animation: rotate-0;
  animation-duration: 0.3s;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}

@keyframes rotate-0 {
  from {
    transform: rotate(180deg);
  }
  to {
    transform: rotate(0deg);
  }
}
</style>
