<template>
  <div id="map">
    <yandex-map
      :coords="center"
      :zoom="17"
      style="width: 400px; height: 400px;"
    >
      <ymap-marker
        :key="center"
        :marker-id="1"
        marker-type="Polygon"
        :coords="coords"
        :marker-fill="{color: '#ff0000', opacity:	'0.4'}"
        :marker-stroke="{color: '#ff0000', width: 1}"
      />
    </yandex-map>

  </div>
</template>

<script>

export default {
  props: {
    coords: {
      type: Array,
      default: () => ([])
    },
    center: {
      type: Array,
      default: () => ([59.110174, 30.473105])
    }
  },
}
</script>

<style>
.ymap-class {
  width: 100%;
  height: 600px;
}
</style>
