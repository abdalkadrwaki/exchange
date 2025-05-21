<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles


</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
    <!-- Bootstrap 5 CSS -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- تعديل Delivery ---
            document.querySelectorAll('.edit-delivery-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('delivery_id').value = btn.dataset.id;
                    document.getElementById('delivery_beneficiary').value = btn.dataset.beneficiary;
                    document.getElementById('delivery_transaction').value = btn.dataset.transaction;
                    document.getElementById('delivery_amount').value = btn.dataset.amount;
                    document.getElementById('delivery_currency').value = btn.dataset.currency;
                    document.getElementById('delivery_note').value = btn.dataset.note;

                    const modalEl = document.getElementById('editDeliveryModal');
                    if (modalEl) new bootstrap.Modal(modalEl).show();
                });
            });

            document.getElementById('editDeliveryForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = this.querySelector('#delivery_id').value;
                const url = `/deliveries/${id}`;
                fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            beneficiary: this.delivery_beneficiary.value,
                            transaction_type: this.delivery_transaction.value,
                            amount: this.delivery_amount.value,
                            currency_name: this.delivery_currency.value,
                            note: this.delivery_note.value
                        })
                    })
                    .then(res => res.json())
                    .then(data => data.success ? location.reload() : alert('حدث خطأ'))
                    .catch(() => alert('فشل التعديل'));
            });

            // --- تعديل Exchange ---
            document.querySelectorAll('.edit-exchange-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('exchange_id').value = btn.dataset.id;
                    document.getElementById('exchange_amount').value = btn.dataset.amount;
                    document.getElementById('exchange_total').value = btn.dataset.total;
                    document.getElementById('exchange_rate').value = btn.dataset.rate;
                    document.getElementById('exchange_note').value = btn.dataset.note;
                    document.getElementById('exchange_currency_from').value = btn.dataset
                        .currencyFrom;
                    document.getElementById('exchange_currency_to').value = btn.dataset.currencyTo;

                    const modalEl = document.getElementById('editExchangeModal');
                    if (modalEl) new bootstrap.Modal(modalEl).show();
                });
            });

            document.getElementById('editExchangeForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = this.exchange_id.value;
                fetch(`/exchanges/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value
                        },
                        body: new FormData(this)
                    })
                    .then(res => {
                        if (!res.ok) throw res;
                        return res.json();
                    })
                    .then(() => location.reload())
                    .catch(async err => {
                        let msg = 'فشل الحفظ';
                        try {
                            const j = await err.json();
                            if (j.message) msg = j.message;
                        } catch {}
                        alert(msg);
                    });
            });
        });
    </script>

</body>

</html>
