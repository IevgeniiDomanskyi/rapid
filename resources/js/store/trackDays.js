import Vue from 'vue'
import VueCookie from 'vue-cookie'
import api from '../api'

const trackDays = {
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
      trackDay: 0,
      message: '',
      eForm: {},
    },

    token: VueCookie.get('track-day-token') || null,
  },

  getters: {
    trackDayById(state) {
      return (id) => {
        for (const trackDay of state.list) {
          if (trackDay.id == id) {
            return trackDay
          }
        }

        return {}
      }
    },

    trackDaysStepTitle() {
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
    trackDaysTokenSet(state) {
      const pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      let token = ''
      for (let i = 0; i < 16; i++) {
        token += pool.charAt(Math.floor(Math.random() * pool.length));
      }

      state.token = token

      let options = { expires: 1 }

      Vue.cookie.set('track-day-token', token, options)
    },

    trackDaysTokenClear(state) {
      state.token = null

      Vue.cookie.delete('track-day-token')
    },

    trackDaysSet(state, payload) {
      state.list = payload
    },

    trackDaysEnquirySet(state, payload) {
      state.enquiries = payload
    },

    trackDaysCustomersSet(state, payload) {
      state.customers = payload
    },

    trackDaysCurrentSet(state, payload) {
      state.current = payload
    },

    trackDaysMaxSet(state, payload) {
      state.max = Math.max(payload, state.max)
    },

    trackDaysStepSet(state, payload) {
      if (payload.step) {
        state.step = payload.step
      }

      if (payload.options) {
        state.options = { ...state.options, ...payload.options }
      }
    },

    trackDaysStoreClear(state) {
      state.step = 1
      state.max = 1
      state.options.terms = false
      state.options.card = 0
      state.options.plan = 0
      state.options.instalment = 2
      state.options.trackDay = 0
      state.options.message = ''
      state.options.eForm = {}
    },

    trackDaysBookingsSet(state, payload) {
      state.bookings = payload
    },
  },

  actions: {
    trackDaysAll({ commit }) {
      return api.trackDays.all().then(data => {
        if (data) {
          commit('trackDaysSet', data)
        }

        return data
      })
    },

    trackDaysGet({ commit }, payload) {
      return api.trackDays.get(payload).then(data => {
        if (data) {
          commit('trackDaysCurrentSet', data)
        }

        return data
      })
    },

    trackDaysSave({ commit }, payload) {
      return api.trackDays.save(payload).then(data => {
        if (data) {
          commit('trackDaysSet', data)
        }

        return data
      })
    },

    trackDaysCustomers({ commit }, payload) {
      return api.trackDays.customers(payload).then(data => {
        if (data) {
          commit('trackDaysCustomersSet', data)
        }

        return data
      })
    },

    trackDaysStep({ state, commit }, payload) {
      if (!state.token) {
        commit('trackDaysTokenSet')
      }

      commit('trackDaysStepSet', payload)
      commit('trackDaysMaxSet', payload.step)

      return api.trackDays.temp(state).then(data => {
        return data
      })
    },

    trackDaysTempGet({ state, commit, rootState }) {
      if (state.token) {
        return api.trackDays.tempGet({ token: state.token }).then(data => {
          if (data) {
            if (!rootState.auth.me.id || (rootState.auth.me.id && rootState.auth.me.role > 0)) {
              if (data.step > 2) {
                data.step = 2
              }

              if (data.max > 2) {
                data.max = 2
              }
            }

            commit('trackDaysStepSet', data)
            commit('trackDaysMaxSet', data.max)
          }
          return data
        })
      }
    },

    trackDaysBook({ state, commit }) {
      return api.trackDays.book({ id: state.current.id, options: state.options, token: state.token }).then(data => {
        if (data) {
          commit('trackDaysTokenClear')
          commit('trackDaysStoreClear')
        }
        return data
      })
    },

    trackDaysRemove({ commit }, payload) {
      return api.trackDays.remove(payload).then(data => {
        if (data) {
          commit('trackDaysSet', data)
        }

        return data
      })
    },

    trackDaysBookCreate({ commit }, payload) {
      return api.trackDays.bookCreate(payload).then(data => {
        if (data) {
          commit('trackDaysSet', data)
        }

        return data
      })
    },

    trackDaysBookings({ commit }) {
      return api.trackDays.bookings().then(data => {
        if (data) {
          commit('trackDaysBookingsSet', data)
        }

        return data
      })
    },

    trackDaysPay({ commit }, payload) {
      return api.trackDays.pay(payload).then(data => {
        if (data) {
          commit('trackDaysBookingsSet', data)
        }

        return data
      })
    },

    trackDaysEnquiry({ state, commit }) {
      return api.trackDays.enquiry({ token: state.token }).then(data => {
        if (data) {
          commit('trackDaysTokenClear')
          commit('trackDaysStoreClear')
        }
        return data
      })
    },

    trackDaysEnquiryAll({ state, commit }) {
      return api.trackDays.enquiryAll().then(data => {
        if (data) {
          commit('trackDaysEnquirySet', data)
        }
        return data
      })
    },
  },
}

export default trackDays