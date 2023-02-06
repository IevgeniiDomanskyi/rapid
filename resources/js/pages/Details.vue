<template>
  <div>
    <rapid-title>
      <app-text
        class="text-h5"
      >
        My Personal Details
      </app-text>
    </rapid-title>

    <rapid-content>
      <v-row
        class="mx-0"
      >
        <v-col
          :cols="12"
          :sm="6"
        >
          <app-panel>
            <app-text
              class="text-h6 mb-4"
            >
              Personal Info
            </app-text>

            <v-form
              ref="formProfile"
              v-model="validProfile"
              lazy-validation
            >
              <app-input-text
                v-model="profile.firstname"
                :rules="validate('First name')"
                label="First name"
                inverse
                @submit="onProfileSave"
              />

              <app-input-text
                v-model="profile.lastname"
                :rules="validate('Last name')"
                label="Last name"
                inverse
                @submit="onProfileSave"
              />

              <app-input-text
                v-model="profile.email"
                :rules="validate('Email')"
                label="Email"
                inverse
                type="email"
                @submit="onProfileSave"
              />

              <v-dialog
                v-model="isDobModalVisible"
                :return-value.sync="profile.dob"
                ref="dialog"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <app-input-text
                    v-if="me.role == 0"
                    v-model="profile.dob"
                    :rules="validate('Date of birth')"
                    label="Date of birth"
                    inverse
                    v-bind="attrs"
                    v-on="on"
                    @submit="onProfileSave"
                  />
                </template>

                <v-date-picker
                  v-model="profile.dob"
                  :max="max"
                  scrollable
                  @input="onDobSelected(profile.dob)"
                />
              </v-dialog>

              <app-input-text
                v-if="me.role == 0"
                v-model="profile.phone"
                :rules="validate('Telephone number')"
                label="Telephone number"
                inverse
                type="number"
                @submit="onProfileSave"
              />

              <div
                v-if="me.role == 0"
              >
                <app-text>
                  Rider Experience
                </app-text>

                <app-radio-group
                  v-model="profile.exp"
                  :list="expList"
                />
              </div>

              <div
                v-if="me.role == 0"
              >
                <app-checkbox
                  v-model="profile.gdpr"
                  :rules="validate('Receive emails confirmation')"
                  dark
                >
                  I'd like to receive emails from Rapid Training to stay up to date and to enhance my course and user experience
                </app-checkbox>
              </div>

              <div class="text-right">
                <app-button
                  :loading="isProfileLoading"
                  color="primary"
                  label="Save Personal Info"
                  @click="onProfileSave"
                />
              </div>
            </v-form>
          </app-panel>
        </v-col>

        <v-col
          v-if="me.role == 0"
          :cols="12"
          :sm="6"
        >
          <app-panel>
            <app-text
              class="text-h6 mb-4"
            >
              Billing Address
            </app-text>

            <v-form
              ref="formAddress"
              v-model="validAddress"
              lazy-validation
            >
              <app-input-text
                v-model="address.line1"
                :rules="validate('Address Line 1')"
                label="Address Line 1"
                inverse
                @submit="onAddressSave"
              />

              <app-input-text
                v-model="address.line2"
                label="Address Line 2"
                inverse
                @submit="onAddressSave"
              />

              <app-input-text
                v-model="address.town"
                :rules="validate('Town')"
                label="Town/City"
                inverse
                @submit="onAddressSave"
              />

              <app-input-text
                v-model="address.county"
                :rules="validate('County')"
                label="County"
                inverse
                @submit="onAddressSave"
              />

              <app-select
                v-model="address.country"
                :items="countryList"
                :rules="validate('Country')"
                label="Country"
                inverse
                @submit="onAddressSave"
              />

              <app-input-text
                v-model="address.postcode"
                :rules="validate('Postcode')"
                label="Postcode"
                inverse
                @submit="onAddressSave"
              />

              <div class="text-right">
                <app-button
                  :loading="isAddressLoading"
                  color="primary"
                  label="Save Billing Address"
                  @click="onAddressSave"
                />
              </div>
            </v-form>
          </app-panel>
        </v-col>

        <v-col
          :cols="12"
          :sm="6"
        >
          <app-panel>
            <app-text
              class="text-h6 mb-4"
            >
              Change Password
            </app-text>

            <v-form
              ref="formPassword"
              v-model="validPassword"
              lazy-validation
            >
              <app-input-text
                v-model="password.current"
                :rules="validate('Current password')"
                label="Current password"
                inverse
                type="password"
                @submit="onPasswordSave"
              />

              <app-input-text
                v-model="password.new"
                :rules="validate('New password')"
                label="New password"
                inverse
                type="password"
                @submit="onPasswordSave"
              />

              <div class="text-right">
                <app-button
                  :loading="isPasswordLoading"
                  color="primary"
                  label="Change password"
                  @click="onPasswordSave"
                />
              </div>
            </v-form>
          </app-panel>
        </v-col>
      </v-row>
    </rapid-content>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import RapidTitle from '../components/RapidTitle'
  import RapidContent from '../components/RapidContent'
  import AppText from '../components/AppText'
  import AppPanel from '../components/AppPanel'
  import AppInputText from '../components/AppInputText'
  import AppRadioGroup from '../components/AppRadioGroup'
  import AppButton from '../components/AppButton'
  import AppSelect from '../components/AppSelect'
  import AppCheckbox from '../components/AppCheckbox'
  import Rapid from '../mixins/rapid'
  import rules from '../validation/rules'

  export default {
    name: 'Details',

    mixins: [Rapid],

    components: {
      RapidTitle,
      RapidContent,
      AppText,
      AppPanel,
      AppInputText,
      AppRadioGroup,
      AppButton,
      AppSelect,
      AppCheckbox,
    },

    data: () => {
      return {
        validProfile: true,
        validAddress: true,
        validPassword: true,

        isProfileLoading: false,
        isAddressLoading: false,
        isPasswordLoading: false,

        max: new Date().toISOString().substr(0, 10),

        profile: {
          firstname: '',
          lastname: '',
          email: '',
          dob: null,
          phone: '',
          exp: 1,
          gdpr: false,
          link: '',
        },

        address: {
          line1: '',
          line2: '',
          town: '',
          city: '',
          county: '',
          country: '',
          postcode: '',
        },

        password: {
          current: '',
          new: '',
        },

        countryList: [
          { value: 'England', text: 'England' },
          { value: 'Scotland', text: 'Scotland' },
          { value: 'Wales', text: 'Wales' },
        ],

        isDobModalVisible: false,
      }
    },

    computed: {
      ...mapState({
        exps: state => state.exps.list,
        postcodes: state => state.postcodes.list,
      }),

      expList() {
        let result = []

        const desc = [
          '<br /><small>A rider who prefers to ride at a steady pace on the road, has limited or no track experience, or who lacks confidence at speed.</small>',
          '<br /><small>A rider who is relatively fast on the road but has little or no track experience.</small>',
          '<br /><small>A rider who is relatively fast on the road and has track experience.</small>',
        ]

        for (const exp of this.exps) {
          result.push({
            value: exp.value,
            label: exp.title + desc[exp.value - 1],
          })
        }

        return result
      },

     /*  postcodesList() {
        let result = []
        for (const postcode of this.postcodes) {
          result.push({
            value: postcode.id,
            text: (postcode.code + ' (' + postcode.area + ')'),
          })
        }
        return result
      }, */
    },

    watch: {
      me(val) {
        if (val) {
          this.profileInit()
        }
      },
    },

    created() {
      this.expsAll()
      // this.postcodesAll()

      if (this.me) {
        this.profileInit()
      }
    },

    methods: {
      ...mapMutations([
        'messagesSet',
      ]),

      ...mapActions([
        'authMe',
        'expsAll',
        // 'postcodesAll',
        'usersAddress',
        'usersAddressSave',
        'authProfile',
        'authPassword',
      ]),

      profileInit() {
        this.profile.firstname = this.me.firstname
        this.profile.lastname = this.me.lastname
        this.profile.email = this.me.email
        this.profile.dob = this.me.dob
        this.profile.phone = this.me.phone
        this.profile.gdpr = this.me.gdpr
        this.profile.exp = this.me.exp ? this.me.exp.value : 1

        this.usersAddress({
          userId: this.me.id
        }).then(data => {
          if (data && data.id) {
            this.address = {
              line1: data.line1,
              line2: data.line2,
              town: data.town,
              city: data.city,
              county: data.county,
              country: data.country,
              postcode: data.postcode,
            }
          }
        })
      },

      validate(field) {
        let r = []
        let o = {}

        switch (field) {
          case 'First name': r = ['require']; break
          case 'Last name': r = ['require']; break
          case 'Email': r = ['require', 'email']; break
          case 'Telephone number': r = ['phone']; break
          case 'Current password': r = ['require']; break
          case 'New password': r = ['require', 'min']; o = { min: 8 }; break
          case 'Address Line 1': r = ['require']; break
          case 'Town': r = ['require']; break
          case 'City': r = ['require']; break
          // case 'County': r = ['require']; break
          case 'Country': r = ['require']; break
          case 'Postcode': r = ['require']; break
          default: r = []; o = {}
        }
        
        return rules.assign(field, r, o)
      },

      onDobSelected(date) {
        this.$refs.dialog.save(date)
        this.isDobModalVisible = false
      },

      onProfileSave() {
        if (this.$refs.formProfile.validate()) {
          this.isProfileLoading = true
          this.profile.link = `${window.location.protocol}//${window.location.host}/panel/activate/{hash}`
          this.authProfile(this.profile).then(data => {
            this.isProfileLoading = false
          })
        }
      },

      onAddressSave() {
        if (this.$refs.formAddress.validate()) {
          this.isAddressLoading = true
          this.address.userId = this.me.id
          this.usersAddressSave(this.address).then(data => {
            this.isAddressLoading = false

            this.authMe()
          })
        }
      },

      onPasswordSave() {
        if (this.$refs.formPassword.validate()) {
          if (this.password.current == this.password.new) {
            this.messagesSet({
              text: 'New password should not match the current one',
              type: 'error',
            })
          } else {
            this.isPasswordLoading = true
            this.authPassword(this.password).then(data => {
              this.isPasswordLoading = false
            })
          }
        }
      },
    },
  }
</script>