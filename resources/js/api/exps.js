import { request } from './index'

const exps = {
  all() {
    return request('exp', {}, 'GET')
  },
}

export default exps