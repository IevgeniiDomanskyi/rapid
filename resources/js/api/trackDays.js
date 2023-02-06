import { request } from './index'

const trackDays = {
  tempGet(data) {
    return request(`track-day/temp/${data.token}`, {}, 'GET')
  },

  temp(data) {
    return request('track-day/temp', data)
  },

  all() {
    return request('track-day', {}, 'GET')
  },

  get(data) {
    return request(`track-day/${data.id}`, {}, 'GET')
  },

  save(data) {
    if (data.id) {
      return request(`track-day/${data.id}`, data, 'PUT')
    } else {
      return request(`track-day`, data)
    }
  },

  customers(data) {
    return request(`track-day/${data.id}/customers`, {}, 'GET')
  },

  book(data) {
    return request(`track-day/${data.id}/book`, data)
  },

  remove(data) {
    return request(`track-day/${data.id}`, {}, 'DELETE')
  },

  bookCreate(data) {
    return request(`track-day/book/create`, data)
  },

  bookings() {
    return request('track-day/bookings', {}, 'GET')
  },

  pay(data) {
    return request('track-day/pay', data)
  },

  enquiry(data) {
    return request(`track-day/enquiry`, data)
  },

  enquiryAll() {
    return request(`track-day/enquiry`, {}, 'GET')
  },
}

export default trackDays