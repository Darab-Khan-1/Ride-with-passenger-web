<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
abstract class FormValidateRequest {
    abstract public function validate(Request $request, $rules, $messages);
}