<?php

namespace App\Services;

use App\Models\PaymentBill;
use App\Repositories\Contracts\PaymentRecordRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentRecordService
{
    public function __construct(protected PaymentRecordRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with(['paymentBill.student'])->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $data['recorded_by'] = auth()->id();
            $record = $this->repository->create($data);
            $this->refreshBillStatus($record->payment_bill_id);

            return $record;
        });
    }

    public function update(int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $record = $this->repository->update($id, $data);
            $this->refreshBillStatus($record->payment_bill_id);

            return $record;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $record = $this->findOrFail($id);
            $billId = $record->payment_bill_id;
            $deleted = $this->repository->delete($id);
            $this->refreshBillStatus($billId);

            return $deleted;
        });
    }

    private function refreshBillStatus(int $billId): void
    {
        $bill = PaymentBill::query()->findOrFail($billId);
        $paid = $bill->paymentRecords()->sum('amount');
        $status = $paid >= $bill->amount ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');
        $bill->update(['status' => $status]);
    }
}
