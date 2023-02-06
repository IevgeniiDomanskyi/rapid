import { request } from './index'

const certificates = {
  all() {
    return request(`certificates`, {}, 'GET')
  },

  send(data) {
    return request(`certificates`, data, 'POST')
  },
}

export default certificates