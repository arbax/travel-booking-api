<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    #[OA\Post(
        path: '/api/bookings/{booking}/payment',
        summary: 'Process payment for a booking',
        security: [['sanctum' => []]],
        tags: ['Payments'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['method'],
                properties: [
                    new OA\Property(property: 'method', type: 'string', enum: ['card', 'cash', 'online']),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Payment processed'),
            new OA\Response(response: 422, description: 'Already paid or booking cancelled'),
        ]
    )]

    public function store(StorePaymentRequest $request, Booking $booking): JsonResponse
    {
        $payment = $this->paymentService->process($booking, $request->validated());

        return response()->json(['data' => new PaymentResource($payment)], 201);
    }

    #[OA\Get(
        path: '/api/bookings/{booking}/payment',
        summary: 'Get payment for a booking',
        security: [['sanctum' => []]],
        tags: ['Payments'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Payment details'),
            new OA\Response(response: 404, description: 'No payment found'),
        ]
    )]
    
    public function show(Booking $booking): JsonResponse
    {
        $payment = $booking->payment;

        if (!$payment) {
            return response()->json(['message' => 'No payment found.'], 404);
        }

        return response()->json(['data' => new PaymentResource($payment)]);
    }
}