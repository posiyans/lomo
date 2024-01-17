import { useQuasar } from 'quasar'
import { updatePayment } from 'src/Modules/Bookkeeping/api/paymentApi'

const $q = useQuasar()

export function usePayment____(obj) {
  const payment = obj
  const deleteError = () => {
    $q.dialog({
      title: 'Подтвердите',
      message: 'Установить что что все данные в платеже проверены?',
      cancel: {
        noCaps: true,
        flat: true,
        label: 'Отмена',
        color: 'negative'
      },
      ok: {
        noCaps: true,
        outline: true,
        label: 'Сохранить',
        color: 'primary'
      },
      persistent: true
    }).onOk(() => {
      const data = {
        data_verified: true
      }
      return saveData(data)
    })
  }

  const saveData = (data) => {
    return updatePayment(payment.id, data)
  }
  return {
    deleteError
  }
}
