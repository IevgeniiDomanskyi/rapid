import { request } from './index'

const auth = {
  me() {
    return request('me', {}, 'GET')
  },

  login(data) {
    return request('login', data)
  },

  magic(data) {
    return request('magic', data)
  },

  register(data) {
    return request('register', data)
  },

  book(data) {
    return request('auth-book', data)
  },

  recovery(data) {
    return request('recovery', data)
  },

  activate(data) {
    return request(`activate/${data.hash}`, {}, 'GET')
  },

  profile(data) {
    return request('profile', data)
  },

  password(data) {
    return request('password', data)
  },
}

export default auth