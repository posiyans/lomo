<template>
  <div>
    <div>Укажите участок</div>
    <UserSteadFind @selectStead="setStead" />
    <div v-if="stead">
      Добавить собственнику участок № {{ stead.number }} площадью {{ stead.size }} кв.м?
    </div>
    <div>
      <div @click="showAddons = !showAddons">
        Дополнительно
        <i class="el-icon-arrow-down" :class="{'rotate-180': showAddons}" />
      </div>
      <transition name="fade">
        <div v-show="showAddons">
          <table :key="key" :class="colorTable">
            <tr>
              <td>{{ name }}</td>
              <td>
                <el-input v-model="proportion" type="number" max="100" min="1" placeholder="Доля в процетах" style="width: 80px;" @change="key++" />
                %
              </td>
            </tr>
            <tr v-for="item in allOwner" :key="item.id">
              <td>{{ item.owner_fullName }}</td>
              <td>
                <el-input v-model="item.proportion" type="number" max="100" min="1" placeholder="Доля в процетах" style="width: 80px;" @change="key++" />
                %
              </td>
            </tr>
          </table>
        </div>
      </transition>
    </div>
    <div class="tr">
      <el-button type="success" :disabled="!stead" @click="AddStead">Добавить</el-button>
    </div>
  </div>
</template>

<script>
import UserSteadFind from '@/components/UserSteadFind'
import { OwnerAddUser } from '@/api/admin/owner/owner-api'
import { getOwnerForStead } from '@/api/admin/stead'

export default {
  components: {
    UserSteadFind
  },
  props: {
    ownerId: {
      type: [Number, String],
      default: ''
    },
    name: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      key: 0,
      stead: null,
      proportion: 100,
      showAddons: false,
      allOwner: []
    }
  },
  computed: {
    colorTable() {
      let pr = +this.proportion
      this.allOwner.forEach(item => {
        pr += +item.proportion
      })
      if (pr > 100) {
        return 'dark-red'
      }
      return ''
    }
  },
  methods: {
    getSteadOwner() {
      getOwnerForStead(this.stead.id)
        .then(response => {
          if (response.data.status) {
            this.allOwner = response.data.data
            this.showAddons = true
          } else if (response.data.error) {
            this.$message.error(response.data.error)
          }
        })
    },
    AddStead() {
      this.$confirm('Добавить участок собственнику?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        OwnerAddUser({ owner: this.ownerId, stead: this.stead.id, proportion: this.proportion, allOwner: this.allOwner })
          .then(response => {
            if (response.data.status) {
              this.$emit('close')
              this.$message('Данные успешно сохранены')
              // this. = response.data.data
            } else if (response.data.error) {
              this.$message.error(response.data.error)
            }
            if (response.data.code === 100) {
              this.getSteadOwner()
            }
          })
      }).catch(() => {

      })
    },
    setStead(val) {
      this.stead = val
    }
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: all 0.75s ease-out;
}
</style>
