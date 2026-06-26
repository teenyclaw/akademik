<?php

namespace App\Http\Requests\PaymentBill;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'period' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:20',
        ];
    }
}
