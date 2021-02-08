<template>
  <div class="app-container">
    <div class="mb2">
      <el-button type="primary" @click="$router.push('/bookkeping/billing_reestr')">Все начисления</el-button>
    </div>
    <el-tabs type="border-card">
      <el-tab-pane label="Платежи">
        <BilligReestr :is-edit="false" />
      </el-tab-pane>
      <el-tab-pane label="Разовый платеж">
        <other />
      </el-tab-pane>

    </el-tabs>
  </div>
</template>

<script>
import BilligReestr from './index'
import Other from './other'

export default {
  components: {
    BilligReestr,
    Other
  }
}
</script>

