<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function store(StorePaymentRequest $request, Booking $booking): JsonResponse
    {
        $payment = $this->paymentService->process($booking, $request->validated());

        return response()->json(['data' => new PaymentResource($payment)], 201);
    }

    public function show(Booking $booking): JsonResponse
    {
        $payment = $booking->payment;

        if (!$payment) {
            return response()->json(['message' => 'No payment found.'], 404);
        }

        return response()->json(['data' => new PaymentResource($payment)]);
    }
}