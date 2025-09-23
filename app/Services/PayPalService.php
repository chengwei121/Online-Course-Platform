<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private $client;
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        
        $mode = config('services.paypal.mode', 'sandbox');
        $this->baseUrl = $mode === 'live' 
            ? config('services.paypal.live.base_uri')
            : config('services.paypal.sandbox.base_uri');
    }

    /**
     * Get PayPal access token
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            $response = $this->client->post($this->baseUrl . '/v1/oauth2/token', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en_US',
                ],
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $this->accessToken = $data['access_token'];
            
            return $this->accessToken;
        } catch (RequestException $e) {
            Log::error('PayPal OAuth Error: ' . $e->getMessage());
            throw new \Exception('Failed to authenticate with PayPal');
        }
    }

    /**
     * Create a PayPal payment
     */
    public function createPayment($amount, $currency, $description, $successUrl, $cancelUrl, $courseId = null)
    {
        $accessToken = $this->getAccessToken();

        $paymentData = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal'
            ],
            'transactions' => [
                [
                    'amount' => [
                        'total' => number_format($amount, 2, '.', ''),
                        'currency' => $currency
                    ],
                    'description' => $description,
                    'custom' => $courseId ? json_encode(['course_id' => $courseId]) : null
                ]
            ],
            'redirect_urls' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl
            ]
        ];

        try {
            $response = $this->client->post($this->baseUrl . '/v1/payments/payment', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'json' => $paymentData
            ]);

            $payment = json_decode($response->getBody(), true);
            
            // Find the approval URL
            $approvalUrl = null;
            foreach ($payment['links'] as $link) {
                if ($link['rel'] === 'approval_url') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            return [
                'payment_id' => $payment['id'],
                'approval_url' => $approvalUrl,
                'payment' => $payment
            ];
        } catch (RequestException $e) {
            Log::error('PayPal Payment Creation Error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error('PayPal Response: ' . $e->getResponse()->getBody());
            }
            throw new \Exception('Failed to create PayPal payment');
        }
    }

    /**
     * Execute a PayPal payment
     */
    public function executePayment($paymentId, $payerId)
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->post($this->baseUrl . "/v1/payments/payment/{$paymentId}/execute", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'json' => [
                    'payer_id' => $payerId
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('PayPal Payment Execution Error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error('PayPal Response: ' . $e->getResponse()->getBody());
            }
            throw new \Exception('Failed to execute PayPal payment');
        }
    }

    /**
     * Get payment details
     */
    public function getPaymentDetails($paymentId)
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->get($this->baseUrl . "/v1/payments/payment/{$paymentId}", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('PayPal Get Payment Error: ' . $e->getMessage());
            throw new \Exception('Failed to get PayPal payment details');
        }
    }

    /**
     * Check if payment is approved
     */
    public function isPaymentApproved($payment)
    {
        return $payment['state'] === 'approved';
    }

    /**
     * Get transaction amount from payment
     */
    public function getTransactionAmount($payment)
    {
        return isset($payment['transactions'][0]['amount']['total']) 
            ? (float) $payment['transactions'][0]['amount']['total'] 
            : 0;
    }

    /**
     * Get custom data from payment (like course_id)
     */
    public function getCustomData($payment)
    {
        $custom = $payment['transactions'][0]['custom'] ?? null;
        return $custom ? json_decode($custom, true) : null;
    }
}