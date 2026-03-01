<?php

namespace App\Components\Gateways;

use Exception;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Contracts\GatewayInterface;
use Illuminate\Support\Facades\Http;

class Polar implements GatewayInterface
{
    protected $config;
    protected $request;

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    public function isActive(): bool
    {
        return (bool) setting('POLAR_ALLOW', 0);
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['accessToken']) && !empty($this->config['organizationId']);
    }

    public function getName(): string
    {
        return "Polar (Automated)";
    }

    public function getIcon(): string
    {
        return '<i class="bi bi-lightning-charge-fill"></i>';
    }

    public function initialize()
    {
        // Initialization if needed
    }

    public function render()
    {
        return view('checkout.polar')->render();
    }

    public function processPayment($transaction)
    {
        try {
            // Polar usually works with hosted checkouts or direct subscription links
            // For a "bulletproof" automated way, we redirect to Polar's checkout
            
            $plan = $transaction->plan_id == 0 ? ads_plan() : Plan::find($transaction->plan_id);
            $polarProductId = $transaction->plan_type == 'yearly' ? $plan->polar_yearly_id : $plan->polar_monthly_id;

            if (empty($polarProductId)) {
                throw new Exception("Polar Product ID not configured for this plan.");
            }

            // Create a checkout session via Polar API
            $response = Http::withToken($this->config['accessToken'])
                ->post('https://api.polar.sh/api/v1/checkouts/custom', [
                    'product_id' => $polarProductId,
                    'success_url' => route('payments.success', ['transaction_id' => $transaction->id]) . '&polar_session_id={CHECKOUT_SESSION_ID}',
                    'customer_email' => $transaction->email,
                    'metadata' => [
                        'transaction_id' => $transaction->id,
                        'user_id' => $transaction->user_id
                    ]
                ]);

            if ($response->failed()) {
                throw new Exception("Polar API Error: " . $response->body());
            }

            $checkout = $response->json();
            
            // Redirect user to Polar checkout
            return redirect($checkout['url']);

        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function verifyPayment($transaction, $request): bool
    {
        // Verify via Polar API if the session is paid
        $polarSessionId = $request->polar_session_id;
        
        if (empty($polarSessionId)) return false;

        $response = Http::withToken($this->config['accessToken'])
            ->get("https://api.polar.sh/api/v1/checkouts/custom/{$polarSessionId}");

        if ($response->successful()) {
            $checkout = $response->json();
            return $checkout['status'] === 'confirmed' || $checkout['status'] === 'succeeded';
        }

        return false;
    }

    public function webhook(Request $request)
    {
        // Handle Polar webhooks (subscription.created, order.created, etc.)
        $payload = $request->all();
        $type = $payload['type'] ?? '';

        if ($type === 'checkout.succeeded') {
            $transactionId = $payload['data']['metadata']['transaction_id'] ?? null;
            if ($transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction && $transaction->status !== 1) {
                    $transaction->status = 1;
                    $transaction->transaction_id = $payload['data']['id'];
                    $transaction->expiry_date = $transaction->plan_type == "yearly" ? now()->addYear() : now()->addMonth();
                    $transaction->save();
                    return response()->json(['status' => 'success']);
                }
            }
        }

        return response()->json(['status' => 'ignored']);
    }
}
