@section('title', "Бронироние №{$booking->id}")

<x-app-layout>
    <!-- component -->
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        <div class="flex justify-start item-start space-y-2 flex-col">
            <h1 class="text-3xl lg:text-4xl font-semibold leading-7 lg:leading-9 text-gray-800">Отель {{ $booking->room->hotel->name }}</h1>
        </div>
        <div class="mt-10 flex flex-col xl:flex-row jusitfy-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
            <x-users.user-card :user="$booking->user"></x-users.user-card>
            <x-bookings.booking-card :booking="$booking"></x-bookings.booking-card>
        </div>
    </div>
</x-app-layout>
