import { request } from './index'

const courses = {
  all() {
    return request('course', {}, 'GET')
  },
}

export default courses