<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Content Management API',
    version: '1.0.0',
    description: 'REST API for Content Management System'
)]
class Controller
{
    //
}