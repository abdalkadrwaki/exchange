<div>
    @php
        $currencies = [
            'SYP' => 'سوري',
            'USD' => 'دولار',
            'TRY' => 'تركي',
            'EUR' => 'يورو',
            'SAR' => 'ريال',
        ];
    @endphp
    <div class="flex justify-center items-center ">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($stats as $code => $data)
                @php
                    $name = $currencies[$code] ?? $code;
                @endphp

                <div class="rounded-lg shadow overflow-hidden bg-white">
                    <!-- اسم العملة -->
                    <div class="bg-blue-900 text-white text-center py-3 text-xl font-bold">
                        {{ $name }}
                    </div>

                    <!-- الفرق -->
                    <div class="bg-gray-200 text-center py-2 text-xl font-bold text-gray-900 border-b">
                        فرق: {{ number_format($data['difference'], 0) }}
                        @if ($data['difference'] < 0)
                            <span class="text-red-600">−</span>
                        @elseif ($data['difference'] > 0)
                            <span class="text-green-600">+</span>
                        @endif
                    </div>

                    <!-- سعر الصرف الوسطي للشراء -->
                    <div class="text-center py-3">
                        <div class="border border-gray-900 py-2 text-xl font-bold">
                              وسطي للشراء:
                            <span class="text-blue-700 font-bold">
                                {{ $data['average_buy_rate'] ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <!-- سعر الصرف الوسطي للبيع -->
                    <div class="text-center pb-3">
                        <div class="border border-gray-900 py-2 text-xl font-bold">
                              وسطي للبيع
                            <span class="text-green-700 font-bold">
                                {{ $data['average_sell_rate'] ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


</div>
