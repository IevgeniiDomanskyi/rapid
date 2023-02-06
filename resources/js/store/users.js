import api from '../api'

const users = {
  state: {
    list: [],
    customers: [],
    customer: {},
    cards: [],
  },

  getters: {
    cardById(state) {
      return (id) => {
        for (const card of state.cards) {
          if (card.id == id) {
            return card
          }
        }

        return {}
      }
    },

    customerById(state) {
      return (id) => {
        for (const customer of state.customers) {
          if (customer.id == id) {
            return customer
          }
        }

        return {}
      }
    },
  },

  mutations: {
    usersCardSet(state, payload) {
      state.cards = payload
    },

    usersCustomersSet(state, payload) {
      state.customers = payload
    },

    usersCustomerSet(state, payload) {
      state.customer = payload
    },
  },

  actions: {
    usersCustomers({ commit }, payload) {
      return api.users.customers(payload).then(data => {
        if (data) {
          commit('usersCustomersSet', data)
        }

        return data
      })
    },

    usersCustomersSave({ commit }, payload) {
      return api.users.customersSave(payload).then(data => {
        if (data) {
          commit('usersCustomersSet', data)
        }

        return data
      })
    },

    usersCustomersGet({ commit }, payload) {
      return api.users.customersGet(payload).then(data => {
        if (data) {
          commit('usersCustomerSet', data)
        }

        return data
      })
    },

    usersAddress({ }, payload) {
      return api.users.address(payload).then(data => {
        return data
      })
    },

    usersAddressSave({ }, payload) {
      return api.users.addressSave(payload).then(data => {
        return data
      })
    },

    usersCardDetails({ }, payload) {
      return api.users.cardDetails(payload).then(data => {
        return data
      })
    },

    usersCardAdd({ }, payload) {
      return api.users.cardAdd(payload).then(data => {
        return data
      })
    },

    usersCardAll({ commit }, payload) {
      return api.users.cardAll(payload).then(data => {
        if (data) {
          commit('usersCardSet', data)
        }
        return data
      })
    },

    usersCardRemove({ commit }, payload) {
      return api.users.cardRemove(payload).then(data => {
        if (data) {
          commit('usersCardSet', data)
        }
        return data
      })
    },

    usersImport({ }) {
      return api.users.import().then(data => {
        return data
      })
    },

    usersRequestPaymentDetails({ }, payload) {
      return api.users.requestPaymentDetails(payload).then(data => {
        return data
      })
    },

    usersCardConnect({ }, payload) {
      return api.users.cardConnect(payload).then(data => {
        return data
      })
    },
  },
}

export default users