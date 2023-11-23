<template>
  <div>
    <q-select
      :modelValue="modelValue"
      :outlined="outlined"
      :options="showMenu"
      :label="label"
      map-options
      :dense="dense"
      :clearable="clearable"
      option-value="id"
      option-label="label"
      emit-value
      @update:modelValue="setValue"
    >
      <template v-slot:option="scope">
        <q-item v-bind="scope.itemProps" :disable="scope.opt.disable" :clickable="!scope.opt.disable">
          <q-item-section>
            <q-item-label>
              <div class="row items-center">
                <div v-for="(i, index) in scope.opt.index" :key="index" style="width: 15px; border-bottom: 1px solid black;"></div>
                <div>
                  {{ scope.opt.label }}
                </div>
              </div>
            </q-item-label>
          </q-item-section>
        </q-item>
      </template>
    </q-select>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenuStore'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: 'Раздел'
    },
    clearable: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    optionDisable: {
      type: Array,
      default: () => []
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const siteMenuStore = useSiteMenuStore()
    const showMenu = computed(() => {
      return tt.value.map(item => {
        item.disable = props.optionDisable.includes(item.id)
        return item
      }).filter(item => {
        return !item.readOnly
      })
    })
    const getChildren = (item, index = 0) => {
      const tmp = []
      item.forEach(i => {
        i.index = index
        tmp.push(i)
        if (i.children) {
          const cldIndex = index + 1
          getChildren(i.children, cldIndex).forEach(ch => {
            tmp.push(ch)
          })
        }
      })
      return tmp
    }
    const tt = computed(() => {
      return getChildren(siteMenuStore.menu, 0)
    })
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    return {
      data,
      setValue,
      siteMenuStore,
      showMenu,
      tt

    }
  }
})
</script>

<style scoped>

</style>
