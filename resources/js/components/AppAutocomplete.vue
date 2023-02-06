<template>
  <v-autocomplete
    v-model="selections"
    :label="label"
    :items="items"
    :item-text="itemText"
    :item-value="itemValue"
    :rules="rules"
    :color="inverse ? $colors.text : $colors.primary"
    :readonly="readonly"
    :disabled="disabled"
    :multiple="multiple"
    :chips="chips"
    autocomplete="off"
    class="app-autocomplete"
  >
    <template v-slot:selection="data">
      <v-chip
        v-if="chips"
        :input-value="data.selected"
        :color="$colors.primary"
        close
        v-bind="data.attrs"
        @click="data.select"
        @click:close="remove(data.item)"
      >
        {{ data.item[itemText] }}
      </v-chip>

      <span
        v-else
      >
        {{ data.item[itemText] }}
      </span>
    </template>

    <template v-slot:item="data">
      <template v-if="(typeof data.item) !== 'object'">
        <v-list-item-content v-text="data.item"></v-list-item-content>
      </template>

      <template v-else>
        <v-list-item-content>
          <v-list-item-title v-html="data.item[itemText] + (data.item[itemFullText] ? (' (' + data.item[itemFullText] + ')') : '')"></v-list-item-title>
        </v-list-item-content>
      </template>
    </template>

  </v-autocomplete>
</template>

<script>
  export default {
    name: 'AppAutocomplete',

    props: {
      value: {
        type: [Array, Number, String],
        default: null,
      },

      inverse: {
        type: Boolean,
        default: false,
      },

      label: {
        type: String,
        default: null,
      },

      items: {
        type: Array,
        default: () => [],
      },

      itemText: {
        type: String,
        default: 'name',
      },

      itemFullText: {
        type: String,
        default: 'description',
      },

      itemValue: {
        type: String,
        default: 'id',
      },

      rules: {
        type: Array,
        default: () => [],
      },

      readonly: {
        type: Boolean,
        default: false,
      },

      disabled: {
        type: Boolean,
        default: false,
      },

      multiple: {
        type: Boolean,
        default: true,
      },

      chips: {
        type: Boolean,
        default: true,
      },
    },

    computed: {
      selections: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },
    },

    methods: {
      remove (item) {
        const index = this.selections.indexOf(item[this.itemValue])
        if (index >= 0) this.selections.splice(index, 1)
      },
    },
  }
</script>

<style lang="scss">
  .app-autocomplete {
    .v-label {
      font-size: 14px;
    }

    &.v-select.v-select--chips {
      .v-select__selections {
        min-height: 32px;
      }
    }
  }
</style>