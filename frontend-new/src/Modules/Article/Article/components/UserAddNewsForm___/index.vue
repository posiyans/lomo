<template>
  <div>
    <div>
      <ArticleCategorySelect :modelValue="modelValue.category_id" />
    </div>
    <div class="q-mb-md" style="width: 100%;">
      <q-input :model-value="modelValue.title" no-caps outlined label="Заголовок" />
    </div>
    <Tinymce :id="id" ref="editor" :modelValue="modelValue.text" :height="400" />
  </div>
</template>

<script>
import Tinymce from './components/Tinymce/index.vue'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect/index.vue'
import { uid } from 'quasar'

export default {
  components: { Tinymce, ArticleCategorySelect },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      id: uid()
    }
  }
}
</script>

<style scoped>

</style>
