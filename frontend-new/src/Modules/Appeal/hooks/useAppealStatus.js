import { ref } from 'vue'

export function useAppealStatus() {
  const status = ref([
    {
      value: 'open',
      label: 'Открытый',
      color: 'text-red'
    },
    {
      value: 'close',
      label: 'Закрытый',
      color: 'text-teal'
    }
  ])
  return {
    status
  }
}
