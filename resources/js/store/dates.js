import api from '../api'

const dates = {
  state: {
    list: [],
  },

  mutations: {
    datesSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    datesAll({ commit }, payload) {
      return api.dates.all(payload).then(data => {
        if (data) {
          commit('datesSet', data)
        }

        return data
      })
    },

    datesSave({ commit }, payload) {
      return api.dates.save(payload).then(data => {
        if (data) {
          commit('datesSet', data)
        }

        return data
      })
    },
  },
}

export default dates