<template>
  <div class="app-container">
    <div style="padding-bottom: 60px">
      <el-button v-if="showAdd" icon="el-icon-back" type="primary" @click="showAdd=false">
        Назад
      </el-button>
      <el-button v-else icon="el-icon-plus" type="primary" @click="showAdd=true">
        Добавить категорию
      </el-button>

    </div>
    <div v-if="showAdd" style="width: 600px">
      <div> Добавить категорию</div>
      <el-form ref="form" :model="form" label-width="280px">
        <el-form-item label="Название категори">
          <el-input v-model="form.newCategory" />
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
        draggable
        :allow-drop="allowDrop"
        :allow-drag="allowDrag"
        @node-drag-start="handleDragStart"
        @node-drag-enter="handleDragEnter"
        @node-drag-leave="handleDragLeave"
        @node-drag-over="handleDragOver"
        @node-drag-end="handleDragEnd"
        @node-drop="handleDrop"
      >
        <span slot-scope="{ node, data }" class="custom-tree-node">
          <span :class="data.show_menu | colorFilter">{{ node.label }}</span>
          <span>
            <el-button
              type="text"
              size="mini"
              @click="editCategory(node, data)"
            >
              Редактировать
            </el-button>
          </span>
        </span>
      </el-tree>
      <el-button type="primary" @click="updateCategory">Сохранить</el-button>
    </div>
    <el-dialog title="Редактировать" :visible.sync="dialogVisible">
      <el-form :model="categoryEdit">
        <el-form-item label="Promotion name" label-width="150px">
          <el-input v-model="categoryEdit.label" autocomplete="off" />
        </el-form-item>
        <el-form-item v-if="!categoryEdit.children" label="Это прямая ссылка" label-width="150px">
          <el-checkbox v-model="razdel"><span v-if="razdel">Укажите ссылку ниже</span></el-checkbox>
          <el-input v-if="razdel" v-model="categoryEdit.menu_name" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Показать в меню" label-width="150px">
          <el-select v-model="categoryEdit.show_menu" placeholder="Показать в меню">
            <el-option
              v-for="item in ShowMenu"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Отмена</el-button>
        <el-button type="primary" @click="saveCategory">Сохранить</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchCategoryList, createCategory, updateCategory } from '@/api/category'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'ArticleList',
  components: { Pagination },
  filters: {
    colorFilter(val) {
      if (val === 1) {
        return 'green'
      }
      return 'red'
    },
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
      dialogVisible: false,
      form: {
        newCategory: ''
      },
      ShowMenu: [
        {
          value: 1,
          label: 'Показать'
        },
        {
          value: 0,
          label: 'Спрятать'
        }
      ],
      showAdd: false,
      categoryEdit: {},
      category: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      }
    }
  },
  computed: {
    razdel: {
      get() {
        if (this.categoryEdit.menu_name) {
          return true
        }
        return false
      },
      set(val) {
        if (val) {
          this.categoryEdit.menu_name = '/'
        } else {
          this.categoryEdit.menu_name = null
        }
      }
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    editCategory(node, data) {
      console.log('node')
      console.log(node)
      console.log('data')
      console.log(data)
      this.categoryEdit = data
      this.dialogVisible = true
    },
    saveCategory() {
      const data = {
        category: this.categoryEdit
      }
      updateCategory(this.categoryEdit.id, data)
        .then(response => {
          // console.log(response)
          this.getList()
          this.dialogVisible = false
          this.$message({
            message: 'Данные сохранены',
            type: 'success'
          })
        })
    },
    updateCategory() {
      const data = {
        allCategory: this.category
      }
      updateCategory('all', data)
        .then(response => {
          // console.log(response)
          this.getList()
          this.showAdd = false
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
      fetchCategoryList(listQuery).then(response => {
        this.category = response.data
        this.listLoading = false
        // console.log(this.category)
      })
    },
    handleDragStart(node, ev) {
      console.log('drag start', node)
    },
    handleDragEnter(draggingNode, dropNode, ev) {
      console.log('tree drag enter: ', dropNode.label)
    },
    handleDragLeave(draggingNode, dropNode, ev) {
      console.log('tree drag leave: ', dropNode.label)
    },
    handleDragOver(draggingNode, dropNode, ev) {
      console.log('tree drag over: ', dropNode.label)
    },
    handleDragEnd(draggingNode, dropNode, dropType, ev) {
      console.log('tree drag end: ', dropNode && dropNode.label, dropType)
      console.log(this.category)
    },
    handleDrop(draggingNode, dropNode, dropType, ev) {
      console.log('tree drop: ', dropNode.label, dropType)
    },
    allowDrop(draggingNode, dropNode, type) {
      if (dropNode.data.label === 'Level two 3-1') {
        return type !== 'inner'
      } else {
        return true
      }
    },
    allowDrag(draggingNode) {
      return draggingNode.data.label.indexOf('Level three 3-1-1') === -1
    }
  }
}
</script>

<style scoped>
  .red {
    color: red
  }
  .green {
    color: green;
  }
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

  .custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
  }
</style>
<style>
  .red {
    color: red
  }
  .green {
    color: green;
  }
</style>
