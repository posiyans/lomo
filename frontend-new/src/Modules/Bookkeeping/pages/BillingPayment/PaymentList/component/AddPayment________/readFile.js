'use strict'
const path = require('path')
import parseSber from './sberParse'
import parseNotSber from './notSberParse'

export default async function readFile(file) {
  const ext = path.extname(file.name)
  // const reader = new FileReader()
  // reader.onload = function(loadFile) {
  //   const text = loadFile.target.result
  //   callback(text)
  // }
  if (ext === '.cvs' || ext === '.txt') {
    console.log('txt')
    return await new Promise((resolve, reject) => {
      const reader = new FileReader()
      reader.onload = function (loadFile) {
        const text = loadFile.target.result
        const data = parseSber(text)
        resolve(data)
      }
      reader.readAsText(file, 'cp1251')
    })
  }
  if (ext === '.xlsx' || ext === '.xls') {
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
