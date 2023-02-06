<template>
  <v-dialog
    v-model="dialog"
    width="800"
  >
    <template v-slot:activator="{ on, attrs }">
      <slot name="activator" v-bind="{ on, attrs }"></slot>
    </template>

    <app-card>
      <template v-slot:title>
        Privacy Policy
      </template>
      
      <div class="terms-body">
        <div class="sqs-block-content">
          <p style="text-align:center;">
            <strong>WHAT WE COLLECT AND WHAT WE DO WITH IT</strong>
          </p>
          
          <p>
            This privacy policy sets out how we use and protect any information that you give when you use this website. We are committed to ensuring that
            your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then
            you can be assured that it will only be used in accordance with this privacy statement. We may change this policy from time to time by updating this page.
            You should check this page from time to time to ensure that you are happy with any changes.
          </p>
          
          <p>
            For the purpose of making an enquiring or placing an order, our website may collect the following information. Your name and appropriate contact
            information including email address, address, preferences and interests. Other information relevant to customer surveys and/or offers may also be requested.
          </p>
          
          <p>
            We require this information to understand your needs and provide you with more information for the product(s)/service(s) you have requested.
            Your information may also be stored in a CRM system for Internal record keeping. We may use the information to improve our products and services.
            We may periodically send promotional emails about new products, special offers or other information which we think you may find interesting
            using the email address which you have provided. From time to time, we may also use your information to contact you for market research purposes.
            We may contact you by email, phone, fax or mail. We may use the information to customise the website according to your interests.
            You may choose to restrict the collection or use of your personal information in the following ways:
          </p>
          
          <p>
            Whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information
            to be used by anybody for direct marketing purposes
          </p>
          
          <p>
            If you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by
            writing to or emailing us.
          </p>
          
          <p>
            We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so.
            We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell
            us that you wish this to happen. You may request details of personal information which we hold about you under the Data Protection Act 1998.
            A small fee may be payable. If you would like a copy of the information held on you please write to or email us. If you believe that any
            information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address.
            We will promptly correct any information found to be incorrect.
          </p>
          
          <h2 style="text-align:center;">Security</h2>
          
          <p>
            We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure, we have put in place suitable
            physical, electronic and managerial procedures to safeguard and secure the information we collect online.We will take reasonable technical and
            organisational precautions to prevent the loss, misuse or alteration of your personal information. We will never store your credit/debit card
            information on our website. Of course, data transmission over the internet is inherently insecure, and we cannot guarantee the security of data
            sent over the internet. You are responsible for keeping your password and user details confidential. We will not ask you for your password.
          </p>
          
          <h2 style="text-align:center;">External Links</h2>
          
          <p>
            Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note
            that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which
            you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the
            privacy statement applicable to the website in question.
          </p>
        </div>
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
    name: 'RapidPolicyModal',

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
    },

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
      onClose() {
        this.$emit('close')
      },
    },
  }
</script>

<style lang="scss" scoped>
  .terms-body {
    max-height: 50vh;
    overflow: auto;
    margin-bottom: 15px;

    p {
      font-size: 16px;
      line-height: 1.4;
    }
  }
</style>