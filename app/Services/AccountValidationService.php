<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AccountValidationService
{
    private $baseUrl;
    private $stsUrl;
    private $clientId;
    private $clientSecret;
    private $tokenCacheKey = 'zamupay_token';
    private $tokenExpiryBuffer = 300; // 5 minutes buffer before actual expiry

    public function __construct()
    {
        $this->baseUrl = config('services.zamupay.base_url');
        $this->stsUrl = config('services.zamupay.sts_url');
        $this->clientId = config('services.zamupay.client_id');
        $this->clientSecret = config('services.zamupay.client_secret');
        $this->callbackUrl = config('services.zamupay.callback_url');
        
        // Validate required configuration
        $this->validateConfig();
    }
    private function getToken()
    {
        // Try to get token from cache first
        if (Cache::has($this->tokenCacheKey)) {
            return Cache::get($this->tokenCacheKey);
        }

        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->post($this->stsUrl . '/connect/token', [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                    'scope' => 'PyPay_api'
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get token: ' . $response->body());
            }

            $data = $response->json();
            
            // Cache token for slightly less than its expiry time
            Cache::put(
                $this->tokenCacheKey, 
                $data['access_token'], 
                now()->addSeconds($data['expires_in'] - $this->tokenExpiryBuffer)
            );

            return $data['access_token'];

        } catch (\Exception $e) {
            Log::error('Token Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function validateConfig()
    {
        if (empty($this->callbackUrl)) {
            throw new \Exception('Zamupay callback URL is not configured');
        }
        
        if (empty($this->baseUrl) || empty($this->stsUrl) || empty($this->clientId) || empty($this->clientSecret)) {
            throw new \Exception('Required Zamupay configuration is missing');
        }
    }

    public function validateAccount($accountNumber, $institutionCode)
    {
        try {
            $token = $this->getToken();
            $traceAuditNumber = (string) Str::uuid();

            // Format phone number if needed
            if (!str_starts_with($accountNumber, '+254') && str_starts_with($accountNumber, '0')) {
                $accountNumber = '+254' . substr($accountNumber, 1);
            }

            $response = Http::withoutVerifying()
                ->withToken($token)
                ->post($this->baseUrl . '/v1/account/validate', [
                    'type' => 1,
                    'systemTraceAuditNumber' => $traceAuditNumber,
                    'primaryAccountNumber' => $accountNumber,
                    'institutionCode' => $institutionCode,
                    'callBackUrl' => $this->callbackUrl,
                    'callbackFormat' => 2,
                    'ccy' => 'KES',
                    'countryCode' => 'KE'
                ]);

            if (!$response->successful()) {
                Log::error('Account validation request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'account' => $accountNumber
                ]);
                throw new \Exception('Account validation request failed: ' . $response->body());
            }

            $data = $response->json();

            if (!isset($data['creditParty'])) {
                throw new \Exception('Invalid account validation response: ' . json_encode($data));
            }

            return [
                'account_holder' => $data['creditParty'],
                'account_number' => $accountNumber,
                'reference_number' => $data['systemTraceAuditNumber'],
                'message' => $data['message'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('Account Validation Error', [
                'error' => $e->getMessage(),
                'account' => $accountNumber,
                'institution' => $institutionCode
            ]);
            throw $e;
        }
    }

}
