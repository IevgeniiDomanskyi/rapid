import { request } from './index'

const tracks = {
  all() {
    return request(`track`, {}, 'GET')
  },

  save(data) {
    return request(`track`, data)
  },

  datesAll() {
    return request(`track/date`, {}, 'GET')
  },

  datesSave(data) {
    return request(`track/date`, data)
  },

  datesRemove(data) {
    return request(`track/date/${data.date}`, {}, 'DELETE')
  },

  datesByRegion(data) {
    return request(`track/date/region`, data)
  },
}

export default tracks