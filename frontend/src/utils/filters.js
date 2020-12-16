import Vue from 'vue'

Vue.filter('declOfNum', function(number, titles) {
  const cases = [2, 0, 1, 1, 1, 2]
  return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]]
})

Vue.filter('trimText', function(text, len = 20, dot = true) {
  if (text.length > len) {
    const a = text.substring(0, len)
    let d = ''
    if (dot) {
      d = ' ...'
    }
    return a.substring(0, Math.max(a.lastIndexOf(' '), a.lastIndexOf(',') - 1)) + d
  } else {
    return text
  }
})
