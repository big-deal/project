<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    {{-- Left Side Of Navbar --}}
    <ul class="navbar-nav mr-auto">
        @includeIf('common.navbar.left')
    </ul>

    {{-- Right Side Of Navbar --}}
    <ul class="navbar-nav ml-auto">
        @includeIf('common.navbar.right')
        @includeIf('common.navbar.auth')
    </ul>
</div>
