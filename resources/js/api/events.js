import { request } from './index'

const events = {
  tempGet(data) {
    return request(`event/temp/${data.token}`, {}, 'GET')
  },

  temp(data) {
    return request('event/temp', data)
  },

  all() {
    return request('event', {}, 'GET')
  },

  get(data) {
    return request(`event/${data.id}`, {}, 'GET')
  },

  save(data) {
    if (data.id) {
      return request(`event/${data.id}`, data, 'PUT')
    } else {
      return request(`event`, data)
    }
  },

  customers(data) {
    return request(`event/${data.id}/customers`, {}, 'GET')
  },

  book(data) {
    return request(`event/${data.id}/book`, data)
  },

  remove(data) {
    return request(`event/${data.id}`, {}, 'DELETE')
  },

  bookCreate(data) {
    return request(`event/book/create`, data)
  },

  bookings() {
    return request('event/bookings', {}, 'GET')
  },

  pay(data) {
    return request('event/pay', data)
  },

  enquiry(data) {
    return request(`event/enquiry`, data)
  },

  enquiryAll() {
    return request(`event/enquiry`, {}, 'GET')
  },
}

export default events