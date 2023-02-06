import { request } from './index'

const payment = {
  all(data) {
    return request(`user/${data.userId}/payment`, {}, 'GET')
  },

  get(data) {
    return request(`user/${data.userId}/payment/${data.paymentId}`, {}, 'GET')
  },

  pay(data) {
    return request(`user/${data.userId}/payment/${data.paymentId}`, data)
  },

  history(data) {
    return request(`user/${data.userId}/payment/history`, {}, 'GET')
  },
}

export default payment