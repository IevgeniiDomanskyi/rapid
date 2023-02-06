import { request } from './index'

const dates = {
  all(data) {
    return request(`user/${data.userId}/date`, {}, 'GET')
  },

  save(data) {
    return request(`user/${data.userId}/date`, data)
  },
}

export default dates