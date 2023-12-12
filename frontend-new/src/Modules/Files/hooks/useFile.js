import { reactive } from 'vue'
import { deleteFileApi, userUploadFile } from 'src/Modules/Files/api/file'
import { uid as newUid } from 'quasar'

const maxChunkSize = 2 * 1024 * 1024 // max 2Mb

export function useFile() {
  const parent = {
    type: null,
    uid: null
  }
  const model = reactive(
    {
      uid: null,
      name: null,
      size: null,
      type: null,
      url: null,
      blob: null
    }
  )
  const init = (val) => {
    model.uid = val.uid
    model.name = val.name
    model.size = val.size
    model.url = val.url
    model.type = val.type
    upload.success = true
  }
  const addFile = (oldFile, parentType, parentUid) => {
    model.uid = newUid()
    model.blob = oldFile
    model.name = oldFile.name
    model.size = oldFile.size
    model.type = oldFile.type
    parent.type = parentType
    parent.uid = parentUid
  }
  const upload = reactive(
    {
      success: false,
      error: false,
      errorsMessage: null,
      process: 0
    }
  )

  const sendFileToServer = () => {
    return new Promise((resolve, reject) => {
      upload.success = false
      upload.error = false
      const data = {
        chunk: 0,
        maxChunk: Math.round(model.blob.size / maxChunkSize)
      }
      sendData(data)
      resolve()
    })
  }
  const deleteFile = () => {
    return new Promise((resolve, reject) => {
      const data = {
        uid: model.uid
      }
      deleteFileApi(data)
        .then(res => {
          resolve()
        })
        .catch(() => {
          reject()
        })
    })
  }
  const sendData = (data) => {
    if (data.chunk <= data.maxChunk) {
      const start = data.chunk * maxChunkSize
      const end = start + maxChunkSize
      const chunk = model.blob.slice(start, end)
      const formData = new FormData()
      let done = false
      if (data.chunk === data.maxChunk) {
        done = true
        formData.append('action', 'done')
        formData.append('name', model.name)
        formData.append('size', model.size)
        formData.append('type', model.type)
        formData.append('model', parent.type)
        formData.append('model_uid', parent.uid)
      } else {
        formData.append('action', 'chunk')
      }
      formData.append('chunk', data.chunk)
      formData.append('uid', model.uid)
      formData.append('file', chunk)
      data.chunk++
      userUploadFile(formData)
        .then(response => {
          upload.process = (data.chunk - 1) / data.maxChunk
          if (done) {
            upload.success = true
            model.url = response.data.data.url
            upload.error = false
          }
          sendData(data)
        })
        .catch(er => {
          upload.error = true
          upload.success = false
          upload.errorsMessage = er.response.data.errors
        })
    }
  }

  return {
    init,
    addFile,
    model,
    upload,
    deleteFile,
    sendFileToServer
  }
}
