<!DOCTYPE html>
@langrtl
    <html lang="{{ app()->getLocale() }}" dir="rtl">
@else
    <html lang="{{ app()->getLocale() }}">
@endlangrtl
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        @yield('meta')

        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        {{ style(mix('css/frontend.css')) }}

        @stack('after-styles')
    </head>
    <body>
        <div id="app" class="d-none">
            @include('includes.partials.logged-in-as')
            @include('frontend.includes.nav')

            <div class="container">
                @include('includes.partials.messages')
                @yield('content')
            </div><!-- container -->
        </div><!-- #app -->




        <div id="app" class="app"><div class="auth-layout"> <div class="main row"><div class="auth-content col-lg-6 col-12"><div class="signup"><h2>Create account</h2> <form method="post" action="/auth/signup" name="signup"><div class="form-group"><div class="input-group"><input type="text" id="email" required="required"> <label for="email" class="control-label">Email</label><i class="bar"></i></div></div> <div class="form-group"><div class="input-group"><input type="password" id="password" required="required"> <label for="password" class="control-label">Password</label><i class="bar"></i></div></div> <div class="vuestic-checkbox form-check abc-checkbox abc-checkbox-primary"><input type="checkbox" id="checkbox1" name="" class="form-check-input"> <label for="checkbox1" class="form-check-label"><span class="abc-label-text"></span></label></div> <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between down-container"><button type="submit" class="btn btn-primary">
        Sign Up
      </button> <a href="#/auth/login" class="link">Already joined?</a></div></form></div></div> <div class="auth-wallpaper col-6 d-none d-lg-flex"><div class="oblique"></div> <a href="#/" class="i-vuestic router-link-active"></a></div></div></div></div>

        <!-- Scripts -->
        @stack('before-scripts')
        {!! script(mix('js/frontend.js')) !!}
        @stack('after-scripts')

        @include('includes.partials.ga')
    </body>
</html>
