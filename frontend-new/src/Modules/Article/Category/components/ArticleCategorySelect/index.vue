<template>
  <div>
    <div v-if="loading" class="items-center" style="width: 200px;">
      Loading...
    </div>
    <div v-else>
      <q-select
        :modelValue="modelValue"
        :outlined="outlined"
        :options="options"
        :label="label"
        map-options
        :dense="dense"
        :clearable="clearable"
        option-value="id"
        option-label="name"
        emit-value
        @update:modelValue="setValue"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import { fetchCategoryList } from 'src/Modules/Article/Category/api/category'
import { LocalStorage } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: 'Категория'
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
    onlyPublic: {
      type: Boolean,
      default: false
    },
    setDefault: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const options = ref([])
    const keyName = 'ArticleCategoryList'
    const loading = ref(false)
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    const getCategory = () => {
      loading.value = true
      const data = {
        markDefault: true
      }
      if (props.onlyPublic) {
        data.public = true
      }
      fetchCategoryList(data)
        .then(response => {
          options.value = response.data
          LocalStorage.set(keyName, options.value)
          loading.value = false
          if (props.setDefault) {
            options.value.forEach(item => {
              if (item.default) {
                emit('update:modelValue', item.id)
              }
            })
          }
        })
    }
    onMounted(() => {
      getCategory()
      if (LocalStorage.has(keyName)) {
        loading.value = false
        options.value = LocalStorage.getItem(keyName)
        if (props.setDefault) {
          options.value.forEach(item => {
            if (item.default) {
              emit('update:modelValue', item.id)
            }
          })
        }
      }
    })
    return {
      options,
      loading,
      setValue
    }
  }
})
</script>

<style scoped>

</style>
