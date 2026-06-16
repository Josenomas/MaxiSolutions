<?php

namespace App\Services;

use Exception;

class FlowService
{
    private $apiKey;
    private $secretKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.flow.api_key');
        $this->secretKey = config('services.flow.secret_key');
        $this->apiUrl = config('services.flow.environment') === 'production'
            ? 'https://www.flow.cl/api'
            : 'https://sandbox.flow.cl/api';
    }

    /**
     * Crea una nueva orden de pago en Flow
     */
    public function createPayment($params)
    {
        $params['apiKey'] = $this->apiKey;
        $params['s'] = $this->sign($params);

        $response = $this->makeRequest('/payment/create', $params);

        return $response;
    }

    /**
     * Obtiene el estado de un pago
     */
    public function getPaymentStatus($token)
    {
        $params = [
            'apiKey' => $this->apiKey,
            'token' => $token
        ];
        $params['s'] = $this->sign($params);

        return $this->makeRequest('/payment/getStatus', $params);
    }

    /**
     * Confirma un pago después del callback
     */
    public function confirmPayment($token)
    {
        $params = [
            'apiKey' => $this->apiKey,
            'token' => $token
        ];
        $params['s'] = $this->sign($params);

        return $this->makeRequest('/payment/getStatus', $params);
    }

    /**
     * Genera la firma HMAC para autenticar la solicitud
     */
    private function sign($params)
    {
        $keys = array_keys($params);
        sort($keys);

        $toSign = '';
        foreach ($keys as $key) {
            $toSign .= $key . $params[$key];
        }

        return hash_hmac('sha256', $toSign, $this->secretKey);
    }

    /**
     * Verifica la firma de un callback de Flow
     */
    public function verifySignature($params)
    {
        if (!isset($params['s'])) {
            return false;
        }

        $signature = $params['s'];
        unset($params['s']);

        $expectedSignature = $this->sign($params);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Realiza una petición HTTP a la API de Flow
     */
    private function makeRequest($endpoint, $params)
    {
        $url = $this->apiUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("Error en la comunicación con Flow: " . $error);
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Error HTTP {$httpCode} de Flow");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Respuesta inválida de Flow");
        }

        return $data;
    }

    /**
     * Genera la URL de pago de Flow
     */
    public function getPaymentUrl($token)
    {
        $baseUrl = config('services.flow.environment') === 'production'
            ? 'https://www.flow.cl/app/web'
            : 'https://sandbox.flow.cl/app/web';

        return $baseUrl . '/pay.php?token=' . $token;
    }
}
