'use strict'
import { date } from 'quasar'

export default function parseSber(data) {
  const dataOk = []
  data.split(/\r?\n/).forEach(i => {
    if (i.trim().length > 0) {
      const line = i.split(';')
      if (line.length === 12 && (line[0].match(/-/g) || []).length === 2 && (line[1].match(/-/g) || []).length === 2) {
        const t1 = line[0] + ' ' + line[1]
        const t2 = date.formatDate(date.extractDate(line[0] + ' ' + line[1], 'DD-MM-YYYY HH-mm-ss'), 'DD-MM-YYYY HH-mm-ss')
        const t3 = date.formatDate(date.extractDate(line[0] + ' ' + line[1], 'DD-MM-YYYY HH-mm-ss'), 'YYYY-MM-DD HH:mm:ss')
        if (t1 === t2) {
          dataOk.push([t3, line[8], line[5], line[6], line[7]])
        }
      }
    }
  })
  return dataOk
}
