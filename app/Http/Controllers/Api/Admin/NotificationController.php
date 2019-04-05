<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Subscribe;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function getNew() {
        return response()->json([
            'new_subscribes' => Subscribe::newSubscribes()
        ]);
    }
}
