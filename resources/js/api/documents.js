import { request } from './index'

const documents = {
  all(data) {
    return request(`user/${data.userId}/document`, {}, 'GET')
  },

  sign(data) {
    return request(`user/${data.userId}/document/${data.documentId}`, {}, 'GET')
  },
}

export default documents