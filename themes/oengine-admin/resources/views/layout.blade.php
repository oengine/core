<!doctype html>
<livewireg:oengine-layout>
    <div class="page-main">
        @include('theme::share.sidebar')
        <div class="page-container">
            @include('theme::share.header')
            <div class="page-content">
                @yield('content')
            </div>
            @include('theme::share.footer')
        </div>
    </div>
</livewireg:oengine-layout>
