<template>
  <div style="margin-top: 20px">
    <el-table
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column label="Номер участка" align="center" width="160">
        <template slot-scope="{row}">
          <span>{{ row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column v-for="(i, j) in list[0].data" :key="i" :label="j" align="center">
        <template slot-scope="{row}">
          <span>{{ row.data[j] }} руб.</span>
        </template>
      </el-table-column>

      <!--      <el-table-column label="ФИО" min-width="150px" class-name="small-padding fixed-width">-->
      <!--        <template slot-scope="{row}">-->
      <!--          <span-->
      <!--            class="link-type"-->
      <!--            :class="{-->
      <!--              'grey' : !row.consent_personal-->
      <!--            }"-->
      <!--            :title="row.consent_personal ? '' : 'Нет согласия на обработку персональных данных'"-->
      <!--            @click="handleShow(row)"-->
      <!--          >-->
      <!--            {{ row | fullNameFilter }}-->
      <!--          </span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <!--      <el-table-column label="Участок(и)" align="center"  width="150px">-->
      <!--        <template slot-scope="{row}">-->
      <!--          <span v-for="(stead, i) of row.steads" class="link-type" >-->
      <!--             <span v-if=" i > 0" >, </span><span>{{ stead.number }}</span>-->
      <!--          </span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <!--      <el-table-column label="Телефон" align="center"  width="150px">-->
      <!--        <template slot-scope="{row}">-->
      <!--        <span lass="link-type" >-->
      <!--           {{ row.phone }}</span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <!--      <el-table-column label="Email" align="center"  width="250px">-->
      <!--        <template slot-scope="{row}">-->
      <!--        <span-->
      <!--          class="link-type"-->
      <!--          :class="{-->
      <!--          'gren' : row.email_verified_at,-->
      <!--          'grey' : !row.email_verified_at-->
      <!--          }"-->
      <!--          :title="row.email_verified_at ? '' : 'Не подтвержден'"-->
      <!--        >-->
      <!--           {{ row.email }}</span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <!--      <el-table-column label="Статус" class-name="status-col" width="100">-->
      <!--        <template slot-scope="{row}">-->
      <!--          <span-->
      <!--            :class="{'gren' : row.steads.length > 0}"-->
      <!--            :title="row.steads | ownerFilter"-->
      <!--          >-->
      <!--            <i class="el-icon-s-home" />-->
      <!--          </span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <!--      <el-table-column label="Actions" align="center" width="230" class-name="small-padding fixed-width">-->
      <!--        <template slot-scope="{ row }">-->
      <!--          <el-button type="primary" size="small" @click="handleUpdate(row)" circle>-->
      <!--            <svg-icon icon-class="edit" />-->
      <!--          </el-button>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
    </el-table>
  </div>
</template>

<script>
export default {
  components: {
  },
  props: {
    list: {
      type: Array,
      default: () => { [] }
    }
  },
  data() {
    return {
      total: 0
    }
  },
  methods: {
    findStead() {

    }
  }
}
</script>

<style scoped>

</style>
