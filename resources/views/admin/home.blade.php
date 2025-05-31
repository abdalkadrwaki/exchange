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
</x-app-layout>
