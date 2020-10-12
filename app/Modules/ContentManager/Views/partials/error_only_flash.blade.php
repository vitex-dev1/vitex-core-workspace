@if (session()->has('flash_notification.message'))
    @if (session()->has('flash_notification.overlay'))
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => session('flash_notification.title'),
            'body'       => session('flash_notification.message')
        ])
    @else
        <div class="alert
                    alert-{{ session('flash_notification.level') }}
        {{ session()->has('flash_notification.important') ? 'alert-important' : '' }}"
        >
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <ul>
                <li>{!! session('flash_notification.message') !!}</li>
            </ul>
        </div>
    @endif
@endif
