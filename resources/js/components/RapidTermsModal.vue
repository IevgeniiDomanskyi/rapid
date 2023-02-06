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
        Terms and Conditions
      </template>
      
      <div class="terms-body">
        <div class="sqs-block-content">
          <p style="text-align:center;">
            <strong>RAPID TRAINING LIMITED </strong>
          </p>
          
          <p style="text-align:center;">
            Please read the following important terms and conditions before agreeing to complete our motorcycle training, events and courses and check they contain nothing that you are not willing to agree to.
          </p>
          
          <p>
            <strong>1. INFORMATION ABOUT US AND HOW TO CONTACT US&nbsp;</strong>
          </p>
          
          <p>
            1.1 <strong>Who we are.</strong> We are Rapid Training Limited a company registered in England and Wales. Our company registration number
            is 03389719 and our registered office is at Scottsdale House.10-31 Springfield Avenue.  Harrogate. HG1 2HR
          </p>
          
          <p>
            1.2 <strong>How to contact us.</strong> You can contact us by writing to us at Scottsdale House.10-31 Springfield Avenue.  Harrogate.
            HG1 2HR or be emailing us at enquiries@rapidtraining.co.uk
          </p>
          
          <p>
            <strong>2. OUR CONTRACT WITH YOU</strong>
          </p>
          
          <p>
            2.1. <strong>How we will accept your booking.</strong>
          </p>
          
          <p>
            Our acceptance of your booking will take place when we tell you that we are able to provide you with the coaching services or
            events and the date(s) on which they will be provided, or, after you have completed an online booking form,
            at which point a contract will come into existence between you and us. A contract is not formed between us prior to this time,
            for example when you enquire about the coaching or events and the dates currently available or when we send you a booking form to complete.
          </p>
          
          <p>
            2.2. <strong>If we cannot accept your booking.</strong> If we are unable to accept your booking, we will inform you of this. This is
            typically due to being unable to authorise your payment or our coaching/training services being fully booked.
          </p>
          
          <p>
            2.3. <strong>Other terms which are relevant to this contract.</strong> You also agree to be legally bound by the declaration which you
            will be required to complete and return to us before we provide the coaching services. You will also be required to confirm that the
            content of the declaration remains true on each day that you participate in the coaching services. The declaration forms part of this
            contract as though set out in full here.
          </p>
          
          <p>
            <strong>3. YOUR RIGHTS TO MAKE CHANGES</strong>
          </p>
          
          <p>
            3.1. If you wish to make a change to the date(s) of the coaching services or any other part of the coaching services or events,
            please contact us. We will let you know if the change is possible. If we are able to accommodate the change, we may charge you for
            any additional costs which are incurred as a result of the change, for example, the cost of cancelling any track hire (if applicable)
            on the day you can’t make and re-booking it. We will not make any change before we have notified you of any additional costs and
            you have confirmed that you would like to make the change and are prepared to pay the additional costs.
          </p>
          
          <p>
            3.2. If you cancel or postpone an agreed road coaching day with less than 48 hours written notice, we will not refund your payment for that day.
            If you wish to reschedule the day to another date, there will be a £75.00 (plus VAT) rebooking fee.
          </p>
          
          <p>
            <strong>4. OUR RIGHTS TO MAKE CHANGES</strong>
          </p>
          
          <p>
            4.1. <strong>Minor changes to the coaching services or events</strong>. We may make minor change the coaching services or events such as
            to reflect changes in relevant laws and regulations. These changes will not affect the overall coaching service provided to you.
          </p>
          
          <p>
            4.2. <strong>More significant changes to the coaching services or events and these terms</strong>. In addition, we may make more
            significant changes to these terms or the coaching services or events, but if we do so we will notify you and if you are not
            happy with the proposed changes you may choose to end the contract before the changes take effect.
          </p>
          
          <p>
            <strong>5. YOUR OBLIGATIONS</strong>
          </p>
          
          <p>
            5.1. Unless agreed otherwise we will only provide the services if you:
          </p>
          
          <p style="margin-left:40px;">
            5.1.1. provide your own roadworthy motorcycle with appropriate insurance, MOT certificate and road tax;
          </p>
          
          <p style="margin-left:40px;">
            5.1.2. have a valid, full current driving licence relevant to the motorcycle you are riding on for the coaching services;
          </p>
          
          <p style="margin-left:40px;">
            5.1.3. complete the attached declaration before the commencement of the coaching services; and
          </p>
          
          <p style="margin-left:40px;">
            5.1.4. at all times comply with the terms of your completed declaration.
          </p>
          
          <p>
            <strong>6. PROVIDING THE SERVICES</strong>
          </p>
          
          <p>
            6.1.<strong> When we will provide the coaching/training services.</strong> We will supply the coaching services to you on the date(s) set out
            in the booking or otherwise agreed between us.
          </p>
          
          <p>
            6.2. <strong>We are not responsible for delays outside our control.</strong> If we have to delay or cancel the coaching services as a result
            of something outside our control, for example poor weather conditions, then we will contact you as soon as possible to let you know and we
            will take steps to minimise the effect of the delay or cancellation. Provided we do this we will not be liable for the delay or cancellation
            caused by the event. If this happens, we may need to agree a different date(s) for the coaching services.
          </p>
          
          <p>
            6.3. <strong>What will happen if you do not provide required information to us.</strong> If you do not comply with your obligations
            (see clause 5) or do not provide us with other information we may require, or you provide us with incomplete or incorrect information,
            we may either end the contract or delay the coaching services or events and make an additional charge of a reasonable sum to compensate
            us for any costs incurred as a result for example, the cost of cancelling any track hire (if applicable) and re-booking it. We will not
            be responsible for providing the coaching services late or not providing any part of them if this is caused by you not giving us the
            information we need.
          </p>
          
          <p>
            6.4. <strong>Reasons we may delay the coaching/training services. </strong>We may have to delay, cancel or reorganise the coaching services
            such as if we need to update the coaching services to reflect changes in relevant law or regulatory requirements, or due to poor weather conditions.
          </p>
          
          <p>
            6.5. <strong>Your rights if we delay the coaching/training services. </strong>We will always try to contact you in advance to tell you
            we will be delaying, cancelling or reorganising the coaching services or events, unless the problem is urgent or an emergency.
            Where possible we will provide new date(s) for the coaching services or events as soon as possible. You may be able to end the
            contract as a result of our delay (see clause 7.1.2.).
          </p>
          
          <p>
            <strong>7. YOUR RIGHTS TO END THE CONTRACT</strong>&nbsp;&nbsp;
          </p>
          
          <p>
            7.1. <strong>Ending the contract because of something we have done or are going to do.</strong> If you are ending the contract for
            a reason set out at 7.1.1 to 7.1.3 below, the contract will end immediately and we will refund you in full for any coaching services
            or events which have not been provided to you or have not been properly provided to you. The reasons are:
          </p>
          
          <p style="margin-left:40px;">
            7.1.1. you are notified of an error or change to the price, description or terms of the coaching services or events and you do not wish to proceed;
          </p>
          
          <p style="margin-left:40px;">
            7.1.2. if we delay or advise we are going to delay the coaching services or events, for a period of more than two months; or
          </p>
          
          <p style="margin-left:40px;">
            7.1.3. you have a legal right to end the contract because of something we have done wrong.
          </p>
          
          <p>
            7.2. <strong>Exercising your right to change your mind.</strong> Where you have entered into this contract over the telephone or by email,
            you have a legal right to change your mind within 14 days of the date we entered into this contract and receive a refund for the coaching
            services or events which have not been provided.
          </p>
          
          <p>
            7.3. <strong>Ending the contract where we are not at fault and there is no right to change your mind.</strong> If you are ending the contract
            but we are not at fault and you have no right to change your mind, the contract will end immediately. You will receive a refund for the
            coaching services or events which you have not received but we may deduct any reasonable costs we incur as a result of your ending
            the contract, for example the cost of hiring the track (if applicable). If you have not made an advance payment, we may deduct or charge
            you the costs we will incur as a result of your breaking the contract.
          </p>
          
          <p>
            <strong>8. OUR RIGHTS TO END THE CONTRACT&nbsp;</strong>&nbsp;
          </p>
          
          <p>
            8.1. <strong>We may end the contract if you break it.</strong> We may end the contract at any time if:
          </p>
          
          <p style="margin-left:40px;">
            8.1.1. you do not make any payment due to us in accordance with clause 10.3 below;
          </p>
          
          <p style="margin-left:40px;">
            8.1.2. you do not provide us with information that is necessary for us to provide the coaching services or events;
          </p>
          
          <p style="margin-left:40px;">
            8.1.3. we do not think it is safe or legal for you to ride on the coaching course or events; or
          </p>
          
          <p style="margin-left:40px;">
            8.1.4. you do not comply with your obligations (see clause 5) or your completed declaration.
          </p>
          
          <p>
            8.2 <strong>You must compensate us if you break the contract.</strong> If we end the contract in the situations set out in clause 8.1 above we will
            refund any money you have paid in advance for coaching services or events we have not provided, but we may deduct or charge you the costs we will
            incur as a result of your breaking the contract.
          </p>
          
          <p>
            <strong>9. IF THERE IS A PROBLEM WITH THE COACHING/TRAINING SERVICES</strong>&nbsp;&nbsp;
          </p>
          
          <p>
            9.1. <strong>Your legal rights</strong>. The Consumer Rights Act 2015 gives you certain legal rights, for example we must perform our
            obligations with reasonable skill and care. For detailed advice, please visit the Citizens Advice website (www.adviceguide.org.uk) or call 03454 040506.
          </p>
          
          <p>
            9.2. <strong>How to tell us about problems.</strong> If you have any questions or complaints about the coaching/training services,
            please contact us by writing to us at Scottsdale House.10-31 Springfield Avenue. Harrogate. HG1 2HR or by emailing us at enquiries@rapidtraining.co.uk
          </p>
          
          <p>
            <strong>10. PRICE AND PAYMENT</strong>
          </p>
          
          <p>
            10.1. <strong>Where to find the price for the coaching services or events</strong>. The price of the coaching services or events
            (which includes VAT) will be the price confirmed at the date of your booking. We take all reasonable care to ensure that the prices
            of the coaching services or events advised to you are correct. If you believe we have charged you incorrectly please contact us promptly to let us know.&nbsp;
          </p>
          
          <p>
            10.2. <strong>We will pass on changes in the rate of VAT</strong>. If the rate of VAT changes between your booking date and the date we provide the
            coaching services or events, we will adjust the of rate of VAT that you pay, unless you have already paid in full before the change in the rate VAT takes effect.
          </p>
          
          <p>
            10.3. <strong>When you must pay and how you must pay</strong>. You must pay in full, at least 7 days in advance of the date the coaching
            services or events are scheduled to take place. This can be via a one-off payment or in instalments, as agreed by us in writing at the
            time of your booking. You must pay via the following methods [cheque, bank transfer, credit card, debit card, instalments or any other
            method agreed by us at the time of booking.
          </p>
          
          <p>
            <strong>11. OUR RESPONSIBILITY FOR LOSS OR DAMAGE SUFFERED BY YOU&nbsp;</strong>&nbsp;
          </p>
          
          <p>
            11.1. <strong>We are responsible to you for foreseeable loss and damage caused by us</strong>. If we fail to comply with these terms,
            we are responsible for loss or damage you suffer that is a foreseeable result of our breaking this contract or our failing to use
            reasonable care and skill, but we are not responsible for any loss or damage that is not foreseeable. Loss or damage is foreseeable
            if it is obvious that it will happen.
          </p>
          
          <p>
            11.2. <strong>We have no responsibility to you in some circumstances</strong>. We are not responsible to you for loss or damage
            which you suffer if it is as a result of you failing to comply with your completed declaration.&nbsp;
          </p>
          
          <p>
            11.3. <strong>We do not exclude or limit in any way our liability to you where it would be unlawful to do so</strong>. This includes
            liability for death or personal injury caused by our negligence or the negligence of our employees, agents or subcontractors; for
            fraud or fraudulent misrepresentation; for breach of your legal rights in relation to our obligations under this contract.&nbsp;&nbsp;
          </p>
          
          <p>
            11.4. <strong>We are not liable for business losses</strong>. We only supply the coaching services and events for domestic and private use.
            If you use the coaching services or events for any commercial or business purpose we will have no liability to you for any business losses
            including (amongst other things) any loss of profit, loss of business, business interruption, or loss of business opportunity.
          </p>
          
          <p>
            <strong>12. HOW WE MAY USE YOUR PERSONAL INFORMATION&nbsp;&nbsp;</strong>
          </p>
          
          <p>
            12.1. <strong>How we will use your personal information</strong>. We will only use your personal information as set out in our Privacy Policy.
            You can find our Privacy Policy on our website.
          </p>
          
          <p>
            <strong>13. OTHER IMPORTANT TERMS</strong>&nbsp;&nbsp;
          </p>
          
          <p>
            13.1. <strong>We may transfer this agreement to someone else</strong>. We may transfer our rights and obligations under these terms to another
            organisation. We will contact you to let you know if we plan to do this. If you are unhappy with the transfer and we have not started to
            provide the coaching services or events to you, you may contact us to end the contract within one month of us telling you about it and we
            will refund you any payments you have made in advance for coaching services or events not provided.
          </p>
          
          <p>
            13.2. <strong>Nobody else has any rights under this contract</strong>. This contract is between you and us. No other person shall have
            any rights to enforce any of its terms
          </p>
          
          <p>
            13.3. <strong>If a court finds part of this contract illegal, the rest will continue in force</strong>. Each of the paragraphs of
            these terms operates separately. If any court or relevant authority decides that any of them are unlawful, the remaining paragraphs
            will remain in full force and effect.
          </p>
          
          <p>
            13.4. <strong>Which laws apply to this contract and where you may bring legal proceedings</strong>. These terms are governed by
            English law and you can bring legal proceedings in respect of the services in the English courts.&nbsp;
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
    name: 'RapidTermsModal',

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