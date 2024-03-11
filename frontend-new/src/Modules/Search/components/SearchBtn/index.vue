<template>
  <div>
    <div class="row ellipsis">

      <transition
        enter-active-class="animated backInRight"
        leave-active-class="animated backOutRight"
      >
        <div
          v-if="inputVisible"
          class="ellipsis"
        >
          <q-input
            v-model="find"
            dense
            bg-color="white"
            outlined
            @keydown.enter="findAction"
          >
            <template v-slot:append>
              <q-icon v-if="find !== ''" name="close" @click="find = ''" class="cursor-pointer" />
              <q-icon name="search" @click="findAction" />
            </template>
          </q-input>
        </div>
      </transition>
      <q-btn v-if="!inputVisible" icon="search" flat @click="showInput" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const inputVisible = ref(false)
    const data = ref(false)
    const router = useRouter()
    const find = ref('')
    const showInput = () => {
      inputVisible.value = !inputVisible.value
    }
    const findAction = () => {
      router.push('/search?find=' + find.value)
      inputVisible.value = false
      find.value = ''
    }
    return {
      data,
      inputVisible,
      showInput,
      findAction,
      find
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
