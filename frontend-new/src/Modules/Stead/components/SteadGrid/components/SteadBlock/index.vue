<template>
  <div class="stead-grid-block row justify-center q-py-sm text-center">
    <div v-if="loading">
      <q-spinner-dots
        color="grey"
        size="1em"
      />
    </div>
    <div v-else>
      <div :style="styleName" class="cursor-pointer">
        {{ stead.number }}
      </div>
      <div class="text-small-80">
        {{ after }}
      </div>
      <q-tooltip v-if="tooltip">
        {{ tooltip }}
      </q-tooltip>

    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {
    stead: {
      type: Object,
      required: true
    },
    color: {
      type: String,
      default: 'black'
    },
    tooltip: {
      type: String,
      default: ''
    },
    after: {
      type: String,
      default: ''
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const styleName = computed(() => {
      const style = {}
      if (props.color) {
        style.color = props.color
      }
      return style
    })
    onMounted(() => {

    })
    return {
      data,
      styleName
    }
  }
})
</script>

<style scoped>
.stead-grid-block {
  width: 150px;
  /*height: 35px;*/
  border: 1px solid black;
  margin-left: -1px;
  margin-top: -1px;
  /*text-align: center;*/
  /*padding: 10px 0;*/
}
</style>
