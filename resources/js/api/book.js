import { request } from './index'

const book = {
  tempGet(data) {
    return request(`book/temp/${data.token}`, {}, 'GET')
  },

  temp(data) {
    return request('book/temp', data)
  },

  save(data) {
    return request(`book`, data)
  },

  enquiry(data) {
    return request(`enquiry`, data)
  },

  enquiryAll() {
    return request(`enquiry`, {}, 'GET')
  },

  all() {
    return request(`book`, {}, 'GET')
  },

  get(data) {
    return request(`book/${data.book_id}`, {}, 'GET')
  },

  forPay() {
    return request(`book/for-pay`, {}, 'GET')
  },

  assign(data) {
    return request('book/assign', data)
  },

  trackDateDefine(data) {
    return request('book/track-date-define', data)
  },

  trackPay(data) {
    return request('book/track-pay', data)
  },

  roadDateDefine(data) {
    return request('book/road-date-define', data)
  },

  agreeRoadDates(data) {
    return request('book/agree-road-dates', data)
  },

  requestPayment(data) {
    return request('book/request-payment', data)
  },

  pay(data) {
    return request('book/pay', data)
  },

  create(data) {
    return request(`book/create`, data)
  },
}

export default book