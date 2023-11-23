'use strict'
// import moment from 'moment'
import { read, utils } from 'xlsx'

export default function parseNotSber(file) {
  const workbook = read(file, { type: 'array', cellDates: true })
  const data = []
  const worksheet = workbook.Sheets[workbook.SheetNames[0]]
  const json = utils.sheet_to_json(worksheet, { raw: false, dateNF: 'YYYY-MM-DD hh:mm:ss', blankrows: false })
  json.forEach(item => {
    if (item._13 && item._20.substr(0, 14) !== '//Приложение//' && item._20[0] !== '<') {
      let fio = item._4.split('\n')
      fio = fio.slice(fio.length - 1).join('')
      data.push([item._1, item._13.replace(',', ''), '', fio, item._20])
    }
  })
  return data.slice(1)
}
