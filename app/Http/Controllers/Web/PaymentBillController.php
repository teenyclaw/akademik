<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentBill\StorePaymentBillRequest;
use App\Http\Requests\PaymentBill\UpdatePaymentBillRequest;
use App\Models\FeeType;
use App\Models\PaymentBill;
use App\Models\Student;
use App\Services\PaymentBillService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentBillController extends Controller
{
    public function __construct(protected PaymentBillService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('payment-bills.index', compact('items'));
    }

    public function create(): View
    {
        return view('payment-bills.create', $this->formData(new PaymentBill));
    }

    public function store(StorePaymentBillRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('payment-bills.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('payment-bills.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdatePaymentBillRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('payment-bills.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('payment-bills.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    private function formData(PaymentBill $item): array
    {
        return [
            'item' => $item,
            'students' => Student::query()->orderBy('name')->get(),
            'feeTypes' => FeeType::query()->orderBy('name')->get(),
        ];
    }
}
