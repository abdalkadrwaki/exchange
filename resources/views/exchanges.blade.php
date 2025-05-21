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


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const today = new Date().toISOString().split('T')[0];
                if (!document.getElementById('from_date').value) {
                    document.getElementById('from_date').value = today;
                }
                if (!document.getElementById('to_date').value) {
                    document.getElementById('to_date').value = today;
                }
            });
        </script>

    

</x-app-layout>
