<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Travel Booking API',
    version: '1.0.0',
    description: 'API documentation for the Travel Booking system',
    contact: new OA\Contact(email: 'hey@api-travel.com')
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'apiKey',
    in: 'header',
    name: 'Authorization',
    description: 'Enter token in format: Bearer <token>'
)]
#[OA\Tag(name: 'Auth', description: 'Authentication endpoints')]
#[OA\Tag(name: 'Bookings', description: 'Booking management')]
#[OA\Tag(name: 'Passengers', description: 'Passenger management')]
#[OA\Tag(name: 'Tickets', description: 'Ticket management')]
#[OA\Tag(name: 'Payments', description: 'Payment management')]


abstract class Controller
{
    //
}
