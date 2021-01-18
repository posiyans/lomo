'use strict'
import moment from 'moment'
export default function parseSber(data) {
  // let temp = ''
  // this.row = 2
  const data_ok = []
  // let error = false
  data.split(/\r?\n/).forEach(i => {
    if (i.trim().length > 0) {
      console.log('line')
      const line = i.split(';')
      if (line.length === 12 && (line[0].match(/-/g) || []).length === 2 && (line[1].match(/-/g) || []).length === 2) {
        const t1 = line[0] + ' ' + line[1]
        const t2 = moment(line[0] + ' ' + line[1], 'DD-MM-YYYY HH-mm-ss', false).format('DD-MM-YYYY HH-mm-ss')
        const t3 = moment(line[0] + ' ' + line[1], 'DD-MM-YYYY HH-mm-ss', false).format('YYYY-MM-DD HH:mm:ss')
        console.log(t2)
        if (t1 === t2) {
          data_ok.push([t3, line[8], line[5], line[6], line[7]])
        } else {
          // error = true
          // this.row++
          // temp += i + '\r\n'
        }
      }
    }
  })
  return data_ok
}
