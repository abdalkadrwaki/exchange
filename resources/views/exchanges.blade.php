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
    @php
        $today = date('Y-m-d');
        $fromDate = request('from_date', $today);
        $toDate = request('to_date', $today);
    @endphp


    <div class="container" style="">
        <!-- بطاقة نموذج التحديد -->
        <!-- بطاقة الفلترة -->
        <div class="card mb-4" style="max-width: 1200px; margin: auto; margin-top: 39px;">
            <div class="card-header text-blue-600">
                <h2 class="mb-1 text-center font-bold text-xl">استلام/ تسليم</h2>
            </div>
            <div class="card-body">
                <div class="p-4 bg-custom-gray2 shadow-md rounded-md">
                    <form action="{{ route('Exchanges.filter') }}" method="GET">
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

                                    <option value="all" {{ request('currency') == 'all' ? 'selected' : '' }}>الكل
                                    </option>
                                    <option value="SYP" {{ request('currency') == 'SYP' ? 'selected' : '' }}>سوري
                                    </option>
                                    <option value="USD" {{ request('currency') == 'USD' ? 'selected' : '' }}>دولار
                                    </option>
                                    <option value="TRY" {{ request('currency') == 'TRY' ? 'selected' : '' }}>تركي
                                    </option>
                                    <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>يورو
                                    </option>
                                    <option value="SAR" {{ request('currency') == 'SAR' ? 'selected' : '' }}>ريال
                                        سعودي</option>
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
        

        <div class="modal fade" id="editExchangeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- modal-lg for better spacing -->
                <div class="modal-content">
                    <form id="editExchangeForm">
                        @csrf
                        @method('PUT')

                        <div class="modal-header   font-bold">
                            <h5 class="text-center ">تعديل بيانات </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="exchange_id" name="id">

                            <div class="card bg-custom-gray2 shadow-sm border-0">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="mb-1 text-center font-bold text-xl ">صرافة </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3"> <!-- Vertical spacing between rows -->

                                        <div class="col-md-6">
                                            <label class="form-label">بيع (المبلغ)</label>
                                            <input type="number" step="0.01" id="exchange_amount" name="amount"
                                                class="font-bold form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">عملة البيع</label>
                                            <select id="exchange_currency_from" name="currency_name"
                                                class="font-bold form-select">
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
                                            <select id="exchange_currency_to" name="currency_name3"
                                                class=" font-bold form-select">
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
