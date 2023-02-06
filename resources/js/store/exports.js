import api from '../api'

const exports = {
  state: {
    list: [],
  },

  mutations: {
    exportsSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    exportsGenerate({ }, payload) {
      return api.exports.generate(payload).then(data => {
        if (data) {
          const downloadLink = document.createElement('a')
          const fileData = ['\ufeff' + data]

          const blobObject = new Blob(fileData, {
            type: 'text/csv;charset=utf-8;',
          })

          const url = URL.createObjectURL(blobObject)
          downloadLink.href = url
          downloadLink.download = payload.options.file + '.csv'

          document.body.appendChild(downloadLink)
          downloadLink.click()
          document.body.removeChild(downloadLink)
        }

        return data
      })
    },
  },
}

export default exports