<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
        </h2>
    </x-slot>
    <style>
        /* تلوين الصفوف الفردية */
        .table-responsive tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        /* تلوين الصفوف الزوجية */
        .table-responsive tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }
    </style>


    <div class="container" style="">
        <!-- بطاقة نموذج التحديد -->
        <div class="card mb-4" style="max-width: 1200px; margin: auto; margin-top: 39px;">
            <div class="card-header      text-blue-600">
                <h2 class="mb-1 text-center font-bold text-xl ">استلام/ تسليم</h2>
            </div>
            <div class="card-body">
                <div class="p-4 bg-custom-gray2 shadow-md rounded-md ">
                    <!-- نموذج تحديد النطاق الزمني ونوع العملة -->
                    <form action="{{ route('deliveries.filter') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="from_date">من تاريخ</label>
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    value="{{ request('from_date') ?? date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="to_date">إلى تاريخ</label>
                                <input type="date" id="to_date" name="to_date" class="form-control"
                                    value="{{ request('to_date') ?? date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="currency">العملة</label>
                                <select id="currency" name="currency" class="form-select  scrollable-input right-64 ">
                                  
                                    <option value="all" {{ request('currency') == 'all' ? 'selected' : '' }}>الكل
                                    </option>
                                    <option value="SYP" {{ request('currency') == 'SYP' ? 'selected' : '' }}>سوري
                                    </option>
                                    <option value="USD" {{ request('currency') == 'USD' ? 'selected' : '' }}>دولار
                                        أمريكي
                                    </option>
                                    <option value="TRY" {{ request('currency') == 'TRY' ? 'selected' : '' }}>تركي
                                    </option>
                                    <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>يورو
                                    </option>
                                    <option value="SAR" {{ request('currency') == 'SAR' ? 'selected' : '' }}>ريال
                                        سعودي
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn  btn-primary  w-full text-xl">تنفيذ </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- بطاقة الجدول -->
        <div class="card" style="max-width: 1200px; margin: auto;">
            <div class="card-body">
                @if (!request()->filled('from_date') || !request()->filled('to_date'))
                    <div class="alert alert-info text-center">
                        يرجى تحديد نطاق زمني (من تاريخ وإلى تاريخ) لتظهر البيانات.
                    </div>
                @else
                    @if (!request()->has('currency') || request('currency') == '')
                        <div class="alert alert-info text-center">
                            يرجى اختيار العملة لتظهر البيانات.
                        </div>
                    @else
                        @php
                            $currencies = [
                                'SYP' => 'سوري',
                                'USD' => 'دولار أمريكي',
                                'TRY' => 'تركي',
                                'EUR' => 'يورو',
                                'SAR' => 'ريال سعودي',
                            ];
                            $transaction = [
                                'delivery' => 'تسليم',
                                'Receive' => 'استلام',
                            ];
                        @endphp

                        @if (request('currency') != 'all')
                            @php
                                $totalIstlam = $deliveries->where('transaction_type', 'delivery')->sum('amount');
                                $totalTaslim = $deliveries->where('transaction_type', 'Receive')->sum('amount');
                                $total = $totalIstlam - $totalTaslim;
                            @endphp
                            <div class="table-responsive">
                                <div class="p-4 bg-custom-gray2 shadow-md rounded-md">
                                    <table id="deliveriesTable" class="table table-bordered mt-3"
                                        style="direction: rtl;">
                                        <thead class="text-center bg-black text-white ">
                                            <tr class="">
                                                <th class="text-center bg-black text-white ">الإجراءات</th>
                                                <th class="text-center bg-black text-white ">الموظف</th>
                                                <th class="text-center bg-black text-white ">رقم القيد</th>
                                                <th class="text-center bg-black text-white ">المستفيد</th>
                                                <th class="text-center bg-black text-white ">استلام</th>
                                                <th class="text-center bg-black text-white ">تسليم</th>
                                                <th class="text-center bg-black text-white ">ملاحظة</th>
                                                <th class="text-center bg-black text-white ">تاريخ الإنشاء</th>
                                            </tr>
                                        </thead>z
                                        <tbody>
                                            @forelse($deliveries as $delivery)
                                                <tr class="text-center font-bold align-middle h-16 ">
                                                    <td>
                                                        @if ($delivery->user_id === auth()->id() || auth()->user()->hasRole('admin'))
                                                            <button class="btn btn-sm btn-primary edit-delivery-btn"
                                                                data-id="{{ $delivery->id }}"
                                                                data-beneficiary="{{ $delivery->beneficiary }}"
                                                                data-transaction="{{ $delivery->transaction_type }}"
                                                                data-amount="{{ $delivery->amount }}"
                                                                data-currency="{{ $delivery->currency_name }}"
                                                                data-note="{{ $delivery->note }}">
                                                                تعديل
                                                            </button>

                                                            @if (auth()->user()->hasRole('admin'))
                                                                <form
                                                                    action="{{ route('deliveries.destroy', $delivery->id) }}"
                                                                    method="POST" style="display:inline-block;"
                                                                    onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">
                                                                        حذف
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">غير مصرح</span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $delivery->user->name ?? 'غير معروف' }}</td>
                                                    <td class="text-primary font-bold text-center align-middle h-16">
                                                        {{ $delivery->transaction_code }}</td>
                                                    <td>{{ $delivery->beneficiary }}</td>
                                                    <td class="text-success font-bold">
                                                        @if ($delivery->transaction_type == 'Receive')
                                                            {{ number_format($delivery->amount, 2) }}<br>
                                                            <span
                                                                class="text-dark">{{ $currencies[$delivery->currency_name] ?? $delivery->currency_name }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-danger font-bold">
                                                        @if ($delivery->transaction_type == 'delivery')
                                                            {{ number_format($delivery->amount, 2) }}<br>
                                                            <span
                                                                class="text-dark">{{ $currencies[$delivery->currency_name] ?? $delivery->currency_name }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="font-bold text-center align-middle h-16">
                                                        {{ $delivery->note }}</td>
                                                    <td class="font-bold text-center align-middle h-16">
                                                        {{ $delivery->created_at->format('Y-m-d') }}
                                                    </td>

                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr style="background: #24095e; color: #fff;">
                                                <td colspan="4" class="text-center font-bold bg-black text-white ">
                                                    المجموع</td>
                                                <td class="text-center font-bold bg-black text-white ">
                                                    {{ number_format($totalIstlam, 1) }}
                                                </td>
                                                <td class="text-center font-bold bg-black text-white ">
                                                    {{ number_format($totalTaslim, 1) }}
                                                </td>
                                                <td colspan="2" class="text-center font-bold bg-black text-white ">
                                                    {{ number_format($total, 1) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <div class="p-4 bg-custom-gray2 shadow-md rounded-md">
                                    <table id="deliveriesTable" class="table table-bordered mt-3"
                                        style="direction: rtl;">
                                        <thead class="text-center bg-black text-white ">
                                            <tr class="">
                                                <th class="text-center bg-black text-white ">الإجراءات</th>
                                                <th class="text-center bg-black text-white ">الموظف</th>
                                                <th class="text-center bg-black text-white ">رقم القيد</th>
                                                <th class="text-center bg-black text-white ">المستفيد</th>
                                                <th class="text-center bg-black text-white ">استلام</th>
                                                <th class="text-center bg-black text-white ">تسليم</th>
                                                <th class="text-center bg-black text-white ">ملاحظة</th>
                                                <th class="text-center bg-black text-white ">تاريخ الإنشاء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($deliveries as $delivery)
                                                <tr class="text-center font-bold align-middle h-16 ">
                                                      <td>
                                                        @if ($delivery->user_id === auth()->id() || auth()->user()->hasRole('admin'))
                                                            <button class="btn btn-sm btn-primary edit-delivery-btn"
                                                                data-id="{{ $delivery->id }}"
                                                                data-beneficiary="{{ $delivery->beneficiary }}"
                                                                data-transaction="{{ $delivery->transaction_type }}"
                                                                data-amount="{{ $delivery->amount }}"
                                                                data-currency="{{ $delivery->currency_name }}"
                                                                data-note="{{ $delivery->note }}">
                                                                تعديل
                                                            </button>

                                                            @if (auth()->user()->hasRole('admin'))
                                                                <form
                                                                    action="{{ route('deliveries.destroy', $delivery->id) }}"
                                                                    method="POST" style="display:inline-block;"
                                                                    onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">
                                                                        حذف
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">غير مصرح</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $delivery->user->name ?? 'غير معروف' }}</td>
                                                    <td class="text-primary font-bold text-center align-middle h-16">
                                                        {{ $delivery->transaction_code }}</td>
                                                    <td>{{ $delivery->beneficiary }}</td>
                                                    <td class="text-success font-bold">
                                                        @if ($delivery->transaction_type == 'Receive')
                                                            {{ number_format($delivery->amount, 2) }}<br>
                                                            <span
                                                                class="text-dark">{{ $currencies[$delivery->currency_name] ?? $delivery->currency_name }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-danger font-bold">
                                                        @if ($delivery->transaction_type == 'delivery')
                                                            {{ number_format($delivery->amount, 2) }}<br>
                                                            <span
                                                                class="text-dark">{{ $currencies[$delivery->currency_name] ?? $delivery->currency_name }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="font-bold text-center align-middle h-16">
                                                        {{ $delivery->note }}</td>
                                                    <td class="font-bold text-center align-middle h-16">
                                                        {{ $delivery->created_at->format('Y-m-d') }}
                                                    </td>

                                                </tr>
                                            @empty
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>


        <div class="modal fade" id="editDeliveryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editDeliveryForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-header text-center    font-bold">
                            <h5 class="text-center ">تعديل بيانات </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="delivery_id" name="id">


                            <div class="card bg-custom-gray2 shadow-sm border-0">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="mb-1 text-center font-bold text-xl ">استلام وتسليم </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <label class="form-label">اسم المستفيد</label>
                                            <input type="text" class="form-control" id="delivery_beneficiary"
                                                name="beneficiary">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">نوع المعاملة</label>
                                            <select class="form-select" id="delivery_transaction"
                                                name="transaction_type">
                                                <option value="Receive">استلام</option>
                                                <option value="delivery">تسليم</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">المبلغ</label>
                                            <input type="number" class="form-control" id="delivery_amount"
                                                name="amount" step="0.01">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">العملة</label>
                                            <select class="form-select" id="delivery_currency" name="currency_name">
                                                <option value="">اختر</option>
                                                <option value="SYP">ليرة سورية</option>
                                                <option value="USD">دولار</option>
                                                <option value="SAR">ريال سعودي</option>
                                                <option value="TRY">ليرة تركية</option>
                                                <option value="EUR">يورو</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">ملاحظة</label>
                                            <textarea class="form-control" id="delivery_note" name="note"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>






    </div>

</x-app-layout>
