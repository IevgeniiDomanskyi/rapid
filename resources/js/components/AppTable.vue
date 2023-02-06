<template>
  <v-data-table
    v-model="selected"
    :headers="headers"
    :items="items"
    :search="search"
    :show-select="showSelect"
    :item-key="itemKey"
    :items-per-page.sync="itemsPerPage"
    :footer-props="{ 'items-per-page-options': [10, 30, 50, -1] }"
    dark
    class="app-table"
  >
    <template v-slot:top="{ pagination, options, updateOptions }">
      <div class="d-sm-flex align-center d-none">
        <a
          v-if="exportOptions"
          class="export-link"
          @click="onExport"
        >
          Export to CSV
        </a>

        <v-data-footer
          :pagination="pagination" 
          :options="{ ...options, itemsPerPage: itemsPerPage }"
          :items-per-page-options="[10, 30, 50, -1]"
          items-per-page-text="Rows per page:"
          class="data-footer-top"
          @update:options="updateOptions"
        />
      </div>
    </template>

    <template v-for="(index, name) in $scopedSlots" v-slot:[name]="data">
      <slot :name="name" v-bind="data"></slot>
    </template>
  </v-data-table>
</template>

<script>
  import { mapMutations, mapActions } from 'vuex'

  export default {
    name: 'AppTable',

    props: {
      value: {
        type: Array,
        default: () => [],
      },

      headers: {
        type: Array,
        default: null,
      },

      items: {
        type: Array,
        default: null,
      },

      search: {
        type: String,
        default: null,
      },

      showSelect: {
        type: Boolean,
        default: false,
      },

      itemKey: {
        type: String,
        default: 'id',
      },

      perPage: {
        type: Number,
        default: 30,
      },

      exportOptions: {
        type: Object,
        default: () => {},
      },
    },

    computed: {
      selected: {
        get() {
          return this.value
        },

        set(val) {
          this.$emit('input', val)
        },
      },

      itemsPerPage: {
        get() {
          return this.perPage
        },

        set(val) {
          this.$emit('perPage', val)
        },
      },
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'exportsGenerate',
      ]),

      onExport() {
        //if (this.selected.length > 0) {
          this.exportsGenerate({
            options: this.exportOptions,
            items: this.selectedIds(),
          })
        /* } else {
          this.messagesSet({
            text: 'Please, select at least one row',
            type: 'warning',
          })
        } */
      },

      selectedIds() {
        let result = []
        for (const item of this.selected) {
          result.push(item.id)
        }
        return result
      },
    },
  }
</script>

<style lang="scss">
  .app-table {
    &.v-data-table {
      background: transparent;
    }

    &.theme--dark.v-data-table {
      .v-data-footer.data-footer-top {
        border-top: 0;
        width: 100%;
      }
    }

    .export-link {
      white-space: nowrap;
      color: #fbfbfb;
      font-size: .75rem;
      margin-left: 15px;

      &:hover {
        opacity: 0.6;
      }
    }
  }
</style>