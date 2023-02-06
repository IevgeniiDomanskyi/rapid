<template>
  <v-dialog
    v-model="dialog"
    width="500"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card
      inverse
    >
      <template v-slot:title>
        Customer's Documents
      </template>
      
      <div
        class="assign-coaches-body"
      >
        <app-text
          v-if=" ! docs.length"
        >
          There are no documents for this customer
        </app-text>

        <v-list
          v-else
          rounded
        >
          <v-list-item
            v-for="(item, index) in docs"
            :key="index"
          >
            <v-list-item-content>
              <v-list-item-title>{{ typeText(item.type, item.course) }}</v-list-item-title>
              <v-list-item-subtitle>{{ item.updated_at }}</v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-action>
              <app-button
                :href="(item.file != '' ? item.file : '/pdf/RAPID-TRAINING-LIMITED-T&CDeclaration.pdf')"
                label="Download"
                small
                color="blue"
                target="_blank"
              />
            </v-list-item-action>
          </v-list-item>
        </v-list>
      </div>

      <template v-slot:actions>
        <v-spacer></v-spacer>

        <app-button
          label="Close"
          color="primary"
          outlined
          @click="onClose"
        />
      </template>
    </app-card>
  </v-dialog>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import AppCard from './AppCard'
  import AppText from './AppText'
  import AppButton from './AppButton'

  export default {
    name: 'RapidDocsModal',

    components: {
      AppCard,
      AppText,
      AppButton,
    },

    props: {
      value: {
        type: Boolean,
        default: false,
      },

      docs: {
        type: Array,
        default: () => [],
      },
    },

    data: () => ({
      isLoading: false,
    }),

    computed: {
      dialog: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      typeText(type, course) {
        switch (type) {
          case 'book': return 'Booking'
          case 'road1': return 'Rider Declaration Road Date 1'
          case 'road2': return 'Rider Declaration Road Date 2'
          case 'road3': return 'Rider Declaration Road Date 3'
          case 'road4': return 'Rider Declaration Road Date 4'
          case 'road5': return 'Rider Declaration Road Date 5'
          case 'road6': return 'Rider Declaration Road Date 6'
          case 'certificate': return 'Certificate ' + course
          case 'congratulation': return 'Congratulation ' + course
          case 'report': return 'Report ' + course
          default: type
        }
      },

      onClose() {
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  .assign-coaches-body {
    min-height: 200px;
    max-height: 360px;
    overflow: auto;
    margin-bottom: 15px;
  }
</style>

<style lang="scss">
  .theme--dark.v-list {
    background: transparent;

    &.v-list--rounded {
      padding: 10px 0;
    }
  }
</style>