<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            لوحة تحكم الادمن
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="container mx-auto px-4 mt-8 ">
            <livewire:exchange-difference-boxes />

            <div class="max-w-12x1 mx-auto bg-white shadow rounded-md p-4 mt-3 ">


                @livewire('transactions-table')
            </div>

        </div>
    </div>

     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container-custom">
                @if (session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif


                <form id="transaction-form" action="{{ route('transactions.store') }}" method="POST"
                    style=" direction: rtl;">
                    @csrf
                    <div class="row">
                        <!-- بطاقة "صرافة" -->

                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="mb-1 text-center font-bold text-xl ">صرافة </h2>
                                </div>
                                <div class="card-body">
                                    <div class="p-4 bg-custom-gray2 shadow-md rounded-md  ">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">من العملة</label>
                                                <select name="currency_name" id="currency_name"
                                                    class="font-bold form-select scrollable-input">
                                                    <option value="">اختر</option>
                                                    <option value="USD">دولار</option>
                                                    <option value="SYP">ليرة سورية</option>
                                                    <option value="SAR">ريال سعودي</option>
                                                    <option value="TRY">ليرة تركية</option>
                                                    <option value="EUR">يورو</option>
                                                </select>
                                            </div>



                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">شراء مبلغ</label>
                                                <input type="" name="total" id="total"
                                                    class="font-bold  form-control number-only scrollable-input2"
                                                    placeholder="">
                                            </div>
<div class="col-md-6 mb-3">
                                                <label class="form-label">بيع مبلغ</label>
                                                <input type="text" name="amount" id="amount"
                                                    class="font-bold form-control number-only scrollable-input2">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">إلى العملة</label>
                                                <select name="currency_name3" id="currency_name3"
                                                    class="font-bold form-select scrollable-input">
                                                    <option value="">اختر</option>
                                                    <option value="USD">دولار</option>
                                                    <option value="SYP">ليرة سورية</option>
                                                    <option value="SAR">ريال سعودي</option>
                                                    <option value="TRY">ليرة تركية</option>
                                                    <option value="EUR">يورو</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">سعر الصرف:</label>
                                                <input type="text" name="rate" id="rate"
                                                    class="font-bold form-control number-only scrollable-input2"
                                                    value="">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">ملاحظة</label>
                                                <input type="text" name="note" id="note"
                                                    class="font-bold form-control number-only scrollable-input2"
                                                    placeholder="">
                                            </div>

                                            <button class="btn py-2 mt-2 font-bold text-white" type="submit"
                                                onclick="printReceipt()" style="background: rgb(56, 16, 135)">
                                                حفظ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const elements = {
                                    amount: document.getElementById('amount'),
                                    rate: document.getElementById('rate'),
                                    total: document.getElementById('total'),
                                    fromCurrency: document.getElementById('currency_name'),
                                    toCurrency: document.getElementById('currency_name3')
                                };

                                const currencyHierarchy = ['EUR', 'USD', 'SAR', 'TRY', 'SYP'];
                                let isUpdating = false; // لمنع التحديث الدائري

                                function getConversionDirection(from, to) {
                                    const fromStrength = currencyHierarchy.indexOf(from);
                                    const toStrength = currencyHierarchy.indexOf(to);

                                    if (fromStrength === -1 || toStrength === -1) return 'multiply';
                                    return fromStrength < toStrength ? 'multiply' : 'divide';
                                }

                                function updateTotalFromAmount() {
                                    if (isUpdating) return;
                                    isUpdating = true;

                                    const amount = parseFloat(elements.amount.value) || 0;
                                    const rate = parseFloat(elements.rate.value) || 0;
                                    const from = elements.fromCurrency.value;
                                    const to = elements.toCurrency.value;

                                    if (!amount || !rate || from === '' || to === '' || from === to) {
                                        elements.total.value = '';
                                        isUpdating = false;
                                        return;
                                    }

                                    const direction = getConversionDirection(from, to);
                                    const total = direction === 'multiply' ? amount * rate : amount / rate;

                                    elements.total.value = total.toFixed(2);
                                    isUpdating = false;
                                }

                                function updateAmountFromTotal() {
                                    if (isUpdating) return;
                                    isUpdating = true;

                                    const total = parseFloat(elements.total.value) || 0;
                                    const rate = parseFloat(elements.rate.value) || 0;
                                    const from = elements.fromCurrency.value;
                                    const to = elements.toCurrency.value;

                                    if (!total || !rate || from === '' || to === '' || from === to) {
                                        elements.amount.value = '';
                                        isUpdating = false;
                                        return;
                                    }

                                    const direction = getConversionDirection(from, to);
                                    const amount = direction === 'multiply' ? total / rate : total * rate;

                                    elements.amount.value = amount.toFixed(2);
                                    isUpdating = false;
                                }

                                ['input', 'change'].forEach(event => {
                                    elements.amount.addEventListener(event, updateTotalFromAmount);
                                    elements.rate.addEventListener(event, () => {
                                        updateTotalFromAmount();
                                        updateAmountFromTotal();
                                    });
                                    elements.fromCurrency.addEventListener(event, () => {
                                        updateTotalFromAmount();
                                        updateAmountFromTotal();
                                    });
                                    elements.toCurrency.addEventListener(event, () => {
                                        updateTotalFromAmount();
                                        updateAmountFromTotal();
                                    });
                                    elements.total.addEventListener(event, updateAmountFromTotal);
                                });

                                // أول حساب عند تحميل الصفحة
                                updateTotalFromAmount();
                            });
                        </script>


                        <!-- بطاقة "استلام/تسليم" -->
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="mb-1 text-center font-bold text-xl ">استلام/ تسليم</h2>
                                </div>
                                <div class="card-body">
                                    <div class="p-4 bg-custom-gray2 shadow-md rounded-md ">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">المستفيد:</label>
                                                <input type="text" name="beneficiary" id="name"
                                                    class="form-control" placeholder="اسم المستفيد">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">نوع العملية:</label>
                                                <select name="transaction_type2" id="transaction_type2"
                                                    class="form-select scrollable-input">
                                                    <option value="">اختر</option>
                                                    <option value="Receive">دخل</option>
                                                    <option value="delivery">خرج</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">العملة:</label>
                                                <select name="currency_name2" id="currency_name2"
                                                    class="form-select scrollable-input">
                                                    <option value="">اختر</option>
                                                    <option value="SYP">ليرة سورية</option>
                                                    <option value="USD">دولار</option>
                                                    <option value="SAR">ريال سعودي</option>
                                                    <option value="TRY">ليرة تركية</option>
                                                    <option value="EUR">يورو</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">المبلغ:</label>
                                                <input type="text" name="amount2" id="amount2"
                                                    class="form-control number-only" placeholder="أدخل المبلغ">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">ملاحظة </label>
                                                <input type="text" name="note" id="note"
                                                    class="form-control number-only scrollable-input2"
                                                    placeholder=" ">
                                            </div>

                                            <button class="btn  py-2 font-bold  text-white  " type="submit"
                                                onclick="printReceipt2()"
                                                style="margin-top: 10px ; background: rgb(56, 16, 135)">حفظ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
    @if (session('redirect_to_receipt'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    Swal.fire({
                        title: 'هل تريد طباعة الإيصال؟',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'نعم',
                        cancelButtonText: 'لا',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('receipt') }}";
                        } else {
                            // إذا لم يرغب المستخدم بالطباعة، يمكن إعادة تحميل الصفحة
                            location.reload();
                        }
                    });
                }, 1000);
            });
        </script>
    @endif


</x-app-layout>
