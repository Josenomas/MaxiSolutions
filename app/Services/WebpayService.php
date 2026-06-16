<?php

namespace App\Services;

use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\Options;
use App\Models\Pago;

class WebpayService
{
    protected $transaction;
    
    public function __construct()
    {
        // Configurar para ambiente de integración o producción
        if (config('services.webpay.environment') === 'production') {
            $options = new Options(
                config('services.webpay.commerce_code'),
                config('services.webpay.api_key')
            );
        } else {
            // Usa las credenciales de integración por defecto
            $options = null;
        }
        
        $this->transaction = new Transaction($options);
    }
    
    /**
     * Crear una transacción de pago
     */
    public function createTransaction($buyOrder, $sessionId, $amount, $returnUrl)
    {
        try {
            $response = $this->transaction->create(
                $buyOrder,
                $sessionId,
                $amount,
                $returnUrl
            );
            
            return [
                'success' => true,
                'token' => $response->getToken(),
                'url' => $response->getUrl()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Confirmar transacción
     */
    public function commit($token)
    {
        try {
            $response = $this->transaction->commit($token);
            
            return [
                'success' => $response->isApproved(),
                'response' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener estado de una transacción
     */
    public function getStatus($token)
    {
        try {
            $response = $this->transaction->status($token);
            
            return [
                'success' => true,
                'response' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Reembolsar una transacción
     */
    public function refund($token, $amount)
    {
        try {
            $response = $this->transaction->refund($token, $amount);
            
            return [
                'success' => true,
                'response' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
