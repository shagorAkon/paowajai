<?php

namespace App\Services\Courier;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourierService
{
    protected string $pathaoBaseUrl;
    protected string $pathaoClientId;
    protected string $pathaoClientSecret;
    protected string $redxBaseUrl;
    protected string $redxApiKey;
    protected string $steadfastBaseUrl;
    protected string $steadfastApiKey;

    public function __construct()
    {
        $this->pathaoBaseUrl = config('services.couriers.pathao.base_url', 'https://api-hermes.pathao.com');
        $this->pathaoClientId = config('services.couriers.pathao.client_id', '');
        $this->pathaoClientSecret = config('services.couriers.pathao.client_secret', '');
        
        $this->redxBaseUrl = config('services.couriers.redx.base_url', 'https://api.redx.com.bd');
        $this->redxApiKey = config('services.couriers.redx.api_key', '');

        $this->steadfastBaseUrl = config('services.couriers.steadfast.base_url', 'https://portal.steadfast.com.bd/api/v1');
        $this->steadfastApiKey = config('services.couriers.steadfast.api_key', '');
    }

    /**
     * Dispatch an order to the selected courier.
     */
    public function dispatchOrder(\App\Models\Order $order, string $courier): array
    {
        switch (strtolower($courier)) {
            case 'pathao':
                return $this->dispatchToPathao($order);
            case 'redx':
                return $this->dispatchToRedx($order);
            case 'steadfast':
                return $this->dispatchToSteadfast($order);
            default:
                throw new \InvalidArgumentException("Unsupported courier: {$courier}");
        }
    }

    /**
     * Pathao Delivery dispatch implementation
     */
    protected function dispatchToPathao(\App\Models\Order $order): array
    {
        if (empty($this->pathaoClientId)) {
            // Return robust simulated response if creds are not set (local sandbox mode)
            Log::warning("Pathao credentials not set. Simulating courier dispatch.");
            return [
                'success' => true,
                'tracking_number' => 'PTH-' . rand(100000, 999999),
                'consignment_id' => rand(10000000, 99999999),
                'message' => 'Simulated: Consignment created successfully on Pathao.',
            ];
        }

        try {
            // 1. Fetch access token (OAuth client credentials)
            $tokenResponse = Http::post("{$this->pathaoBaseUrl}/aladdin/api/v1/issue-token", [
                'client_id' => $this->pathaoClientId,
                'client_secret' => $this->pathaoClientSecret,
                'username' => config('services.couriers.pathao.username'),
                'password' => config('services.couriers.pathao.password'),
                'grant_type' => 'password',
            ]);

            if ($tokenResponse->failed()) {
                throw new \Exception("Pathao Auth failed: " . $tokenResponse->body());
            }

            $token = $tokenResponse->json()['access_token'];

            // 2. Create the delivery order
            $payload = [
                'store_id' => config('services.couriers.pathao.store_id'),
                'merchant_order_id' => $order->order_number,
                'sender_name' => config('app.name'),
                'sender_phone' => config('services.couriers.pathao.sender_phone'),
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_address' => $order->shipping_address,
                'recipient_city' => $order->shipping_city,
                'recipient_zone' => $order->shipping_district,
                'recipient_area' => $order->shipping_zip,
                'delivery_type' => 48, // Normal Delivery
                'item_type' => 1, // Document/Parcel
                'item_quantity' => $order->items->sum('quantity'),
                'amount_to_collect' => $order->payment_method === 'cod' ? (float) $order->total : 0.0,
                'item_description' => 'E-commerce goods',
            ];

            $response = Http::withToken($token)
                ->post("{$this->pathaoBaseUrl}/aladdin/api/v1/orders", $payload);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['message'] ?? 'Pathao booking error',
                ];
            }

            $data = $response->json()['data'];
            return [
                'success' => true,
                'tracking_number' => $data['consignment_id'],
                'consignment_id' => $data['consignment_id'],
                'message' => 'Consignment created successfully on Pathao.',
            ];

        } catch (\Exception $e) {
            Log::error("Pathao dispatch exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Redx Delivery dispatch implementation
     */
    protected function dispatchToRedx(\App\Models\Order $order): array
    {
        if (empty($this->redxApiKey)) {
            Log::warning("Redx credentials not set. Simulating courier dispatch.");
            return [
                'success' => true,
                'tracking_number' => 'REDX-' . rand(100000, 999999),
                'consignment_id' => rand(10000000, 99999999),
                'message' => 'Simulated: Consignment created successfully on RedX.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'API-KEY' => $this->redxApiKey,
                'Accept' => 'application/json',
            ])->post("{$this->redxBaseUrl}/api/v1/parcels", [
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'delivery_area' => $order->shipping_city,
                'delivery_area_id' => 1, // Look up via API in real app
                'customer_address' => $order->shipping_address,
                'merchant_value' => (float) $order->total,
                'cash_to_collect' => $order->payment_method === 'cod' ? (float) $order->total : 0.0,
                'product_weight' => 0.5,
                'instruction' => $order->notes ?? '',
            ]);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['message'] ?? 'RedX booking error',
                ];
            }

            $data = $response->json();
            return [
                'success' => true,
                'tracking_number' => $data['tracking_id'],
                'consignment_id' => $data['id'],
                'message' => 'Consignment created successfully on RedX.',
            ];

        } catch (\Exception $e) {
            Log::error("RedX dispatch exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * SteadFast Delivery dispatch implementation
     */
    protected function dispatchToSteadfast(\App\Models\Order $order): array
    {
        if (empty($this->steadfastApiKey)) {
            Log::warning("Steadfast credentials not set. Simulating courier dispatch.");
            return [
                'success' => true,
                'tracking_number' => 'SF-' . rand(100000, 999999),
                'consignment_id' => rand(10000000, 99999999),
                'message' => 'Simulated: Consignment created successfully on SteadFast.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->steadfastApiKey,
                'Secret-Key' => config('services.couriers.steadfast.secret_key', ''),
                'Content-Type' => 'application/json',
            ])->post("{$this->steadfastBaseUrl}/create_order", [
                'invoice' => $order->order_number,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_address' => $order->shipping_address,
                'cod_amount' => $order->payment_method === 'cod' ? (float) $order->total : 0.0,
                'note' => $order->notes ?? '',
            ]);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['message'] ?? 'SteadFast booking error',
                ];
            }

            $data = $response->json();
            return [
                'success' => true,
                'tracking_number' => $data['consignment']['tracking_code'],
                'consignment_id' => $data['consignment']['consignment_id'],
                'message' => 'Consignment created successfully on SteadFast.',
            ];

        } catch (\Exception $e) {
            Log::error("SteadFast dispatch exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
