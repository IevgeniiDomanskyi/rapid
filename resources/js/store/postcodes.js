import api from '../api'

const postcodes = {
  state: {
    list: [],
    available: [],
  },

  getters: {
    postcodeById(state) {
      return (id, available) => {
        const list = available ? state.available : state.list
        for (const postcode of list) {
          if (postcode.id == id) {
            return postcode
          }
        }

        return {}
      }
    },
  },

  mutations: {
    postcodesSet(state, payload) {
      state.list = payload
    },

    postcodesAvailableSet(state, payload) {
      state.available = payload
    },
  },

  actions: {
    postcodesAll({ commit }) {
      return api.postcodes.all().then(data => {
        if (data) {
          commit('postcodesSet', data)
        }

        return data
      })
    },

    postcodesAvailable({ commit }) {
      return api.postcodes.available().then(data => {
        if (data) {
          commit('postcodesAvailableSet', data)
        }

        return data
      })
    },
  },
}

export default postcodes