<template>
  <div>
    <div class="row items-center q-mb-md">
      <div class="q-mr-sm">
        Раздел
      </div>
      <div>
        <ArticleCategorySelect v-model="value.category_id" />
      </div>
    </div>
    <div class="q-mb-md" style="width: 100%;">
      <MDinput v-model="value.title" :maxlength="100" name="name" required>
        Заголовок
      </MDinput>
    </div>
    <Tinymce :id="id" ref="editor" v-model="value.text" :height="400" />
  </div>
</template>

<script>
import Tinymce from './components/Tinymce'
import MDinput from '@/components/MDinput'
import ArticleCategorySelect from '@/Modules/Article/Category/components/ArticleCategorySelect'
import { uid } from '@/utils/quasar'

export default {
  components: { Tinymce, MDinput, ArticleCategorySelect },
  props: {
    value: {
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
