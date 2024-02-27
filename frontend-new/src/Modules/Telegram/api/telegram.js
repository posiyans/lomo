import request from 'src/utils/request'

export function getLastUserFromTelegram() {
  return request({
    url: '/api/v2/telegram/get-last-user-from-telegram',
    method: 'get'
  })
}

export function getTelegramBotToken() {
  return request({
    url: '/api/v2/telegram/setting/get-telegram-bot-token',
    method: 'get'
  })
}

export function getTelegramBotInfo() {
  return request({
    url: '/api/v2/telegram/get-bot-info',
    method: 'get'
  })
}

export function updateTelegramBotToken(data) {
  return request({
    url: '/api/v2/telegram/setting/update-telegram-bot-token',
    method: 'post',
    data
  })
}

export function changeTwoFactorEnable(data) {
  return request({
    url: '/api/v2/telegram/setting/change-two-factor-enable',
    method: 'post',
    data
  })
}
