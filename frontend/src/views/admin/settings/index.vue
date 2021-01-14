<template>
  <div class="app-container">
     <el-tag>Настройки</el-tag>
    <el-tabs type="border-card" class="setting-body">
      <el-tab-pane label="Реквизиты">
        <GardeningCommponent/>
      </el-tab-pane>
      <el-tab-pane label="Меню сайта">
        <MainSiteMenu/>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
// import GardeningCommponent from './components/Gardenings'
// import MainSiteMenu from './components/MenuSite'

export default {
  // components: { GardeningCommponent, MainSiteMenu }

}
</script>

<style scoped>
  .tab-container {
    margin: 30px;
  }
  .setting-body {
    margin-top: 15px;
  }
</style>
