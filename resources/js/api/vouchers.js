import { request } from './index'

const vouchers = {
  all() {
    return request('voucher', {}, 'GET')
  },

  remove(data) {
    return request(`voucher/${data.voucherId}`, {}, 'DELETE')
  },

  upload(data) {
    return request(`voucher/upload`, data, 'POST')
  },

  save(data) {
    if (data.id) {
      return request(`voucher/${data.id}`, data, 'PUT')
    } else {
      return request(`voucher`, data, 'POST')
    }
  },

  check(data) {
    return request(`voucher/${data.code}/check`, {}, 'GET')
  },

  get(data) {
    return request(`voucher/${data.code}`, {}, 'GET')
  },

  users() {
    return request(`voucher/users`, {}, 'GET')
  },
}

export default vouchers