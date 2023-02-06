import { request } from './index'

const postcodes = {
  all() {
    return request('postcode', {}, 'GET')
  },

  available() {
    return request('postcode/available', {}, 'GET')
  },
}

export default postcodes