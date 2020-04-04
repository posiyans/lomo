<template>
  <el-upload
    class="upload-demo"
    :action="url"
    :on-preview="handlePreview"
    :on-remove="handleRemove"
    :before-remove="beforeRemove"
    :on-success="handleSuccess"
    multiple
    :limit="10"
    with-credentials
    :headers="token"
    :data="{uid: id, model:'voting'}"
    :on-exceed="handleExceed"
    :file-list="fileList">
    <el-button size="small" type="primary">Загрузить</el-button>
    <div slot="tip" class="el-upload__tip">Прикрепить файлы к голосованию</div>
  </el-upload>
</template>

<script>
  export default {
    props: {
      value: {
        type: Array,
        default: []
      },
      id: {
        type: String,
        default: 'no_uid'
      },
    },
    data() {
      return {
        // fileList: [{name: 'food.jpeg', url: 'https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100'}, {name: 'food2.jpeg', url: 'https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100'}]
        // fileList: [],
        token: {'X-XSRF-TOKEN':this.getCookie('XSRF-TOKEN')}
      };
    },
    computed: {
      url() {
        return process.env.VUE_APP_BASE_API + '/user/storage/file'
      },
      fileList: {
        get() {
          return this.value
        },
        set(val) {
          this.$emit('input', val)
        }
      }
    },
    methods: {
      getCookie(name) {
        let matches = document.cookie.match(new RegExp(
          "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
      },
      handleSuccess(response, file,  fileList) {
        console.log('handleSuccess')
        console.log(response)
        file.id = file.response.files.id,
        console.log(file)
        console.log(fileList)
        this.fileList = fileList
        // this.fileList = fileList.map( item =>{
        //   return {
        //     id: item.reponse.files.file.id,
        //     name: item.reponse.files.file.name
        //   }
        // })
        // const uid = file.uid
        // const objKeyArr = Object.keys(this.listObj)
        // for (let i = 0, len = objKeyArr.length; i < len; i++) {
        //   if (this.listObj[objKeyArr[i]].uid === uid) {
        //     this.listObj[objKeyArr[i]].url = response.files.file
        //     // this.listObj[objKeyArr[i]].url = 'https://sun9-23.userapi.com/c638430/v638430487/359b2/eoc8yTAibjo.jpg?ava=1'
        //     this.listObj[objKeyArr[i]].hasSuccess = true
        //     return
        //   }
        // }
      },
      handleRemove(file, fileList) {
        console.log(file);
        console.log(fileList);
        this.fileList = fileList
      },
      handlePreview(file) {
        console.log(file);
      },
      handleExceed(files, fileList) {
        this.$message.warning(`The limit is 3, you selected ${files.length} files this time, add up to ${files.length + fileList.length} totally`);
      },
      beforeRemove(file, fileList) {
        return this.$confirm(`Удалить файл ${ file.name } ?`);
      }
    }
  }
</script>
