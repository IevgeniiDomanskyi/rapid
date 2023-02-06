import store from '../store'
import router from '../routes'
import auth from './auth'
import exps from './exps'
import courses from './courses'
import coaches from './coaches'
import users from './users'
import postcodes from './postcodes'
import regions from './regions'
import documents from './documents'
import dates from './dates'
import book from './book'
import tracks from './tracks'
import payment from './payment'
import dialogs from './dialogs'
import vouchers from './vouchers'
import certificates from './certificates'
import exports from './exports'
import events from './events'
import trackDays from './trackDays'

const API_URL = '/api/v1/'

const responseHandler = (response) => {
  if (response.url.indexOf('/export/') + 1) {
    if (response.ok) {
      return response.text()
    }
  } else {
    const promise = response.json()

    if (response.status === 401) {
      store.dispatch('authLogout')
      return false
    }

    promise.then((resp) => {
      if (resp.messages && resp.messages.length) {
        for (const message of resp.messages) {
          store.commit('messagesSet', message)
        }
      }
    })

    return promise.then(response => response.data)
  }
}

const errorHandler = (reason) => {
  store.commit('messagesSet', reason)
}

export const params = () => {
  return router.history.current.params
}

export const request = (uri, data = {}, method = 'POST', withAbort = false) => {
  const controller = new AbortController()
  const { signal } = controller
  let csrf = document.head.querySelector('meta[name="csrf-token"]');
  
  const params = {
    method,
    headers: {
      Accept: 'application/json',
      'X-CSRF-TOKEN': csrf.content,
    },
    credentials: 'same-origin',
    signal,
  }

  if (!(data instanceof FormData)) {
    params.headers['Content-Type'] = 'application/json'
  }

  if (method !== 'GET') {
    params.body = !(data instanceof FormData) ? JSON.stringify(data) : data
  }

  const { token } = store.state.auth
  if (token) {
    params.headers.Authorization = `Bearer ${token}`
  }

  const promise = fetch(`${API_URL}${uri}`, params)
    .then(responseHandler, errorHandler)
    .catch(errorHandler)

  return withAbort ? [promise, controller.abort.bind(controller)] : promise
}

export default {
  auth,
  exps,
  courses,
  coaches,
  users,
  postcodes,
  regions,
  documents,
  dates,
  book,
  tracks,
  payment,
  dialogs,
  vouchers,
  certificates,
  exports,
  events,
  trackDays,
}
