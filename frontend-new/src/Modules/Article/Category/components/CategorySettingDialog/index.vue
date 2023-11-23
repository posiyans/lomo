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
        <SetDefaultCategory v-if="!item.default" :category-id="item.id" @success="success" />
      </div>
      <div class="q-gutter-md">
        <q-btn color="negative" flat label="Отмена" v-close-popup />
        <q-btn color="primary" label="Сохранить" @click="updateData" />
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import { updateCategory } from 'src/Modules/Article/Category/api/category'
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
    const success = () => {
      emit('success')
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
      updateData,
      success
    }
  }
})
</script>

<style scoped>

</style>
