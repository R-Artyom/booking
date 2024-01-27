<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row shadow-md bg-gray-50']) }}>
    <div class="h-full w-full md:w-1/3">
        <div class="h-64 w-full bg-cover bg-center bg-no-repeat" style="background-image: url({{ $room->poster_url }})">
        </div>
    </div>
    <div class="p-4 w-full md:w-2/3 flex flex-col justify-between">
        <div class="pb-2">
            <div class="text-xl font-bold">
                {{ $room->name }}
            </div>
            <div>
               <span>•</span> {{ $room->floor_area }} м
            </div>
            <div>
                @foreach($room->facilities as $facility)
                    <span>• {{ $facility->name }} </span>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="flex justify-end pt-2">
            <div class="flex flex-col">
                <span class="text-lg font-bold">{{ $room->total_price }} руб.</span>
                <span>за {{ $room->total_days }} {{ getPhraseNight($room->total_days) }}</span>
            </div>
            @if(auth()->check())
                <form class="ml-4" method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <input type="hidden" name="started_at" min="{{ date('Y-m-d') }}" value="{{ request()->get('start_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                    <input type="hidden" name="finished_at" min="{{ date('Y-m-d') }}" value="{{ request()->get('end_date', \Carbon\Carbon::now()->addDay()->format('Y-m-d')) }}">
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <x-the-button class=" h-full w-full">{{ __('Book') }}</x-the-button>
                </form>
            @else
                <div class="ml-4 flex">
                    <x-link-button href="{{ route('login') }}">{{ __('Book') }}</x-link-button>
                </div>
            @endif
        </div>
    </div>
</div>
