import api from '../api'

const certificates = {
  state: {
    list: [],
  },

  mutations: {
    certificatesSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    certificatesAll({ commit }) {
      return api.certificates.all().then(data => {
        if (data) {
          commit('certificatesSet', data)
        }
        return data
      })
    },

    certificatesSend({ }, payload) {
      return api.certificates.send(payload).then(data => {
        return data
      })
    },
  },
}

export default certificates