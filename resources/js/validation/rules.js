export default {
  assign(field, rules, options) {
    let result = []
    for (const rule of rules) {
      if (this[rule]) {
        result.push(this[rule](field, options))
      } else {
        console.log(`Undefined validation rule ${rule}`)
      }
    }

    return result
  },

  require(field, options) {
    return v => !! v || `${field} is required`
  },

  requireArray(field, options) {
    return v => !! v.length || `${field} is required`
  },

  email(field, options) {
    return v => /.+@.+\..+/.test(v) || `${field} must be valid`
  },

  phone(field, options) {
    return v => (v && v.length == 11 || ! v) || `${field} should consist of 11 digits`
  },

  min(field, options) {
    return v => (v && v.length >= options.min) || `${field} must be more than ${options.min} characters`
  },

  max(field, options) {
    return v => (v && v.length <= options.max) || `${field} must be less than ${options.max} characters`
  },
}