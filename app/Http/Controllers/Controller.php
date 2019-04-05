<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $validate_rules,
              $data,
              $validate_messages,
              $validate_attributes;

    public function __construct()
    {
        $this->validate_rules = $this->validate_messages = $this->validate_attributes = [];
        $this->data = [];
    }
}
