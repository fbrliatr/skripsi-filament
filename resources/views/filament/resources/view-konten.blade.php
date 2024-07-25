<!-- resources/views/filament/resources/view-konten.blade.php -->
@extends('filament::page')
@section('image')
    <x-filament::page>
        <div class="flex flex-col space-y-4">
            <h2 class="text-2xl font-bold">{{ $record->title }}</h2>
            <p>{{ $record->image }}</p>
            @if($record->image)
                <img src="{{ $record->image }}" alt="{{ $record->title }}" class="w-full h-auto">
            @endif
        </div>
    </x-filament::page>
@endsection
