@php
use Illuminate\Support\Facades\Auth;
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{route('home')}}">
        Paris Nanterre
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <a class="nav-link ml-2" href="{{action('Controller@import')}}">get AST</a>
            <a class="nav-link ml-2" href="{{action('Controller@importApi')}}">API</a>
        </ul>

    </div>
</nav>

<!-- <p style=" position: absolute; bottom: 0; left: 0; width: 100%; text-align: center;">Développé par : Driss NAIT BELKACEM - Sekan TAS - Léa Habert.</p> -->
