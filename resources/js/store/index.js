import Vue from 'vue'
import Vuex from 'vuex'
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
import messages from './messages'
import payment from './payment'
import dialogs from './dialogs'
import vouchers from './vouchers'
import certificates from './certificates'
import exports from './exports'
import events from './events'
import trackDays from './trackDays'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
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
    messages,
    payment,
    dialogs,
    vouchers,
    certificates,
    exports,
    events,
    trackDays,
  }
})

export default store