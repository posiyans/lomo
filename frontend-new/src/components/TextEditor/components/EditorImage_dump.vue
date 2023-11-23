<template>
  <div class="upload-container">
    <q-dialog v-model="dialogVisible">
      <q-card style="min-width: 520px;">
        <q-card-section>
          <div class="upload-list upload-list--picture-card">
            <div v-for="item in files" :key="item.name" class="upload-list__item">
              <transition
                appear
                enter-active-class="animated fadeIn"
                leave-active-class="animated fadeOut"
              >
                <div v-if="item.upload.success">
                  <img v-if="item.model.url" class="upload-list__item-thumbnail" :src="url(item.model.url)" alt="">
                  <label class="upload-list__item-status-label">
                    <q-icon name="done" class="upload-list__item-status-label-icon" />
                  </label>
                  <span class="upload-list__item-actions">
                    <span class="cursor-pointer" @click="showPreview(item.model.url)">
                      <q-icon name="search" />
                    </span>
                    <span class="cursor-pointer q-ml-xs" @click="deleteFile(item)">
                      <q-icon name="delete" />
                    </span>
                    {{ item.uid }}
                  </span>
                </div>
                <div v-else class="q-pa-sm">
                  <q-spinner
                    color="primary"
                    size="130px"
                    :thickness="2"
                  />
                </div>
              </transition>
            </div>
          </div>
          <input
            ref="btnRef"
            type="file"
            class="hidden"
            multiple
            @change="change"
          />
          <div class="editor-slide-upload">
            <div class="upload upload--picture-card">
              <q-btn color="primary" dense label="Загрузить" class="q-px-sm" @click="selectFileAction" />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div class="q-gutter-md">
            <q-btn label="Отмена" flat color="negative" @click="dialogVisible = false" />
            <q-btn color="primary" label="Подтвердить" @click="handleSuccess" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog
      v-model="dialogPreviewVisible"
      full-width
      full-height
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <div class="text-center">
            <q-img
              :src="previewUrl"
              width="600px"
              fit="fill"
            />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useFile } from 'src/Modules/Files/hooks/useFile'
import { uid, useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    parentType: {
      type: String,
      default: 'article'
    },
    maxSize: {
      type: Number,
      default: 5 * 1024 * 1024
    },
    parentUid: {
      type: String,
      default: uid()
    },
  },
  setup(props, { emit }) {
    const dialogVisible = ref(true)
    const dialogVisibleAction = () => {
      files.value = []
      dialogVisible.value = true
    }
    const dialogPreviewVisible = ref(false)
    const previewUrl = ref(false)
    const btnRef = ref(null)
    const router = useRouter()
    const route = useRoute()
    const $q = useQuasar()
    const url = computed(() => {
      // return '/api/user/storage/file'
      return (val) => process.env.BASE_API + val
    })
    const files = ref([])
    const selectFileAction = () => {
      btnRef.value.click()
    }
    const showPreview = (val) => {
      previewUrl.value = process.env.BASE_API + val
      dialogPreviewVisible.value = true
    }
    const deleteFile = (val) => {
      val.delete = true
      val.deleteFile()
        .then(() => {
          files.value.splice(files.value.findIndex(item => item.model.uid === val.model.uid), 1)
        })
        .catch(() => {
          $q.notify(
            {
              message: 'Ошибка удаления файла',
              type: 'negative'
            }
          )
        })
    }
    const change = () => {
      let i = 100
      const tmp = [...btnRef.value.files]
      tmp.forEach(item => {
        setTimeout(() => {
          const data = useFile()
          data.addFile(item, 'article', uid())
          data.sendFileToServer()
          files.value.push(data)
        }, i)
        i = i + 750
      })
    }
    onMounted(() => {

    })
    const handleSuccess = () => {
      console.log(files.value)
      emit('success', files.value)
      dialogVisible.value = false
    }
    return {
      dialogVisible,
      dialogVisibleAction,
      deleteFile,
      previewUrl,
      dialogPreviewVisible,
      showPreview,
      selectFileAction,
      change,
      handleSuccess,
      files,
      url,
      btnRef
    }
  }
})
</script>


<style lang="scss" scoped>
.editor-slide-upload {
  margin-bottom: 20px;

  :deep(.el-upload--picture-card) {
    width: 100%;
  }

  :deep(.el-upload-list--picture-card) {
    width: 100%;
  }
}

.upload-list--picture-card {
  display: inline-flex;
  flex-wrap: wrap;
  margin: 0;
  width: 100%;

}

.upload-list {
  margin: 10px 0 0;
  padding: 0;
  list-style: none;
  position: relative;
}

.upload--picture-card {
  //--el-upload-picture-card-size: 148px;
  background-color: #FAFAFAFF;
  border: 1px dashed #cdd0d6;
  border-radius: 6px;
  box-sizing: border-box;
  height: 148px;
  cursor: pointer;
  vertical-align: top;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 100%;

  &:hover {
    border-color: #409EFFFF;
    color: #409EFFFF;
  }
}

.upload {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  outline: 0;
}

.upload-list {
  list-style: none;
}

.upload-list--picture-card .upload-list__item {
  overflow: hidden;
  background-color: #ffffff;
  border: 1px solid #dcdfe6;
  border-radius: 6px;
  box-sizing: border-box;
  width: 148px;
  height: 148px;
  margin: 0 8px 8px 0;
  padding: 0;
  display: inline-flex;
}

.upload-list__item {
  transition: all .5s cubic-bezier(.55, 0, .1, 1);
  font-size: 14px;
  color: #606266;
  margin-bottom: 5px;
  position: relative;
  box-sizing: border-box;
  border-radius: 4px;
  width: 100%;
}

.upload-list--picture-card .upload-list__item-thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

img {
  max-width: 100%;
  border-style: none;
}

.upload-list--picture-card .upload-list__item-status-label {
  right: -15px;
  top: -6px;
  width: 40px;
  height: 24px;
  background: #67C23AFF;
  text-align: center;
  transform: rotate(45deg);
}

.upload-list__item-status-label {
  position: absolute;
  right: 5px;
  top: 0;
  line-height: inherit;
  height: 100%;
  justify-content: center;
  align-items: center;
  transition: opacity .3s;
}

.upload-list__item-status-label-icon {
  color: white;
  font-size: 12px;
  margin-top: 11px;
  transform: rotate(-45deg);
}

.upload-list--picture-card .upload-list__item-actions {
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  cursor: default;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  color: #fff;
  opacity: 0;
  font-size: 20px;
  background-color: rgba(0, 0, 0, .5);
  transition: opacity .3s;

  &:hover {
    opacity: 1;
  }
}
</style>
