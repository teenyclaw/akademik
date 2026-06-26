<?php

namespace App\Http\Requests\PaymentRecord;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_bill_id' => 'required|exists:payment_bills,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
