import { boot } from 'quasar/wrappers'
import { createYmaps } from 'vue-yandex-maps'

export default boot(({ app }) => {
  const ymaps = createYmaps({
    apikey: '514f0fcd-01fe-491b-bcae-19cfa790af54'
  })
  app.use(ymaps)
})
