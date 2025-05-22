<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Delivery;
use App\Models\Exchange;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TransactionsTable extends Component
{
    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function getTransactionsProperty()
    {
        $today = Carbon::today();

        $deliveries = Delivery::with('user')
            ->whereDate('created_at', $today)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'Delivery',
                    'beneficiary' => $item->beneficiary,
                    'transaction_type' => $item->transaction_type,
                    'currency_name' => $item->currency_name,
                    'amount' => $item->amount,
                    'total' => $item->total,
                    'transaction_code' => $item->transaction_code,
                    'note' => $item->note,
                    'user' => $item->user->name ?? 'N/A',
                    'created_at' => $item->created_at
                ];
            });

        $exchanges = Exchange::with('user')
            ->whereDate('created_at', $today)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'Exchange',
                    'beneficiary' => null,
                    'transaction_type' => $item->transaction_type,
                    'currency_name' => $item->currency_name,
                    'currency_name3' => $item->currency_name3,
                    'amount' => $item->amount,
                    'total' => $item->total,
                    'rate' => $item->rate,
                    'transaction_code' => $item->transaction_code,
                    'note' => $item->note,
                    'user' => $item->user->name ?? 'N/A',
                    'created_at' => $item->created_at
                ];
            });

        // استخدم concat بدل merge لأننا نتعامل مع arrays
        $transactions = $deliveries->concat($exchanges);

        return $transactions
            ->sortByDesc('created_at')
            ->take(15)
            ->values();
    }


    /**
     * يجلب كلّ عمليّات Exchange لليوم الحالي
     */
    public function getTodayExchangesProperty()
    {
        return Exchange::whereDate('created_at', Carbon::today())->get();
    }

    /**
     * يحسب «فرق» (كم بيعنا − كم اشترينا) لكلّ عملة
     */
    public function getDifferencesByCurrencyProperty()
    {
        $exchanges = $this->todayExchanges;

        // جمع جميع رموز العملات
        $currencies = $exchanges
            ->pluck('currency_name')
            ->merge($exchanges->pluck('currency_name3'))
            ->unique();

        $diffs = [];
        foreach ($currencies as $code) {
            $sold  = $exchanges->where('currency_name',  $code)->sum('amount');
            $bought = $exchanges->where('currency_name3', $code)->sum('total');
            $diffs[$code] = $sold - $bought;
        }

        return $diffs;
    }
    public function deleteTransaction($type, $code)
    {
        if ($type === 'Delivery') {
            Delivery::where('transaction_code', $code)->delete();
        } elseif ($type === 'Exchange') {
            Exchange::where('transaction_code', $code)->delete();
        }

        $this->dispatch('refreshTransactions'); // إعادة تحميل الجدول
    }
    public $editData = null;

    public function editTransaction($type, $code)
    {
        if ($type === 'Delivery') {
            $transaction = Delivery::where('transaction_code', $code)->first();
        } elseif ($type === 'Exchange') {
            $transaction = Exchange::where('transaction_code', $code)->first();
        }

        if ($transaction) {
            $this->editData = [
                'type' => $type,
                'id' => $transaction->id,
                'transaction_code' => $transaction->transaction_code,
                'transaction_type' => $transaction->transaction_type,
                'amount' => $transaction->amount,
                'total' => $transaction->total ?? '',
                'note' => $transaction->note,
                'rate' => $transaction->rate ?? '',
            ];
        }
    }

    public function updateTransaction()
    {
        if (! $this->editData) return;

        if ($this->editData['type'] === 'Delivery') {
            $model = Delivery::find($this->editData['id']);
        } elseif ($this->editData['type'] === 'Exchange') {
            $model = Exchange::find($this->editData['id']);
        }

        if ($model) {
            $model->transaction_type = $this->editData['transaction_type'];
            $model->amount = $this->editData['amount'];
            $model->note = $this->editData['note'];

            if ($this->editData['type'] === 'Exchange') {
                $model->total = $this->editData['total'];
                $model->rate = $this->editData['rate'];
            }

            $model->save();
        }

        $this->editData = null;
        $this->dispatch('refreshTransactions');
    }

    public function render()
    {
        return view('livewire.transactions-table', [
            'transactions' => $this->transactions,              // إذا كنت لا تزال تعرض الجدول
            'differences'  => $this->differencesByCurrency,     // فقط الفروق
        ]);
    }
}
