<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Exchange;
use Carbon\Carbon;

class ExchangeDifferenceBoxes extends Component
{
    public function getCurrencyStatsProperty()
    {
        $exchanges = Exchange::whereDate('created_at', Carbon::today())->get();

        $currencies = $exchanges
            ->pluck('currency_name')
            ->merge($exchanges->pluck('currency_name3'))
            ->unique();

        $stats = [];

        foreach ($currencies as $code) {
            $sold = $exchanges->where('currency_name', $code);
            $bought = $exchanges->where('currency_name3', $code);

            $soldAmount = $sold->sum('amount');
            $soldTotal = $sold->sum('total');

            $boughtAmount = $bought->sum('amount');
            $boughtTotal = $bought->sum('total');

            $averageBuyRate = $boughtAmount > 0 ? round($boughtTotal / $boughtAmount) : null;
            $averageSellRate = $soldTotal > 0 ? round($soldAmount / $soldTotal) : null;

            $stats[$code] = [
                'difference' => $soldAmount - $boughtTotal,
                'average_buy_rate' => $averageBuyRate,
                'average_sell_rate' => $averageSellRate,
            ];
        }

        return $stats;
    }

    public function render()
    {
        return view('livewire.exchange-difference-boxes', [
            'stats' => $this->currencyStats,
        ]);
    }
}
