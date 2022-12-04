export function getXsrfToken () {
  return getCookie('XSRF-TOKEN')
}

// export setXsrfToken() {
//   token: { 'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN') }
// }

function getCookie (name) {
  const matches = document.cookie.match(new RegExp(
    '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'
  ))
  console.log(document.cookie)
  console.log(matches)
  return matches ? decodeURIComponent(matches[1]) : undefined
}
