/** When your routing table is too long, you can split it into small modules**/

const allInstrumentReading = {
  path: '/indication',
  component: () => import('@/views/all/InstrumentReading/index'),
  hidden: true
  // meta: { title: 'Показания', icon: 'link', affix: true }
}

export default allInstrumentReading
