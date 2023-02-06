import api from '../api'

const regions = {
  state: {
    list: [],
  },

  getters: {
    regionById(state) {
      return (id) => {
        for (const region of state.list) {
          if (region.id == id) {
            return region
          }
        }

        return {}
      }
    },
  },

  mutations: {
    regionsSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    regionsAll({ commit }) {
      return api.regions.all().then(data => {
        if (data) {
          commit('regionsSet', data)
        }

        return data
      })
    },
  },
}

export default regions