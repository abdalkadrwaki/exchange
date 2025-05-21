<!-- resources/views/livewire/transactions-table.blade.php -->

<div>

    @php
        $currencies = [
            'SYP' => 'سوري',
            'USD' => 'دولار ',
            'TRY' => 'تركي',
            'EUR' => 'يورو',
            'SAR' => 'ريال ',
        ];
        $tran = [
            'delivery' => 'تسليم',
            'Receive' => 'استلام',
            'buy' => 'شراء',
            'sell' => 'بيع',
            'Exchange' => 'صرافة',
            'Delivery' => 'قيد',
        ];
    @endphp






    <div style="max-height: 450px; overflow-y: auto;">
        <table id="dataTable" class="table users-table table-bordered mt-3 w-full text-center" style="direction: rtl;">
            <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                <tr class="text-center">
                    <th class="text-center bg-black text-white ">اسم موظف</th>
                    <th class="text-center bg-black text-white ">رقم قيد</th>
                    <th class="text-center bg-black text-white ">الوصف </th>

                    <th class="text-center bg-black text-white ">المبلغ</th>
                    <th class="text-center bg-black text-white ">شراء</th>
                    <th class="text-center bg-black text-white ">بيع</th>

                    <th class="text-center bg-black text-white ">ملاحظة</th>

                    <th class="text-center bg-black text-white ">تاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    @php
                        $typeValue = $tran[$transaction['type']] ?? $transaction['type'];
                        $transactionType = $tran[$transaction['transaction_type']] ?? $transaction['transaction_type'];

                        $rowClass = match (true) {
                            $typeValue === 'صرافة' => 'table-warning',
                            $transactionType === 'تسليم' => 'table-danger',
                            $transactionType === 'استلام' => 'table-success',
                            default => '',
                        };
                    @endphp

                    <tr class="{{ $rowClass }}">
                        <td class="font-bold text-center align-middle h-16">{{ $transaction['user'] }}</td>
                        <td class="font-bold text-center align-middle h-16" style="color: blue ">
                            {{ $transaction['transaction_code'] }}</td>
                        <td class="font-bold text-center align-middle h-16">
                            @if ($transaction['type'] === 'Delivery')
                                {{ $transaction['beneficiary'] }} - ({{ $transactionType }})
                            @else
                                {{ $transactionType }} - ({{ $transaction['rate'] }})
                            @endif
                        </td>



                        {{-- المبلغ --}}
                        <td class="font-bold text-center align-middle h-16">
                            @if ($transaction['type'] === 'Delivery')
                                {{ $transaction['amount'] }}<br>
                                {{ $currencies[$transaction['currency_name']] ?? $transaction['currency_name'] }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- شراء --}}
                        <td class="font-bold text-center align-middle h-16">
                            @if ($transaction['type'] === 'Exchange')
                                {{ $transaction['amount'] }}<br>
                                {{ $currencies[$transaction['currency_name']] ?? $transaction['currency_name'] }}
                            @else
                                -
                            @endif
                        </td>

                        {{-- بيع --}}
                        <td class="font-bold text-center align-middle h-16">
                            @if ($transaction['type'] === 'Exchange')
                                {{ $transaction['total'] }}<br>
                                {{ $currencies[$transaction['currency_name3']] ?? $transaction['currency_name3'] }}
                            @else
                                -
                            @endif
                        </td>


                        <td class="font-bold text-center align-middle h-16">{{ $transaction['note'] }}</td>
                        <td class="font-bold text-center align-middle h-16">{{ $transaction['created_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
