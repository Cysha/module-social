<div class="col-md-8 col-md-offset-2">
    <div class="page-header">
        <h2>{{ config('cms.core.app.site-name') }} Login</h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Please Sign In
                <a href="{{ route('pxcms.user.register') }}" class="btn btn-default btn-sm pull-right">Register</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <h4>Social Login</h4>
                <ul class="list-group">
                @foreach($socialiteProviders as $provider => $info)
                    @if(!in_array(strtolower($provider), $configuredProviders))
                        @continue
                    @endif

                    <a href="{{ route('pxcms.user.provider', ['provider' => $provider])}}" class="list-group-item social social-{{ strtolower($provider) }}">
                        <i class="fa fa-fw {{ array_get($info, 'icon') }}"></i> {{ ucwords($provider) }}
                    </a>
                @endforeach
                </ul>

            </div>
            <div class="col-md-8">
                <h4>Direct Login</h4>
                {!! Theme::partial('core._login_form') !!}
            </div>
        </div>
    </div>
</div>
