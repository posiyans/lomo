<template>
  <div>
    <div class="q-pa-xs">Добавить выписку из банка</div>
    <div>
      <div>
        <input
          ref="btnRefd"
          type="file"
          class="hidden"
          multiple
          :accept="acceptFileType"
          @change="change"
        />
        <div @click.stop="showDialog">
          <q-btn color="primary" no-caps label="Выбрать файлы" />
          <div class="text-small-85 text-grey">
            txt,cvs,xlsx выписки из банка, до 100 файлов
          </div>
        </div>
      </div>
    </div>
    <div>
      <ShowTable :list="paymentData" />
    </div>
    <div>
      <q-btn color="negative" flat label="Отмена" @click="cancel" />
      <q-btn color="primary" :loading="loading" label="Загрузить" @click="uploadData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import readFile from './js/readFile'
import ShowTable from './components/ShowTable/index.vue'

export default defineComponent({
  components: {
    ShowTable
  },
  props: {},
  setup(props, { emit }) {
    const loading = ref(false)
    const btnRefd = ref(null)
    const paymentData = ref([])
    const $q = useQuasar()
    const showDialog = () => {
      console.log('klick')
      console.log(btnRefd.value)
      btnRefd.value.click()
    }
    const acceptFileType = '.txt,.csv,.xls,.xlsx'
    const cancel = () => {
      paymentData.value = []
      loading.value = false
    }
    const uploadData = () => {
      loading.value = true
    }
    const change = () => {
      console.log(btnRefd.value.files)
      const tmp = []
      if (btnRefd.value.files) {
        [...btnRefd.value.files].forEach(item => {
          console.log(item)
          readFile(item).then(text => {
            console.log(text)
            text.forEach(i => {
              paymentData.value.push({
                raw: i,
                error: false,
                upload: false,
              })
            })
          })
          // if (item.size > props.maxSize) {
          //   $q.dialog({
          //     title: 'Ошибка',
          //     message: 'Файл ' + item.name + ' превышает максимальный размер в ' + fileSize(props.maxSize)
          //   })
          // } else {
          //   const data = useFile()
          //   data.addFile(item, props.parentType, props.parentUid)
          //   // const data = useFile(item, props.parentType, props.parentUid)
          //   tmp.push(data)
          // }
        })
      }
      emit('add-files', tmp)
    }
    return {
      showDialog,
      cancel,
      loading,
      acceptFileType,
      paymentData,
      change,
      uploadData,
      btnRefd
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
