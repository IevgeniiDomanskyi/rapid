import api from '../api'

const coaches = {
  state: {
    list: [],
    customers: [],
    books: [],
    enquiries: [],
  },

  getters: {
    coachById(state) {
      return (id) => {
        for (const coach of state.list) {
          if (coach.id == id) {
            return coach
          }
        }

        return {}
      }
    },
  },

  mutations: {
    coachesSet(state, payload) {
      state.list = payload
    },

    coachesCustomersSet(state, payload) {
      state.customers = payload
    },

    coachesBooksSet(state, payload) {
      state.books = payload
    },

    coachesEnquiriesSet(state, payload) {
      state.enquiries = payload
    },
  },

  actions: {
    coachesAll({ commit }) {
      return api.coaches.all().then(data => {
        if (data) {
          commit('coachesSet', data)
        }

        return data
      })
    },

    coachesByPostcode({ commit }, payload) {
      return api.coaches.byPostcode(payload).then(data => {
        if (data) {
          commit('coachesSet', data)
        }

        return data
      })
    },

    coachesSave({ commit }, payload) {
      return api.coaches.save(payload).then(data => {
        if (data) {
          commit('coachesSet', data)
        }

        return data
      })
    },
    
    coachesPassword({ }, payload) {
      return api.coaches.password(payload).then(data => {
        return data
      })
    },

    coachesCustomers({ commit }) {
      return api.coaches.customers().then(data => {
        if (data) {
          commit('coachesCustomersSet', data)
        }

        return data
      })
    },

    coachesBooks({ commit }) {
      return api.coaches.books().then(data => {
        if (data) {
          commit('coachesBooksSet', data)
        }

        return data
      })
    },

    coachesEnquiries({ commit }) {
      return api.coaches.enquiries().then(data => {
        if (data) {
          commit('coachesEnquiriesSet', data)
        }

        return data
      })
    },
  },
}

export default coaches