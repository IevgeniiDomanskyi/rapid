import api from '../api'

const tracks = {
  state: {
    list: [],
    dates: [],
  },

  mutations: {
    tracksSet(state, payload) {
      state.list = payload
    },

    tracksDatesSet(state, payload) {
      state.dates = payload
    },
  },

  actions: {
    tracksAll({ commit }, payload) {
      return api.tracks.all(payload).then(data => {
        if (data) {
          commit('tracksSet', data)
        }

        return data
      })
    },

    tracksSave({ commit }, payload) {
      return api.tracks.save(payload).then(data => {
        if (data) {
          commit('tracksSet', data)
        }

        return data
      })
    },

    tracksDatesAll({ commit }, payload) {
      return api.tracks.datesAll(payload).then(data => {
        if (data) {
          commit('tracksDatesSet', data)
        }

        return data
      })
    },

    tracksDatesSave({ commit }, payload) {
      return api.tracks.datesSave(payload).then(data => {
        if (data) {
          commit('tracksDatesSet', data)
        }

        return data
      })
    },

    tracksDatesRemove({ commit }, payload) {
      return api.tracks.datesRemove(payload).then(data => {
        if (data) {
          commit('tracksDatesSet', data)
        }

        return data
      })
    },

    tracksDatesByRegion({ commit }, payload) {
      return api.tracks.datesByRegion(payload).then(data => {
        if (data) {
          commit('tracksDatesSet', data)
        }

        return data
      })
    },
  },
}

export default tracks