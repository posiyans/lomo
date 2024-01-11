'use strict'
// const path = require('path')
import parseSber from './sberParse'
import parseNotSber from './notSberParse'

const re = /(?:\.([^.]+))?$/

export default async function readFile(file) {
  const ext = re.exec(file.name)[1]
  // const ext = path.extname(file.name)
  // const reader = new FileReader()
  // reader.onload = function(loadFile) {
  //   const text = loadFile.target.result
  //   callback(text)
  // }
  console.log(ext)
  if (ext === 'cvs' || ext === 'txt') {
    // console.log('txt')
    return await new Promise((resolve, reject) => {
      const reader = new FileReader()
      reader.onload = function (loadFile) {
        const text = loadFile.target.result
        console.log('text')
        console.log(text)
        const data = parseSber(text)
        console.log('data')
        console.log(data)
        resolve(data)
      }
      reader.readAsText(file, 'cp1251')
    })
  }
  if (ext === 'xlsx' || ext === 'xls') {
    return await new Promise((resolve, reject) => {
      const reader = new FileReader()
      reader.onload = function (loadFile) {
        const text = loadFile.target.result
        const data = parseNotSber(text)
        resolve(data)
      }
      reader.readAsArrayBuffer(file)
    })
  }
}
