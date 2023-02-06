import Vue from 'vue'
import VueCookie from 'vue-cookie'
import api from '../api'

const events = {
  state: {
    list: [],
    enquiries: [],
    bookings: [],
    customers: [],
    current: {},

    step: 1,
    max: 1,
    options: {
      terms: false,
      card: 0,
      plan: 0,
      instalment: 2,
      event: 0,
      message: '',
      eForm: {},
    },

    token: VueCookie.get('event-token') || null,
  },

  getters: {
    eventById(state) {
      return (id) => {
        for (const event of state.list) {
          if (event.id == id) {
            return event
          }
        }

        return {}
      }
    },

    eventsStepTitle() {
      return (step) => {
        switch (step) {
          case 1: return 'Enter your details'
          case 2: return 'Terms and conditions and Rider declaration'
          case 3: return 'Payment method'
          case 4: return 'Your payment plan'
          case 5: return 'Book now'
        }
      }
    },
  },

  mutations: {
    eventsTokenSet(state) {
      const pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      let token = ''
      for (let i = 0; i < 16; i++) {
        token += pool.charAt(Math.floor(Math.random() * pool.length));
      }

      state.token = token

      let options = { expires: 1 }

      Vue.cookie.set('event-token', token, options)
    },

    eventsTokenClear(state) {
      state.token = null

      Vue.cookie.delete('event-token')
    },

    eventsSet(state, payload) {
      state.list = payload
    },

    eventsEnquirySet(state, payload) {
      state.enquiries = payload
    },

    eventsCustomersSet(state, payload) {
      state.customers = payload
    },

    eventsCurrentSet(state, payload) {
      state.current = payload
    },

    eventsMaxSet(state, payload) {
      state.max = Math.max(payload, state.max)
    },

    eventsStepSet(state, payload) {
      if (payload.step) {
        state.step = payload.step
      }

      if (payload.options) {
        state.options = { ...state.options, ...payload.options }
      }
    },

    eventsStoreClear(state) {
      state.step = 1
      state.max = 1
      state.options.terms = false
      state.options.card = 0
      state.options.plan = 0
      state.options.instalment = 2
      state.options.event = 0
      state.options.message = ''
      state.options.eForm = {}
    },

    eventsBookingsSet(state, payload) {
      state.bookings = payload
    },
  },

  actions: {
    eventsAll({ commit }) {
      return api.events.all().then(data => {
        if (data) {
          commit('eventsSet', data)
        }

        return data
      })
    },

    eventsGet({ commit }, payload) {
      return api.events.get(payload).then(data => {
        if (data) {
          commit('eventsCurrentSet', data)
        }

        return data
      })
    },

    eventsSave({ commit }, payload) {
      return api.events.save(payload).then(data => {
        if (data) {
          commit('eventsSet', data)
        }

        return data
      })
    },

    eventsCustomers({ commit }, payload) {
      return api.events.customers(payload).then(data => {
        if (data) {
          commit('eventsCustomersSet', data)
        }

        return data
      })
    },

    eventsStep({ state, commit }, payload) {
      if (!state.token) {
        commit('eventsTokenSet')
      }

      commit('eventsStepSet', payload)
      commit('eventsMaxSet', payload.step)

      return api.events.temp(state).then(data => {
        return data
      })
    },

    eventsTempGet({ state, commit, rootState }) {
      if (state.token) {
        return api.events.tempGet({ token: state.token }).then(data => {
          if (data) {
            if (!rootState.auth.me.id || (rootState.auth.me.id && rootState.auth.me.role > 0)) {
              if (data.step > 1) {
                data.step = 1
              }

              if (data.max > 1) {
                data.max = 1
              }
            }

            commit('eventsStepSet', data)
            commit('eventsMaxSet', data.max)
          }
          return data
        })
      }
    },

    eventsBook({ state, commit }) {
      return api.events.book({ id: state.current.id, options: state.options, token: state.token }).then(data => {
        if (data) {
          commit('eventsTokenClear')
          commit('eventsStoreClear')
        }
        return data
      })
    },

    eventsRemove({ commit }, payload) {
      return api.events.remove(payload).then(data => {
        if (data) {
          commit('eventsSet', data)
        }

        return data
      })
    },

    eventsBookCreate({ commit }, payload) {
      return api.events.bookCreate(payload).then(data => {
        if (data) {
          commit('eventsSet', data)
        }

        return data
      })
    },

    eventsBookings({ commit }) {
      return api.events.bookings().then(data => {
        if (data) {
          commit('eventsBookingsSet', data)
        }

        return data
      })
    },

    eventsPay({ commit }, payload) {
      return api.events.pay(payload).then(data => {
        if (data) {
          commit('eventsBookingsSet', data)
        }

        return data
      })
    },

    eventsEnquiry({ state, commit }) {
      return api.events.enquiry({ token: state.token }).then(data => {
        if (data) {
          commit('eventsTokenClear')
          commit('eventsStoreClear')
        }
        return data
      })
    },

    eventsEnquiryAll({ state, commit }) {
      return api.events.enquiryAll().then(data => {
        if (data) {
          commit('eventsEnquirySet', data)
        }
        return data
      })
    },
  },
}

export default events