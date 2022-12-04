const votingStatus = [
  {
    value: 'new',
    label: 'Новое',
    color: 'info'
  },
  {
    value: 'execution',
    label: 'Идет',
    color: 'primary'
  },
  {
    value: 'done',
    label: 'Законченно',
    color: 'secondary'
  },
  {
    value: 'cancel',
    label: 'Отмененно',
    color: 'negative'
  }
]

const votingType = [
  {
    value: 'public',
    label: 'Публичное голосование',
    color: 'primary'
  },
  {
    value: 'owner',
    label: 'Голосование собственников',
    color: 'negative'
  }
]

export {
  votingStatus,
  votingType
}
