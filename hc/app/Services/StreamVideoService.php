<?php
// app/Services/StreamVideoService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class StreamVideoService
{
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->apiKey = config('services.stream.video_key');
        $this->apiSecret = config('services.stream.video_secret');
    }

    /**
     * Generate JWT token for Stream Video
     */
    public function generateToken($userId, $expiration = 3600 * 24)
    {
        try {
            $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
            $now = time();
            $payload = json_encode([
                'user_id' => (string) $userId,
                'exp' => $now + $expiration,
                'iat' => $now - 10,  // Subtract 10 seconds to account for clock skew
            ]);

            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);
            
            $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->apiSecret, true);
            $base64UrlSignature = $this->base64UrlEncode($signature);
            
            return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
            
        } catch (\Exception $e) {
            Log::error('Token generation failed:', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to generate video token: ' . $e->getMessage());
        }
    }

    /**
     * Generate frontend configuration
     */
    public function getFrontendConfig($userId, $userName, $userImage = null)
    {
        return [
            'apiKey' => $this->apiKey,
            'token' => $this->generateToken($userId),
            'user' => [
                'id' => (string) $userId,
                'name' => $userName,
                'image' => $userImage,
            ],
            'options' => [
                'logLevel' => 'info',
                'timeout' => 15000,
            ]
        ];
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function isConfigured()
    {
        return !empty($this->apiKey) && !empty($this->apiSecret);
    }
}