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
        





    </div>

</x-app-layout>
