<template>
  <div>
    <div v-for="item in messages" :key="item.id" class="q-mb-sm">
      <ItemBlock :item="item" />
    </div>
    <div v-if="loading" class="text-center text-primary q-pa-sm text-weight-bold">
      Загрузка ...
    </div>
  </div>
</template>

<script>
import { getMessage } from '@/Modules/Comments/api/comment'
import ItemBlock from './components/ItemBlock'

export default {
  components: {
    ItemBlock
  },
  props: {
    type: {
      type: String,
      default: 'article'
    },
    objectUid: {
      type: [String, Number],
      required: true
    },
    reload: {
      type: [Number, String],
      default: 1
    }
  },
  data() {
    return {
      messages: [],
      loading: false
    }
  },
  watch: {
    reload() {
      this.getData()
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      this.loading = true
      const data = {
        type: this.type,
        uid: this.objectUid
      }
      getMessage(data)
        .then(res => {
          this.messages = res.data.data
          console.log(res.data)
          console.log(this.messages)
        }).finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>

</style>
