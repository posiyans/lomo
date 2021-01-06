'use strict'
export default function readFile(file, callback) {
  const reader = new FileReader()
  reader.onload = function(loadFile) {
    const text = loadFile.target.result
    callback(text)
  }
  reader.readAsText(file, 'cp1251')
}
