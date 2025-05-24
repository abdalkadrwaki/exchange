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
            'delivery' => 'خرج',
            'Receive' => 'دخل',
            'buy' => 'شراء',
            'sell' => 'بيع',
            'Exchange' => 'صرافة',
            'Delivery' => 'قيد',
        ];
    @endphp

    <!-- Modal التعديل -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">تعديل العملية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>

                <div class="modal-body">
                    @if ($editData)
                        <div class="mb-2">
                            <label>الوصف (النوع):</label>
                            <input type="text" class="form-control" wire:model.defer="editData.transaction_type">
                        </div>

                        <div class="mb-2">
                            <label>المبلغ:</label>
                            <input type="text" class="form-control" wire:model.defer="editData.amount">
                        </div>

                        @if ($editData['type'] === 'Exchange')
                            <div class="mb-2">
                                <label>شراء:</label>
                                <input type="text" class="form-control" wire:model.defer="editData.amount">
                            </div>

                            <div class="mb-2">
                                <label>بيع:</label>
                                <input type="text" class="form-control" wire:model.defer="editData.total">
                            </div>

                            <div class="mb-2">
                                <label>سعر الصرف:</label>
                                <input type="text" class="form-control" wire:model.defer="editData.rate">
                            </div>
                        @endif

                        <div class="mb-2">
                            <label>ملاحظة:</label>
                            <input type="text" class="form-control" wire:model.defer="editData.note">
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-success" wire:click="updateTransaction">حفظ التعديلات</button>
                </div>

            </div>
        </div>
    </div>




    <div style="max-height: 450px; overflow-y: auto;">
        <table id="dataTable" class="table users-table table-bordered mt-3 w-full text-center" style="direction: rtl;">
            <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                <tr class="text-center">
                    <th class="text-center bg-black text-white">الأجراءت</th>

                    <th class="text-center bg-black text-white ">اسم موظف</th>
                    <th class="text-center bg-black text-white ">رقم قيد</th>
                    <th class="text-center bg-black text-white ">الوصف </th>

                    <th class="text-center bg-black text-white ">المبلغ</th>
                      <th class="text-center bg-black text-white ">بيع</th>
                    <th class="text-center bg-black text-white ">شراء</th>


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
        // إذا كانت العملية صرافة وبيع دولار
        $transaction['type'] === 'Exchange' && $transaction['transaction_type'] === 'sell' && $transaction['currency_name3'] === 'USD' => 'table-danger',
        // إذا كانت العملية صرافة وشراء دولار
        $transaction['type'] === 'Exchange' && $transaction['transaction_type'] === 'buy' && $transaction['currency_name'] === 'USD' => 'table-success',
        // الشروط الحالية
        $typeValue === 'صرافة' => 'table-warning',
        $transactionType === 'تسليم' => 'table-danger',
        $transactionType === 'استلام' => 'table-success',
        default => '',
    };
@endphp

                    <tr class="{{ $rowClass }}">

                        <td class="text-center align-middle h-16">
                            <button
                                wire:click="editTransaction('{{ $transaction['type'] }}', '{{ $transaction['transaction_code'] }}')"
                                class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal">
                                تعديل
                            </button>

                            <button
                                wire:click="deleteTransaction('{{ $transaction['type'] }}', '{{ $transaction['transaction_code'] }}')"
                                class="btn btn-sm btn-danger" onclick="return confirm('هل تريد حذف هذه العملية؟')">
                                حذف
                            </button>
                        </td>

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
    <script>
    window.addEventListener('close-edit-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        modal.hide();
    });
</script>
</div>

</div>
