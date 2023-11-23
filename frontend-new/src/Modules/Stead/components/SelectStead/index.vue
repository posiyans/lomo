<template>
  <div>
    <q-select
      :model-value="modelValue"
      :options="options"
      :label="label"
      :loading="loading"
      use-input
      hide-selected
      fill-input
      map-options
      :clearable="clearable"
      :dense="dense"
      :outlined="outlined"
      emit-value
      transition-show="jump-up"
      transition-hide="jump-up"
      option-label="number"
      option-value="id"
      @filter="filterFn"
      @update:model-value="setValue"
      @clear="setValue('')"
      @input-value="test"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getSteadsList } from 'src/Modules/Stead/api/stead.js'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      default: 'Участок'
    },
    clearable: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
    autoSelect: {
      type: Boolean,
      default: false
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const SteadsList = ref([])
    const options = ref([])
    const key = ref(1)
    const loading = ref(false)
    const router = useRouter()
    const route = useRoute()
    const getData = () => {
      loading.value = true
      // const data = {
      //   id: props.modelValue
      // }
      getSteadsList()
        .then(response => {
          SteadsList.value = response.data.data
          options.value = response.data.data
          if (props.autoSelect && SteadsList.value.length === 1) {
            setValue(this.SteadsList[0].id)
          }
          loading.value = false
          key.value++
        })
    }
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    const test = (val) => {
      console.log(val)
      // emit('update:modelValue', val)
    }
    const filterFn = (val, update, abort) => {
      console.log(val)
      update(() => {
        const needle = val.toLowerCase()
        options.value = SteadsList.value.filter(v => v.number.toLowerCase().indexOf(needle) > -1)
        if (props.autoSelect && options.value.length === 1) {
          setValue(options.value[0].id)
        }
      })
      // const data = {
      //   find: val
      // }
      // this.loading = true
      // getSteadsList(data)
      //   .then(response => {
      //     this.SteadsList = response.data.data
      //     if (this.autoSelect && this.SteadsList.length === 1) {
      //       this.setValue(this.SteadsList[0].id)
      //     }
      //     this.loading = false
      //     update()
      //   })
    }
    onMounted(() => {
      getData()

      // if (this.modelValue) {
      //   this.loading = true
      //   const data = {
      //     id: this.modelValue
      //   }
      //   getSteadsList(data)
      //     .then(response => {
      //       this.SteadsList = response.data.data
      //       if (this.autoSelect && this.SteadsList.length === 1) {
      //         this.setValue(this.SteadsList[0].id)
      //       }
      //       this.loading = false
      //       this.key++
      //     })
      // }
    })
    return {
      data,
      test,
      SteadsList,
      filterFn,
      setValue,
      options,
      key,
      loading
    }
  },
  methods: {
    // filterFn(val, update, abort) {
    //   const data = {
    //     find: val
    //   }
    //   this.loading = true
    //   getSteadsList(data)
    //     .then(response => {
    //       this.SteadsList = response.data.data
    //       if (this.autoSelect && this.SteadsList.length === 1) {
    //         this.setValue(this.SteadsList[0].id)
    //       }
    //       this.loading = false
    //       update()
    //     })
    // },
    findStead(val = '') {
      const data = {
        find: val
      }
      getSteadsList(data)
        .then(response => {
          this.SteadsList = response.data.data
        })
    }
  }
})
</script>

<style scoped>

</style>
