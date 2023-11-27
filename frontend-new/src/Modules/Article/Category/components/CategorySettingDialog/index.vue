<template>
  <div class="q-pa-md q-gutter-lg">
    <q-input
      v-model="data.name"
      outlined
      label="Название раздела"
    />
    <q-input
      v-model="data.description"
      outlined
      label="Описание раздела"
    />
    <q-checkbox v-model="data.public" label="Публичный">
      <q-tooltip>
        Доступный для размещения статей пользователем
      </q-tooltip>
    </q-checkbox>
    <div class="row justify-between">
      <div>
        <SetDefaultCategory v-if="!newCategory && !item.default" :category-id="item.id" @success="success" />
      </div>
      <div class="q-gutter-md">
        <q-btn color="negative" flat label="Отмена" v-close-popup />
        <q-btn color="primary" :label="btnLabel" @click="saveData" />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, ref } from 'vue'
import { createCategory, updateCategory } from 'src/Modules/Article/Category/api/category'
import SetDefaultCategory from 'src/Modules/Article/Category/components/SetDefaultCategory/index.vue'

export default defineComponent({
  components: {
    SetDefaultCategory
  },
  props: {
    item: {
      type: Object
    }
  },
  setup(props, { emit }) {
    const data = ref({})
    const btnLabel = computed(() => {
      return newCategory.value ? 'Добавить' : 'Сохранить'
    })
    const newCategory = computed(() => {
      return !props.item.id
    })
    const success = () => {
      emit('success')
    }
    const saveData = () => {
      if (newCategory.value) {
        createData()
      } else {
        updateData()
      }
    }
    const createData = () => {
      createCategory(data.value)
        .then(res => {
          emit('success')
        })
    }
    const updateData = () => {
      updateCategory(props.item.id, data.value)
        .then(res => {
          emit('success')
        })
    }
    onMounted(() => {
      data.value = Object.assign({}, props.item)
    })
    return {
      data,
      btnLabel,
      newCategory,
      saveData,
      success
    }
  }
})
</script>

<style scoped>

</style>
