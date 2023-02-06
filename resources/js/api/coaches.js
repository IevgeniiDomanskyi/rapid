import { request } from './index'

const coaches = {
  all() {
    return request('coach', {}, 'GET')
  },

  byPostcode(data) {
    return request('coach/postcode', data)
  },

  save(data) {
    return request('coach', data)
  },

  password(data) {
    return request(`coach/password/${data.userId}`, {}, 'GET')
  },

  customers() {
    return request('coach/customers', {}, 'GET')
  },

  books() {
    return request('coach/books', {}, 'GET')
  },

  enquiries() {
    return request('coach/enquiries', {}, 'GET')
  },
}

export default coaches