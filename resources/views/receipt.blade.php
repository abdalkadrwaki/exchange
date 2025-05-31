<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>إيصال العملية</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;



        }

        .receipt {
            width: 80mm;
        }

        .header,
        .footer {
            text-align: center;
        }

        .content {
            margin-top: 10px;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen" onload="printAndRedirect();">


    <div class="receipt ">
        <div class="header flex flex-col items-center space-y-4 py-6 bg-white shadow-md rounded-lg">
            <!-- الشعار -->


        </div>


        <div class="content justify-center">
            @php
                $currencies = [
                    'SYP' => ' ليرة سورية',
                    'USD' => 'الدولار ',
                    'TRY' => ' التركية',
                    'EUR' => 'اليورو',
                    'SAR' => 'الريال ',
                ];
                $transactionTypes = [
                    'delivery' => 'تسليم',
                    'Receive' => 'استلام',
                    'exchange' => 'صرافة',
                ];
                $now = \Carbon\Carbon::now()->locale('ar')->translatedFormat('Y-m-d H:i');
            @endphp

            {{-- عملية صرافة --}}
            @if (session('exchange'))

                <div class="header flex flex-col items-center space-y-4 py-6 bg-white shadow-md rounded-lg"
                    style="width: 80mm; direction: rtl; font-family: Tahoma, sans-serif; text-align: center; margin: 0 auto; padding: 5px; border: 3px dashed #000;">
                                <img src="{{ asset('images/image-removebg-preview (2).png') }}" alt="Logo" style="width: 300px; height: 200px; "/>

                    <h3
                        style="margin: 0; font-size: 22px; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 5px;">
                        إيصال عملية صرافة
                    </h3>

                    <p style="font-size: 16px; margin: 10px 0;">
                        <strong>رقم الإيصال:</strong> {{ session('exchange')['transaction_code'] }}
                    </p>

                    <p style="font-size: 16px; margin: 10px 0;">
                        <strong>نوع العملية:</strong> بيع -
                        {{ $currencies[session('exchange')['currency_name3']] ?? session('exchange')['currency_name3'] }}
                    </p>

                    <table
                        style="border: 1px solid #000; border-collapse: collapse; width: 100%; font-size: 16px; margin-top: 10px;">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th style="border: 1px solid #000; padding: 5px;">من العملة</th>
                                <th style="border: 1px solid #000; padding: 5px;">المبلغ</th>
                                <th style="border: 1px solid #000; padding: 5px;">سعر الصرف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #000; padding: 5px;">
                                    {{ $currencies[session('exchange')['currency_name']] ?? $data['currency_name'] }}
                                </td>
                                <td style="border: 1px solid #000; padding: 5px;">
                                    {{ number_format(session('exchange')['amount'], 2) }}
                                </td>
                                <td style="border: 1px solid #000; padding: 5px;">
                                    {{ number_format(session('exchange')['rate'], 2) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" style="border: 1px solid #000; padding: 8px; font-weight: bold;">
                                    الإجمالي:</td>
                                <td colspan="2" style="border: 1px solid #000; padding: 8px; font-weight: bold;">
                                    {{ number_format(session('exchange')['total'], 2) }}
                                    {{ $currencies[session('exchange')['currency_name3']] ?? session('exchange')['currency_name3'] }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>


                    @if (!empty(session('exchange')['note']))
                        <p style="font-size: 14px; margin-top: 10px; text-align: right;">
                            <strong>ملاحظات:</strong> {{ session('exchange')['note'] }}
                        </p>
                    @endif

                    <p style="font-size: 14px; margin-top: 15px;">
                        <strong>تاريخ العملية:</strong> {{ $now }}
                    </p>
                </div>
            @elseif (session('delivery'))
                <div
                    style="width: 80mm; direction: rtl; font-family: Tahoma, sans-serif; text-align: center; margin: 0 auto; padding: 5px; border: 3px dashed #000;">

                    <p style="font-size: 16px; margin: 5px 0;">إيصال رقم: {{ session('delivery')['transaction_code'] }}
                    </p>
                    <p style="font-size: 16px; margin: 5px 0;">نوع العملية:
                        {{ $transactionTypes[session('delivery')['transaction_type']] ?? session('delivery')['transaction_type'] }}
                    </p>

                    <table style="border: 1px solid black; border-collapse: collapse; width: 100%; font-size: 18px;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid black; text-align: center;">المستفيد</th>
                                <th style="border: 1px solid black; text-align: center;">المبلغ</th>
                                <th style="border: 1px solid black; text-align: center;">العملة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black; text-align: center;">
                                    {{ session('delivery')['beneficiary'] }}</td>
                                <td style="border: 1px solid black; text-align: center;">
                                    {{ session('delivery')['amount'] }}</td>
                                <td style="border: 1px solid black; text-align: center;">
                                    {{ $currencies[session('delivery')['currency_name']] ?? session('delivery')['currency_name'] }}
                                </td>

                            </tr>

                        </tbody>
                        <tfoot>
                            <tr style="border: 1px solid black; text-align: center;">
                                <td colspan="4" class="text-center font-bold bg-black text-white ">
                                    {{ session('delivery')['note'] }}
                                </td>

                            </tr>
                        </tfoot>
                    </table>

                    <p style="font-size: 14px; margin-top: 10px;">تاريخ العملية: {{ $now }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
<script>
    function printAndRedirect() {
        window.print();

        // بعد انتهاء الطباعة، انتظر ثانيتين ثم انتقل للوحة التحكم
        window.onafterprint = function() {
            setTimeout(function() {
                window.location.href = "{{ route('dashboard') }}";
            }, 500); // يمكنك تعديل التأخير إذا أردت
        };
    }
</script>

</html>
