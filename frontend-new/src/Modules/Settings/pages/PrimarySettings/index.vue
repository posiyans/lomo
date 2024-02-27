<template>
  <div class="q-pa-sm">
    <div>
      <AllowPublicationOfArticlesForm />
    </div>
    <div>
      <ChangeFavicon v-if="false" />
    </div>
    <div>
      <SettingTelegramBotToken />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import AllowPublicationOfArticlesForm from 'src/Modules/Article/Article/components/AllowPublicationOfArticlesForm/index.vue'
import ChangeFavicon from 'src/Modules/Settings/components/ChangeFavicon/index.vue'
import SettingTelegramBotToken from 'src/Modules/Telegram/components/SettingTelegramBotToken/index.vue'

export default defineComponent({
  components: {
    SettingTelegramBotToken,
    AllowPublicationOfArticlesForm,
    ChangeFavicon
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    return {
      data
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
