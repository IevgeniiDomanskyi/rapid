import { request } from './index'

const regions = {
  all() {
    return request('regions', {}, 'GET')
  },
}

export default regions