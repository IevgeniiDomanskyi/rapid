<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Entities\Customer;
use GlobalPayments\Api\Utils\GenerationUtils;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\PaymentMethods\RecurringPaymentMethod;


use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\HostedPaymentConfig;
use GlobalPayments\Api\Services\HostedService;
use GlobalPayments\Api\Entities\HostedPaymentData;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\Entities\Enums\RecurringSequence;
use GlobalPayments\Api\Entities\Enums\RecurringType;
use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\AddressType;

use GlobalPayments\Api\Services\Secure3dService;
use GlobalPayments\Api\Entities\Enums\Secure3dVersion;
use GlobalPayments\Api\Entities\Enums\ColorDepth;
use GlobalPayments\Api\Entities\ThreeDSecure;
use GlobalPayments\Api\Entities\BrowserData;
use GlobalPayments\Api\Entities\Enums\ChallengeWindowSize;
use GlobalPayments\Api\Entities\Enums\MethodUrlCompletion;

use Carbon\Carbon;
use App\Http\Services\Service;
use App\Http\Repositories\CardRepository;
use App\User;
use App\Card;

class CardService extends Service
{
    protected $cardRepository;
    protected $service;

    public function __construct(CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    public function init()
    {
        $config = new GpEcomConfig();
        $config->merchantId = env('GP_MERCHANT_ID');
        $config->accountId = env('GP_ACCOUNT_ID');
        $config->sharedSecret = env('GP_SHARED_SECRET');
        if (env('MIX_GP_MODE') == 'dev') {
            $config->serviceUrl = 'https://api.sandbox.realexpayments.com/epage-remote.cgi';
        } else {
            $config->serviceUrl = 'https://api.realexpayments.com/epage-remote.cgi';
        }
        ServicesContainer::configureService($config);
    }

    public function init3D()
    {
        $config = new GpEcomConfig();
        $config->merchantId = env('GP_MERCHANT_ID');
        $config->accountId = env('GP_ACCOUNT_ID');
        $config->sharedSecret = env('GP_SHARED_SECRET');

        /* $config->methodNotificationUrl = env('APP_URL').'/gp/method-notification/';
        $config->challengeNotificationUrl = env('APP_URL').'/gp/challenge-notification/'; */
        $config->methodNotificationUrl = 'https://www.rapidtraining.co.uk/gp/method-notification/';
        $config->challengeNotificationUrl = 'https://www.rapidtraining.co.uk/gp/challenge-notification/';

        $config->merchantContactUrl = 'https://www.rapidtraining.co.uk/about';
        $config->secure3dVersion = Secure3dVersion::TWO;
        ServicesContainer::configureService($config);
    }

    public function get($id)
    {
        return $this->cardRepository->get($id);
    }

    public function all(User $user)
    {
        return $this->cardRepository->all($user);
    }

    public function save(User $user, array $input)
    {
        $this->init();
        $customer = $this->customer($user);
        if ($customer) {
            $expire = new Carbon($input['expire']);

            $paymentMethodRef = GenerationUtils::getGuid();

            $card = new CreditCardData();
            $card->number = $input['card'];
            $card->expMonth = $expire->month;
            $card->expYear = $expire->year;
            $card->cardHolderName = $input['name'];

            $paymentMethod = $customer->addPaymentMethod($paymentMethodRef, $card);

            try {
                $response = $paymentMethod->create();
                if ($response->responseCode == '00') {
                    $card = $this->cardRepository->create([
                        'card' => $input['card'],
                        'gp_key' => $paymentMethodRef,
                    ]);
                    return $this->cardRepository->attach($user, $card);
                } else {
                    return response()->message($response->responseMessage, 'error', 500);
                }
            } catch (ApiException $e) {
                return response()->message($e->responseMessage, 'error', 500);
            }
        }

        return response()->message('System Error', 'error', 500);
    }

    public function customer(User $user)
    {
        $customer = new Customer();
        if ( ! empty($user->gp_key)) {
            $customer->key = $user->gp_key;
            $customer->firstName = $user->firstname;
            $customer->lastName = $user->lastname;
            $customer->email = $user->email;
            $customer->mobilePhone  = $user->phone;
        } else {
            $customer->key = GenerationUtils::getGuid();
            $customer->firstName = $user->firstname;
            $customer->lastName = $user->lastname;
            $customer->email = $user->email;
            $customer->mobilePhone  = $user->phone;

            try {
                $response = $customer->create();
                if ($response->responseCode == '00') {
                    service('User')->update($user, ['gp_key' => $customer->key]);
                } else {
                    return response()->message($response->responseMessage, 'error', 500);
                }
            } catch (ApiException $e) {
                return response()->message($e->responseMessage, 'error', 500);
            }
        }

        return $customer;
    }

    public function customerUpdate(User $user)
    {
        $this->init();

        if ( ! empty($user->gp_key)) {
            $customer = new Customer();
            $customer->key = $user->gp_key;

            $customer->firstName = $user->firstname;
            $customer->lastName = $user->lastname;
            $customer->email = $user->email;
            $customer->mobilePhone  = $user->phone;

            if ( ! empty($user->address)) {
                $address = new Address();
                $address->streetAddress1 = $user->address->line1;
                $address->streetAddress2 = $user->address->line2;
                $address->streetAddress3 = '';
                $address->city = $user->address->town;
                $address->postalCode = $user->address->postcode;
                $address->country = $user->address->country;
                $address->countryCode = 'UK';

                $customer->address = $address;
            }

            try {
                $response = $customer->saveChanges();
            } catch (ApiException $e) {
                dd($e);
                return response()->message($e->responseMessage, 'error', 500);
            }
        }
    }

    public function charge(User $user, Card $card, array $data)
    {
        $this->init();
        $customer = $this->customer($user);
        if ($customer) {
            $paymentMethod = new RecurringPaymentMethod($customer->key, $card->gp_key);

            $customer->title = 'Sam Jon';
            $customer->firstName = $user->firstname;
            $customer->lastName = $user->lastname;
            $customer->email = $user->email;
            $customer->mobilePhone  = $user->phone;

            if (empty($data['instalment'])) {
                $amount = $data['amount'];

                try {
                    $response = $paymentMethod->charge($amount)
                        ->withCurrency("GBP")
                        ->withOrderId($data['order_id'])
                        ->withProductId($data['product_id'])
                        ->withCustomerId($user->id)
                        ->withCustomerData($customer)
                        ->execute();

                    if ($response->responseCode == '00') {
                        return [
                            'order_id' => $response->orderId,
                            'auth_code' => $response->authorizationCode,
                            'transaction_id' => $response->transactionId,
                            'scheme_id' => $response->schemeId,
                            'status' => true,
                        ];
                    } else {
                        return response()->message($response->responseMessage, 'error', 500);
                    }
                } catch (ApiException $e) {
                    return response()->message($e->responseMessage, 'error', 500);
                }
            } else {
                $amount = round($data['amount'] / $data['instalment']);

                $response = $paymentMethod->charge($amount)
                    ->withCurrency("GBP")
                    ->execute();

                $xml = $this->generateXML([
                    'amount' => $amount.'00',
                    'instalment' => $data['instalment'] - 1,
                    'payer' => $customer->key,
                    'card' => $card->gp_key,
                    'book_id' => $data['book_id'],
                ]);
                
                $response = false;
                if (env('MIX_GP_MODE') == 'dev') {
                    $response = Http::withBody($xml, 'text/xml')->post('https://api.sandbox.realexpayments.com/epage-remote.cgi');
                } else {
                    $response = Http::withBody($xml, 'text/xml')->post('https://api.realexpayments.com/epage-remote.cgi');
                }

                $response = Http::withBody($xml, 'text/xml')->post('https://api.realexpayments.com/epage-remote.cgi');
                if ($response->ok()) {
                    $parse = simplexml_load_string($response->body());
                    if ($parse->result == '00') {
                        return true;
                    } else {
                        return response()->message($parse->message, 'error', 500);
                    }
                } else {
                    return response()->message('System Error', 'error', 500);
                }
            }
        }

        return response()->message('System Error', 'error', 500);
    }

    /* public function charge(User $user, Card $card, array $data)
    {
        $this->init3D();

        $customer = $this->customer($user);
        if ($customer) {
            $paymentMethod = new RecurringPaymentMethod($customer->key, $card->gp_key);

            $billingAddress = new Address();
            $billingAddress->streetAddress1 = $user->address->line1;
            $billingAddress->streetAddress2 = $user->address->line2;
            $billingAddress->streetAddress3 = '';
            $billingAddress->city = $user->address->town;
            $billingAddress->postalCode = $user->address->postcode;
            $billingAddress->country = '826';

            $browserData = new BrowserData();
            $browserData->acceptHeader = $_SERVER['HTTP_ACCEPT'];
            $browserData->colorDepth = ColorDepth::TWENTY_FOUR_BITS;
            $browserData->ipAddress = $_SERVER['REMOTE_ADDR'];
            $browserData->javaEnabled = true;
            $browserData->language = 'en';
            $browserData->screenHeight = 1080;
            $browserData->screenWidth = 1920;
            $browserData->challengWindowSize = ChallengeWindowSize::FULL_SCREEN;
            $browserData->timeZone = 0;
            $browserData->userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64, x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36';

            if (empty($data['instalment'])) {
                $amount = $data['amount'];

                $threeDSecureData = new ThreeDSecure();
                $threeDSecureData->serverTransactionId = '9de5bccf-c438-4024-8961-ec4b3cff15f5';

                $card = new CreditCardData();
                $card->number = '4012001038488884';
                $card->expMonth = '05';
                $card->expYear = '25';
                $card->cvn = '123';
                $card->cardHolderName = 'James Mason';

                try {
                    $threeDSecureData = Secure3dService::initiateAuthentication($card, $threeDSecureData)
                        ->withAmount($amount)
                        ->withCurrency('GBP')
                        ->withOrderCreateDate(date('Y-m-d H:i:s'))
                        ->withCustomerEmail($user->email)
                        ->withAddress($billingAddress, AddressType::BILLING)
                        ->withAddress($billingAddress, AddressType::SHIPPING)
                        ->withBrowserData($browserData)
                        ->withMethodUrlCompletion(MethodUrlCompletion::YES)
                        ->withMobileNumber('44', $user->phone)
                        ->execute('default', Secure3dVersion::TWO);
                } catch (ApiException $e) {
                    dd($e);
                    return response()->message($e->responseMessage, 'error', 500);
                }
            } else {
                $amount = round($data['amount'] / $data['instalment']);

                $response = $paymentMethod->charge($amount)
                    ->withCurrency("GBP")
                    ->execute();

                $xml = $this->generateXML([
                    'amount' => $amount.'00',
                    'instalment' => $data['instalment'] - 1,
                    'payer' => $customer->key,
                    'card' => $card->gp_key,
                    'book_id' => $data['book_id'],
                ]);
                
                $response = false;
                if (env('MIX_GP_MODE') == 'dev') {
                    $response = Http::withBody($xml, 'text/xml')->post('https://api.sandbox.realexpayments.com/epage-remote.cgi');
                } else {
                    $response = Http::withBody($xml, 'text/xml')->post('https://api.realexpayments.com/epage-remote.cgi');
                }

                $response = Http::withBody($xml, 'text/xml')->post('https://api.realexpayments.com/epage-remote.cgi');
                if ($response->ok()) {
                    $parse = simplexml_load_string($response->body());
                    if ($parse->result == '00') {
                        return true;
                    } else {
                        return response()->message($parse->message, 'error', 500);
                    }
                } else {
                    return response()->message('System Error', 'error', 500);
                }
            }
        }

        return response()->message('System Error', 'error', 500);
    } */

    public function generateXML($data)
    {
        $timestamp = date('YmdHis');
        $merchantId = env('GP_MERCHANT_ID', 'dev367278884153717891');
        $account = env('GP_ACCOUNT_ID', 'internet1');
        $scheduleref = time().$data['book_id'];
        $secret = env('GP_SHARED_SECRET', '1h1Xl2Bo6c');

        $blueprint = $timestamp.'.'.$merchantId.'.'.$scheduleref.'.'.$data['amount'].'.'.'GBP'.'.'.$data['payer'].'.'.'monthly';
        $hash = sha1($blueprint);
        $sha1hash = sha1($hash.'.'.$secret);

        $xml = '';
        $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<request type="schedule-new" timestamp="'.$timestamp.'">';
        $xml .= '<merchantid>'.$merchantId.'</merchantid>';
        $xml .= '<account>'.$account.'</account>';
        $xml .= '<scheduleref>'.$scheduleref.'</scheduleref>';
        $xml .= '<alias>Booking</alias>';
        $xml .= '<channel>ECOM</channel>';
        $xml .= '<orderidstub>rt</orderidstub>';
        $xml .= '<transtype>auth</transtype>';
        $xml .= '<schedule>monthly</schedule>';
        $xml .= '<numtimes>'.$data['instalment'].'</numtimes>';
        $xml .= '<payerref>'.$data['payer'].'</payerref>';
        $xml .= '<paymentmethod>'.$data['card'].'</paymentmethod>';
        $xml .= '<amount currency="GBP">'.$data['amount'].'</amount>';
        /* <prodid>Fitness First</prodid>
        <varref>My Legal Entity</varref>
        <custno>987654123</custno>
        <comment>Social Sign-Up</comment> */
        $xml .= '<sha1hash>'.$sha1hash.'</sha1hash>';
        $xml .= '</request>';

        return $xml;
    }

    public function remove(User $user, Card $card)
    {
        $this->init();
        $customer = $this->customer($user);
        if ($customer) {
            $paymentMethod = new RecurringPaymentMethod($customer->key, $card->gp_key);

            try {
                $paymentMethod->Delete();
            } catch (ApiException $e) {
                return response()->message($e->responseMessage, 'error', 500);
            }

            return $this->cardRepository->remove($card);
        }

        return response()->message('System Error', 'error', 500);
    }

    public function initDetails()
    {
        $config = new GpEcomConfig();
        $config->merchantId = env('GP_MERCHANT_ID');
        $config->accountId = env('GP_ACCOUNT_ID');
        $config->sharedSecret = env('GP_SHARED_SECRET');
        if (env('MIX_GP_MODE') == 'dev') {
            $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";
        } else {
            $config->serviceUrl = "https://pay.realexpayments.com/pay";
        }
        $config->hostedPaymentConfig = new HostedPaymentConfig();
        $config->hostedPaymentConfig->version = HppVersion::VERSION_2;
        $config->hostedPaymentConfig->cardStorageEnabled = "1";
        // $config->hostedPaymentConfig->displaySavedCards = true;
        $this->service = new HostedService($config);
    }

    public function details(User $user)
    {
        $this->initDetails();

        $me = empty($user) ? auth()->user() : $user;
        $me->gp_key;
        
        $hostedPaymentData = new HostedPaymentData();
        $hostedPaymentData->offerToSaveCard = false;
        $hostedPaymentData->customerEmail = $me->email;
        $hostedPaymentData->customerPhoneMobile = '44|'.$me->phone;

        if (empty($me->gp_key)) {
            $hostedPaymentData->customerExists = false;
        } else {
            $hostedPaymentData->customerExists = true;
            $hostedPaymentData->customerKey = $me->gp_key;
        }

        $hostedPaymentData->addressesMatch = true;

        $billingAddress = new Address();
        $billingAddress->streetAddress1 = $me->address->line1;
        $billingAddress->streetAddress2 = $me->address->line2;
        $billingAddress->streetAddress3 = '';
        $billingAddress->city = $me->address->town;
        $billingAddress->postalCode = $me->address->postcode;
        $billingAddress->country = "826";

        try {
            $hppJson = $this->service->Authorize(0.1)
                ->withCurrency("GBP")
                ->withHostedPaymentData($hostedPaymentData)
                //->withRecurringInfo(RecurringType::FIXED, RecurringSequence::FIRST)
                ->withAddress($billingAddress, AddressType::BILLING)
                ->withAddress($billingAddress, AddressType::SHIPPING)
                ->serialize();

            $temp = json_decode($hppJson);
            service('CardRequest')->clear($me);
            service('CardRequest')->save([
                'user_id' => $me->id,
                'order_id' => $temp->ORDER_ID,
            ]);

            return $hppJson;
        } catch (ApiException $e) {
            return response()->message($e->responseMessage, 'error', 500);
        }
    }

    public function process($response)
    {
        $this->initDetails();

        try {
            // create the response object from the response JSON
            $parsedResponse = $this->service->parseResponse($response, true);

            $responseCode = $parsedResponse->responseCode; // 00
            if ($parsedResponse->responseCode == '00') {
                $responseValues = $parsedResponse->responseValues; // get values accessible by key
                //dd($responseValues);
                $request = service('CardRequest')->byOrder($responseValues["ORDER_ID"]);
                if ( ! empty($request)) {
                    $user = service('User')->get($request->user_id);
                    $this->customerUpdate($user);

                    if ( ! empty($responseValues["PAYER_SETUP"]) && $responseValues["PAYER_SETUP"] == '00') {
                        service('User')->update($user, [
                            'gp_key' => $responseValues["SAVED_PAYER_REF"],
                        ]);
                    }

                    if ( ! empty($responseValues["PMT_SETUP"]) && $responseValues["PMT_SETUP"] == '00') {
                        $card = $this->cardRepository->create([
                            'card' => $responseValues["SAVED_PMT_DIGITS"],
                            'gp_key' => $responseValues["SAVED_PMT_REF"],
                        ]);
                        $this->cardRepository->attach($user, $card);
                    }

                    service('CardRequest')->clear($user);

                    service('User')->update($user, [
                        'payment_request' => false,
                    ]);
                }

                return ['Card was added', 'success'];
            } else {
                return [$parsedResponse->responseMessage, 'error'];
            }
        } catch (ApiException $e) {
            return [$e->responseMessage, 'error'];
        }
    }
}
