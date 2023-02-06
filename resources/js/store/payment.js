import api from '../api'

const payment = {
  state: {
    list: [],
    current: {},
    history: [],
  },

  mutations: {
    paymentSet(state, payload) {
      state.list = payload
    },

    currentSet(state, payload) {
      state.current = payload
    },

    paymentHistorySet(state, payload) {
      state.history = payload
    },
  },

  actions: {
    paymentAll({ commit }, payload) {
      return api.payment.all(payload).then(data => {
        if (data) {
          commit('paymentSet', data)
        }

        return data
      })
    },

    paymentGet({ commit }, payload) {
      return api.payment.get(payload).then(data => {
        if (data) {
          commit('currentSet', data)
        }

        return data
      })
    },

    paymentPay({ commit }, payload) {
      return api.payment.pay(payload).then(data => {
        if (data) {
          commit('currentSet', data)
        }

        return data
      })
    },

    paymentHistory({ commit }, payload) {
      return api.payment.history(payload).then(data => {
        if (data) {
          commit('paymentHistorySet', data)
        }

        return data
      })
    },
  },
}

export default payment