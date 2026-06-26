<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRecord\StorePaymentRecordRequest;
use App\Http\Requests\PaymentRecord\UpdatePaymentRecordRequest;
use App\Models\PaymentBill;
use App\Models\PaymentRecord;
use App\Services\PaymentRecordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentRecordController extends Controller
{
    public function __construct(protected PaymentRecordService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('payment-records.index', compact('items'));
    }

    public function create(): View
    {
        return view('payment-records.create', $this->formData(new PaymentRecord));
    }

    public function store(StorePaymentRecordRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('payment-records.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function edit(int $id): View
    {
        return view('payment-records.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdatePaymentRecordRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('payment-records.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('payment-records.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    private function formData(PaymentRecord $item): array
    {
        return [
            'item' => $item,
            'bills' => PaymentBill::query()->with('student')->orderByDesc('id')->get(),
        ];
    }
}
