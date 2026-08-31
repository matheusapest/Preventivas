@extends('layout.app')

@section('content')

    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">

        @include(
            'configurations.preventives.partials.continuation._header'
        )

        @include(
            'configurations.preventives.partials.continuation._review'
        )

        @include(
            'configurations.preventives.partials.continuation._pending-units'
        )

        @include(
            'configurations.preventives.partials.continuation._unit-selector'
        )

        @include(
            'configurations.preventives.partials.continuation._selected-units'
        )

        @include(
            'configurations.preventives.partials.continuation._actions'
        )

    </div>

@endsection

@vite('resources/js/preventive/continuation.js')
