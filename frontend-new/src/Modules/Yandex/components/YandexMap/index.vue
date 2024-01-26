<template>
  <yandex-map
    :settings="{
      location: {
        center,
        zoom,
      },
    }"
  >
    <yandex-map-default-scheme-layer :settings="{ }" />
    <yandex-map-default-features-layer />
    <yandex-map-feature v-for="item in figure" :key="item.id" :settings="item" />
    <yandex-map-hint v-if="show" hint-property="hint">
      <template #default="props">
        <div
          class="hint text-center"
          v-if="props.content"
        >
          <div class="text-weight-bold">
            {{ props.content.title }}
          </div>
          <div>
            {{ props.content.body }}
          </div>

        </div>
      </template>
    </yandex-map-hint>
  </yandex-map>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { YandexMap, YandexMapControls, YandexMapDefaultFeaturesLayer, YandexMapDefaultSchemeLayer, YandexMapFeature, YandexMapHint, YandexMapZoomControl } from 'vue-yandex-maps'
import { getYandexMap } from 'src/Modules/Yandex/api/apiYandexMap.js'


export default defineComponent({
  components: {
    YandexMap,
    YandexMapControls,
    YandexMapDefaultSchemeLayer,
    YandexMapDefaultFeaturesLayer,
    YandexMapZoomControl,
    YandexMapFeature,
    YandexMapHint
  },
  props: {
    steadId: {
      type: [String, Number],
      default: ''
    }
  },
  setup(props, { emit }) {
    const list = ref([])
    const center = ref([30.473105, 59.110174])
    const zoom = ref(16)
    const show = ref(false)
    const figure = ref([])
    const getData = () => {
      getYandexMap()
        .then(response => {
          console.log(response)
          list.value = response.data
          list.value.forEach(item => {
            if (+props.steadId === +item.id) {
              console.log(item.center)
              const centerX = item.krd.reduce((summ, item) => {
                return summ + item[0]
              }, 0)
              const centerY = item.krd.reduce((summ, item) => {
                return summ + item[1]
              }, 0)
              center.value = [centerX / item.krd.length, centerY / item.krd.length]
              zoom.value = 17
            }
            const color = +props.steadId === +item.id ? 'rgba(255,0,0, 1)' : 'rgba(255,0,0,0.1)'
            figure.value.push({
              id: item.number,
              draggable: false,
              geometry: {
                type: 'Polygon',
                coordinates: [item.krd]
              },
              style: {
                fillRule: 'nonzero',
                fill: color,
                fillOpacity: item.color.opacity,
                stroke: [
                  {
                    color: item.color.color,
                    width: 1
                  },
                ],
              },
              properties: {
                hint: {
                  title: 'Участок ' + item.number,
                  body: item.size + ' кв.м'
                },
              },

              onDragEnd: (val) => {
                console.log(val)
              }
            })
          })
          show.value = true
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      show,
      center,
      zoom,
      list,
      figure
    }
  }
})
</script>

<style scoped lang='scss'>
.hint {
  position: absolute;
  padding: 4px;
  background: white;
  border: 1px solid black;
  white-space: nowrap;
  opacity: 0.7;
  transform: translate(8px, -50%)
}
</style>
