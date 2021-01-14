<template>
  <div id="map" v-id="show">
    <yandex-map
      :coords="center"
      :zoom="16"
      style="width: 100%; height: 600px;"
    >
<!--      <ymap-marker-->
<!--        marker-id="123"-->
<!--        :coords="coords"-->
<!--        marker-type="Polygon"-->
<!--        hint-content="участок 421"-->
<!--	      :marker-fill="{color: '#00ff00'}"-->
<!--        :marker-stroke="{color: '#ff0000', width: 1}"-->
<!--        :balloon="{header: 'участок 421', body: '612 кв.м', footer: 'footer2'}"-->
<!--      />-->
<!--      <ymap-marker-->
<!--        marker-id="124"-->
<!--        :coords="center"-->
<!--        :icon="markerIcon"-->
<!--  />-->
<!--      <ymap-marker-->
<!--        v-for="i in list"-->
<!--        :key="i.center"-->
<!--        :marker-id="i.address"-->
<!--        :coords="i.center"-->
<!--        :icon="showmarker(i)"-->
<!--      />-->
      <ymap-marker
        v-for="i in list"
        :key="i.center"
        :marker-id="i.number"
        marker-type="Polygon"
        :coords="i.krd"
        :marker-fill="i.color"
        :marker-stroke="{color: '#ff0000', width: 1}"
        :balloon="{header: 'Участок ' + i.number, body: i.size+' кв.м'}"
      />


    </yandex-map>

  </div>
</template>

<script>
import { getYandexMap } from '@/api/yandex/map.js'
export default {
  data() {
    return {
      center: [59.110174, 30.473105],
      coords: [
        [[59.11028192721815, 30.479524846676256], [59.11027189724136, 30.479465683631645], [59.11024398638894, 30.478946008239774], [59.11007487897361, 30.478984384268713 ], [59.11011838727184, 30.479560024702778], [ 59.11028092721815, 30.479524846676256 ]],
        []
      ],
      list: [],
      show: true,
      markerIcon: {
        layout: 'default#imageWithContent',
        content: 'уч. 421 <br> 612 кв.м',
        contentOffset: [-100, 50],
        contentLayout: '<div style=" width: 100px; color: #696969;">$[properties.iconContent]</div>'
      }
    }
  },
  mounted() {
    this.getPoints()
  },
  methods: {
    showmarker(i) {
      return   {
        layout: 'default#imageWithContent',
          content: 'уч. 421 <br> 612 кв.м',
          contentOffset: [0, 0],
          contentLayout: '<div style=" width: 100px; color: #696969;">' + i.address + '<br>' + i.size + ' кв.м</div>'
      }
    },
    getPoints() {
      getYandexMap().then(response => {
        console.log(response)
        this.list = response.data
      })
    }
  }
}
</script>

<style>
.ymap-class {
  width: 100%;
  height: 600px;
}
</style>
