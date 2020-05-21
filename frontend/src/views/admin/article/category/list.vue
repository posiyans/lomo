<template>
  <div class="app-container">
    <div style="padding-bottom: 60px">
      <el-button v-if="showAdd" icon="el-icon-back" type="primary" @click="showAdd=false">
        Назад
      </el-button>
      <el-button v-else  icon="el-icon-plus" type="primary" @click="showAdd=true">
        Добавить категорию
      </el-button>

    </div>
    <div v-if="showAdd"  style="width: 600px">
      <div> Добавить категорию</div>
      <el-form ref="form" :model="form" label-width="280px">
        <el-form-item label="Название категори">
          <el-input v-model="form.newCategory"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addCategory">Добавить</el-button>
          <el-button @click="showAdd=false">Отмена</el-button>
        </el-form-item>
      </el-form>

    </div>
    <div v-else style="padding-left: 40px; background-color: antiquewhite">
      <el-tree
        :data="category"
        node-key="id"
        default-expand-all
        @node-drag-start="handleDragStart"
        @node-drag-enter="handleDragEnter"
        @node-drag-leave="handleDragLeave"
        @node-drag-over="handleDragOver"
        @node-drag-end="handleDragEnd"
        @node-drop="handleDrop"
        draggable
        :allow-drop="allowDrop"
        :allow-drag="allowDrag">
      </el-tree>
      <el-button type="primary" @click="updateCategory">Сохранить</el-button>
    </div>

  </div>
</template>

<script>
import { fetchList, createCategory, updateCategory } from '@/api/category'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'ArticleList',
  components: { Pagination },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
      }
      return statusMap[status]
    }
  },
  data() {
    return {
      form: {
        newCategory: '',
      },
      showAdd: false,

      category: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      }
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    updateCategory() {
      const data = {
        allCategory: this.category
      }
      updateCategory('all', data)
        .then(response => {
          // console.log(response)
          this.getList()
          this.showAdd=false
          this.$message({
            message: 'Данные сохранены',
            type: 'success'
          })
        })
    },
    addCategory() {
      const data = {
        title: this.form.newCategory
      }
      createCategory(data)
        .then(response => {
          // console.log(response)
          this.form.newCategory = ''
          this.showAdd = false
          this.getList()
          this.$message({
            message: 'Данные сохранены',
            type: 'success'
          })
        })
    },
    getList() {
      this.listLoading = true
      const listQuery = {
        children: true
      }
      fetchList(listQuery).then(response => {
        this.category = response.data
        this.listLoading = false
        // console.log(this.category)
      })
    },
    handleDragStart(node, ev) {
      console.log('drag start', node);
    },
    handleDragEnter(draggingNode, dropNode, ev) {
      console.log('tree drag enter: ', dropNode.label);
    },
    handleDragLeave(draggingNode, dropNode, ev) {
      console.log('tree drag leave: ', dropNode.label);
    },
    handleDragOver(draggingNode, dropNode, ev) {
      console.log('tree drag over: ', dropNode.label);
    },
    handleDragEnd(draggingNode, dropNode, dropType, ev) {
      console.log('tree drag end: ', dropNode && dropNode.label, dropType);
      console.log(this.category);

    },
    handleDrop(draggingNode, dropNode, dropType, ev) {
      console.log('tree drop: ', dropNode.label, dropType);
    },
    allowDrop(draggingNode, dropNode, type) {
      if (dropNode.data.label === 'Level two 3-1') {
        return type !== 'inner';
      } else {
        return true;
      }
    },
    allowDrag(draggingNode) {
      return draggingNode.data.label.indexOf('Level three 3-1-1') === -1;
    }
  }
}
</script>

<style scoped>
  .demo-input-label {
    display: inline-block;
    width: 320px;
  }
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
</style>
