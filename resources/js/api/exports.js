import { request } from './index'

const exports = {
  generate(data) {
    return request('export/generate', data, 'POST')
  },
}

export default exports