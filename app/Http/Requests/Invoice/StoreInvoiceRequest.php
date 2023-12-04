<?php

namespace App\Http\Requests\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'total' => ['required', 'numeric', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:1000000000'],
            'status' => ['required', Rule::in(array_values(config('app.invoice_status')))],
        ];
    }
}

