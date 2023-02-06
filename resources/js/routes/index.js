import Vue from 'vue'
import VueRouter from 'vue-router'
import pages from '../pages'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Book',
    component: pages.Book,
  }, {
    path: '/course/:courseId/:level',
    name: 'BookCourse',
    component: pages.Book,
  }, {
    path: '/event/:eventId',
    name: 'Event',
    component: pages.Event,
  }, {
    path: '/event-enquiry/:eventId',
    name: 'EventEnquiry',
    component: pages.EventEnquiry,
  }, {
    path: '/track-day/:trackDayId',
    name: 'Track Day',
    component: pages.TrackDay,
  }, {
    path: '/track-day-enquiry/:trackDayId',
    name: 'TrackDayEnquiry',
    component: pages.TrackDayEnquiry,
  }, {
    path: '/enquiry',
    name: 'Enquiry',
    component: pages.Enquiry,
  }, {
    path: '/enquiry/course/:courseId/:level',
    name: 'EnquiryCourse',
    component: pages.Enquiry,
  }, {
    path: '/panel/magic/:token/:segment/:id?',
    name: 'Magic',
    component: pages.Magic,
  }, {
    path: '/panel',
    component: pages.Login,
    children: [
      {
        path: '',
        component: pages.Panel,
        children: [
          {
            path: '',
            name: 'Home',
            component: pages.Home,
          }, {
            path: 'details',
            name: 'Details',
            component: pages.Details,
          }, {
            path: 'payment',
            name: 'Payment',
            component: pages.Payment,
          }, {
            path: 'payment/:paymentId',
            name: 'Payment Book',
            component: pages.PaymentBook,
          }, {
            path: 'documents',
            name: 'Documents',
            component: pages.Documents,
          }, {
            path: 'bookings-customer',
            name: 'BookingsCustomer',
            component: pages.BookingsCustomer,
          }, {
            path: 'enquiries-customer',
            name: 'EnquiriesCustomer',
            component: pages.EnquiriesCustomer,
          }, {
            path: 'events-customer',
            name: 'EventsCustomer',
            component: pages.EventsCustomer,
          }, {
            path: 'bookings',
            name: 'Bookings',
            component: pages.Bookings,
          }, {
            path: 'enquiries',
            name: 'Enquiries',
            component: pages.Enquiries,
          }, {
            path: 'coaches',
            name: 'Coaches',
            component: pages.Coaches,
          }, {
            path: 'customers',
            name: 'Customers',
            component: pages.Customers,
          }, {
            path: 'customers/:customerId',
            name: 'CustomersDetails',
            component: pages.CustomersDetails,
          }, {
            path: 'tracks',
            name: 'Tracks',
            component: pages.Tracks,
          }, {
            path: 'events',
            name: 'Events/Rade Outs',
            component: pages.Events,
          }, {
            path: 'events/bookings',
            name: 'Event Bookings',
            component: pages.EventsBookings,
          }, {
            path: 'events/enquiries',
            name: 'Event Enquiries',
            component: pages.EventsEnquiries,
          }, {
            path: 'track-days',
            name: 'Track Days',
            component: pages.TrackDays,
          }, {
            path: 'track-days/bookings',
            name: 'Track Days Bookings',
            component: pages.TrackDaysBookings,
          }, {
            path: 'track-days/enquiries',
            name: 'Track Days Enquiries',
            component: pages.TrackDaysEnquiries,
          }, {
            path: 'reports/certificates',
            name: 'ReportsCertificates',
            component: pages.ReportsCertificates,
          }, {
            path: 'reports/vouchers',
            name: 'ReportsVouchers',
            component: pages.ReportsVouchers,
          }, {
            path: 'dialog/:dialogId',
            name: 'Dialog',
            component: pages.Dialog,
          }, {
            path: 'coach',
            component: pages.Coach,
            children: [
              {
                path: 'enquiries',
                name: 'EnquiriesCoach',
                component: pages.EnquiriesCoach,
              }, {
                path: 'coaches',
                name: 'CoachesCoach',
                component: pages.CoachesCoach,
              }, {
                path: 'customers',
                name: 'CustomersCoach',
                component: pages.CustomersCoach,
              }, {
                path: 'customers/:customerId',
                name: 'CustomersDetailsAdmin',
                component: pages.CustomersDetails,
              }, {
                path: 'tracks',
                name: 'TracksCoach',
                component: pages.TracksCoach,
              },
            ],
          }, {
            path: 'vouchers',
            name: 'Vouchers',
            component: pages.Vouchers,
          },
        ],
      },
    ],
  }, {
    path: '/panel/register',
    name: 'Register',
    component: pages.Register,
  }, {
    path: '/panel/recovery',
    name: 'Recovery',
    component: pages.Recovery,
  }, {
    path: '/panel/activate/:hash',
    name: 'Activate',
    component: pages.Activate,
  }, {
    path: '/panel/terms',
    name: 'Terms',
    component: pages.Terms,
  },
];

const router = new VueRouter({
  mode: 'history',
  routes,
})

export default router