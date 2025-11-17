@extends('base')
@section('content')
    <div style="background-color: #C9E2FE" class="d-flex flex-column align-items-center justify-content-center py-5">
        <div class="bg-white p-4" style="border-radius: 1rem">
            <img src={{ asset('images/PreKantinLogoBlack.png') }} size="40">
        </div>
        <div class="container text-center pt-5">
            <h1 class="fw-bold">{{ __('about.lead1') }}</h1>
            <p class="w-75 w-lg-50 mx-auto">{{ __('about.desc1')}}</p>

            <div class="mx-auto my-5" style="width: 800px; height: 4px; background-color: #000; border: none;"></div>

            <h1 class="fw-bold">{{ __('about.lead2') }}</h1>
            <p class="w-75 w-lg-50 mx-auto">{{ __('about.desc2') }}</p>

            <div class="mx-auto my-5" style="width: 800px; height: 4px; background-color: #000; border: none;"></div>

            <h1 class="fw-bold">{{ __('about.lead3') }}</h1>
            <p class="w-75 w-lg-50 mx-auto">{{ __('about.desc3') }}</p>
        </div>

    </div>
@endsection
