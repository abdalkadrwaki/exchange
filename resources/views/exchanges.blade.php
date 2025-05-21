<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
        </h2>
    </x-slot>
    <style>
        .table-responsive tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table-responsive tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }
    </style>
    @php
        $today = date('Y-m-d');
        $fromDate = request('from_date', $today);
        $toDate = request('to_date', $today);
    @endphp

    <div class="container" style="">
        <!-- بطاقة الفلترة -->
        <div class="card mb-4" style="max-width: 1200px; margin: auto; margin-top: 39px;">
            <div class="card-header text-blue-600">
                <h2 class="mb-1 text-center font-bold text-xl">استلام/ تسليم</h2>
            </div>
            <div class="card-body">
                <div class="p-4 bg-custom-gray2 shadow-md rounded-md">
                    <form action="{{ route('exchanges.filter') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="from_date">من تاريخ</label>
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="to_date">إلى تاريخ</label>
                                <input type="date" id="to_date" name="to_date" class="form-control"
                                    value="{{ $toDate }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="currency">العملة</label>
                                <select id="currency" name="currency" class="form-select scrollable-input right-64">
                                    <option value="all" {{ request('currency') == 'all' ? 'selected' : '' }}>الكل</option>
                                    <option value="SYP" {{ request('currency') == 'SYP' ? 'selected' : '' }}>سوري</option>
                                    <option value="USD" {{ request('currency') == 'USD' ? 'selected' : '' }}>دولار</option>
                                    <option value="TRY" {{ request('currency') == 'TRY' ? 'selected' : '' }}>تركي</option>
                                    <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>يورو</option>
                                    <option value="SAR" {{ request('currency') == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary w-full text-xl">تنفيذ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- بطاقة الجدول -->
        <div class="card" style="max-width: 1200px; margin: auto;">
            <div class="card-body">
                @if (!request()->has('currency') || request('currency') == '')
                    <div class="alert alert-info text-center">
                        يرجى اختيار العملة لتظهر البيانات.
                    </div>
                @else
                    @php
                        $currencies = [
                            'SYP' => 'سوري',
                            'USD' => 'دولار ',
                            'TRY' => 'تركي',
                            'EUR' => 'يورو',
                            'SAR' => 'ريال سعودي',
                        ];
                        $transaction = ['buy' => 'شراء', 'sell' => 'بيع'];
                    @endphp

                    @if (request('currency') != 'all')
                        <div class="table-responsive mt-5">
                            <div class="p-4 bg-custom-gray2 shadow-md rounded-md">
                                <table id="exchangesTable" class="table table-bordered mt-3" style="direction: rtl;">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center align-middle h-16 bg-black text-white ">الإجراءات</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">الموظف</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">رقم القيد</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">العملية</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">بيع</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">شراء</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">سعر الصرف</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">ملاحظة</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($exchanges as $exchange)
                                            <tr class="text-center">
                                                <td>
                                                    @if ($exchange->user_id === auth()->id() || auth()->user()->hasRole('admin'))
                                                        <button class="btn btn-sm btn-primary edit-exchange-btn"
                                                            data-id="{{ $exchange->id }}"
                                                            data-amount="{{ $exchange->amount }}"
                                                            data-total="{{ $exchange->total }}"
                                                            data-rate="{{ $exchange->rate }}"
                                                            data-note="{{ $exchange->note }}"
                                                            data-currency-from="{{ $exchange->currency_name }}"
                                                            data-currency-to="{{ $exchange->currency_name3 }}">
                                                            تعديل
                                                        </button>

                                                        @if (auth()->user()->hasRole('admin'))
                                                            <button class="btn btn-sm btn-danger delete-exchange-btn"
                                                                data-id="{{ $exchange->id }}">
                                                                حذف
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">غير مصرح</span>
                                                    @endif
                                                </td>

                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->user->name ?? 'غير معروف' }}</td>
                                                <td class="text-primary font-bold text-center align-middle h-16">
                                                    {{ $exchange->transaction_code }}</td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $transaction[$exchange->transaction_type] ?? $exchange->transaction_type }}
                                                </td>
                                                <td class="text-danger font-bold text-center align-middle h-16">
                                                    {{ number_format($exchange->amount, 2) }}<br>
                                                    <span class="text-dark">{{ $currencies[$exchange->currency_name] ?? $exchange->currency_name }}</span>
                                                </td>
                                                <td class="text-success font-bold text-center align-middle h-16">
                                                    {{ number_format($exchange->total, 2) }}<br>
                                                    <span class="text-dark">{{ $currencies[$exchange->currency_name3] ?? $exchange->currency_name3 }}</span>
                                                </td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->rate }}</td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->note }}</td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive mt-5">
                            <div class="p-4 bg-custom-gray2 shadow-md rounded-md">
                                <table id="exchangesTable" class="table table-bordered mt-3" style="direction: rtl;">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center align-middle h-16 bg-black text-white ">الإجراءات</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">الموظف</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">رقم القيد</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">العملية</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">بيع</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">شراء</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">سعر الصرف</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">ملاحظة</th>
                                            <th class="text-center align-middle h-16 bg-black text-white ">تاريخ الإنشاء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($exchanges as $exchange)
                                            <tr class="text-center">
                                                <td>
                                                    @if ($exchange->user_id === auth()->id() || auth()->user()->hasRole('admin'))
                                                        <button class="btn btn-sm btn-primary edit-exchange-btn"
                                                            data-id="{{ $exchange->id }}"
                                                            data-amount="{{ $exchange->amount }}"
                                                            data-total="{{ $exchange->total }}"
                                                            data-rate="{{ $exchange->rate }}"
                                                            data-note="{{ $exchange->note }}"
                                                            data-currency-from="{{ $exchange->currency_name }}"
                                                            data-currency-to="{{ $exchange->currency_name3 }}">
                                                            تعديل
                                                        </button>

                                                        @if (auth()->user()->hasRole('admin'))
                                                            <button class="btn btn-sm btn-danger delete-exchange-btn"
                                                                data-id="{{ $exchange->id }}">
                                                                حذف
                                                            </button>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">غير مصرح</span>
                                                    @endif
                                                </td>

                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->user->name ?? 'غير معروف' }}</td>
                                                <td class="text-primary font-bold text-center align-middle h-16">
                                                    {{ $exchange->transaction_code }}</td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $transaction[$exchange->transaction_type] ?? $exchange->transaction_type }}
                                                </td>
                                                <td class="text-danger font-bold text-center align-middle h-16">
                                                    {{ number_format($exchange->amount, 2) }}<br>
                                                    <span class="text-dark">{{ $currencies[$exchange->currency_name] ?? $exchange->currency_name }}</span>
                                                </td>
                                                <td class="text-success font-bold text-center align-middle h-16">
                                                    {{ number_format($exchange->total, 2) }}<br>
                                                    <span class="text-dark">{{ $currencies[$exchange->currency_name3] ?? $exchange->currency_name3 }}</span>
                                                </td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->rate }}</td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->note }}</td>
                                                <td class="font-bold text-center align-middle h-16">
                                                    {{ $exchange->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editExchangeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editExchangeForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-header font-bold">
                            <h5 class="text-center">تعديل بيانات </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="exchange_id" name="id">
                            <div class="card bg-custom-gray2 shadow-sm border-0">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="mb-1 text-center font-bold text-xl">صرافة </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <label class="form-label">بيع (المبلغ)</label>
                                            <input type="number" step="0.01" id="exchange_amount" name="amount"
                                                class="font-bold form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">عملة البيع</label>
                                            <select id="exchange_currency_from" name="currency_name" class="font-bold form-select">
                                                @foreach ($currencies as $code => $name)
                                                    <option value="{{ $code }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">شراء (المبلغ)</label>
                                            <input type="number" step="0.01" id="exchange_total" name="total"
                                                class="font-bold form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">عملة الشراء</label>
                                            <select id="exchange_currency_to" name="currency_name3" class=" font-bold form-select">
                                                @foreach ($currencies as $code => $name)
                                                    <option value="{{ $code }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">سعر الصرف</label>
                                            <input type="number" step="0.0001" id="exchange_rate" name="rate"
                                                class="font-bold form-control">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">ملاحظة</label>
                                            <input type="text" id="exchange_note" name="note"
                                                class="font-bold form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">حفظ</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
