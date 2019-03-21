{{-- User Dropdown --}}
@if(!Auth::user()->hasVerifiedEmail())
    <a class="dropdown-item has-icon text-danger" href="{{ route('verification.resend') }}">
        <i class="fas fa-fw fa-check"></i>
        {{ __('Resend verification') }}
    </a>
    <div class="dropdown-divider"></div>
@endif

