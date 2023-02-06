import { request } from './index'

const dialogs = {
  all(data) {
    return request(`dialogs/${data.customerId}/all`, {}, 'GET')
  },

  get(data) {
    return request(`dialogs/${data.dialogId}`, {}, 'GET')
  },

  byBook(data) {
    return request(`dialogs/${data.bookId}`, {}, 'GET')
  },

  messageRead(data) {
    return request(`message/${data.messageId}/read`, {}, 'GET')
  },

  addCall(data) {
    return request(`dialogs/${data.dialogId}/call`, data)
  },

  addNotes(data) {
    return request(`dialogs/${data.dialogId}/notes`, data)
  },

  addMessage(data) {
    return request(`dialogs/${data.dialogId}/message`, data)
  },

  close(data) {
    return request(`dialogs/${data.dialogId}/close`, {}, 'GET')
  },
}

export default dialogs