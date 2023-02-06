import { request } from './index'

const users = {
  customers() {
    return request(`user/customers`, {}, 'GET')
  },

  customersSave(data) {
    if (data.id) {
      return request(`user/customers/${data.id}`, data, 'PUT')
    } else {
      return request(`user/customers`, data)
    }
  },

  customersGet(data) {
    return request(`user/customers/${data.customerId}`, {}, 'GET')
  },

  address(data) {
    return request(`user/${data.userId}/address`, {}, 'GET')
  },

  addressSave(data) {
    return request(`user/${data.userId}/address`, data)
  },

  cardDetails(data) {
    return request(`user/${data.userId}/card/details`, {}, 'GET')
  },

  cardAdd(data) {
    return request(`user/${data.userId}/card`, data)
  },

  cardAll(data) {
    return request(`user/${data.userId}/card`, {}, 'GET')
  },

  cardRemove(data) {
    return request(`user/${data.userId}/card/${data.card}`, {}, 'DELETE')
  },

  import() {
    return request(`import`, {}, 'GET')
  },

  requestPaymentDetails(data) {
    return request(`user/${data.userId}/request-payment-details`, {}, 'GET')
  },

  cardConnect(data) {
    return request(`user/card-connect`, data)
  },
}

export default users