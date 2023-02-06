<template>
  <div />
</template>

<script>
  import { mapState, mapMutations } from 'vuex'

  export default {
    name: 'AppNotifications',

    computed: {
      ...mapState({
        messages: state => state.messages.list,
      }),
    },

    watch: {
      messages(val, oldVal) {
        if (val.length) {
          for (const message of val) {
            if ( ! message.text && ! message.type) {
              console.log('MESSAGE:', message)
            } else {
              this.$toastr.Add({
                title: this.ucfirst(message.type),
                msg: message.text,
                timeout: 3000,
                position: 'toast-top-right',
                type: message.type,
                clickClose: true,
                progressbar: false,
                style: { boxShadow: '0 0 6px rgba(0, 0, 0, 0.5)' },
              })
            }
          }

          this.messagesClear()
        }
      },
    },

    mounted() {
      if (serverMessage && serverMessage.length > 1) {
        this.messagesSet({
          text: serverMessage[0],
          type: serverMessage[1],
        })
      }
    },

    methods: {
      ...mapMutations([
        'messagesClear',
        'messagesSet',
      ]),

      ucfirst(str) {
        return str[0].toUpperCase() + str.slice(1)
      },
    },
  }
</script>