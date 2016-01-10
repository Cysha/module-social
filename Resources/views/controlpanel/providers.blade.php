@extends(partial('auth::controlpanel._layout'), ['title' => 'Social Login'])

@section('control-form')
    <p>This page allows you to link your other accounts up so you can quickly login using your favorite service!</p>
    <!-- end of panel -->
    </div>
</div>

@if (count($configuredProviders))

    @set($userProviders, $user->providers()->get())
    @if(count($userProviders))
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Manage Social Logins</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info"><strong>Information:</strong> This panel shows what logins you have active.</div>

            <table class="table table-striped panel-body">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Provider</th>
                        <th>Link Created</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($userProviders as $provider)
                    <tr>
                        <td>{{ $provider->username }} <br /><img src="{{ $provider->avatar }}" alt="" style="height: 80px; width: 80px;"></td>
                        <td>{{ ucwords($provider->provider) }}</td>
                        <td>{!! array_get(date_array($provider->created_at), 'element') !!}</td>
                        <td><a href="{{ route('pxcms.user.remove_provider', ['provider' => $provider->provider]) }}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @set($userProviders, $userProviders->lists('provider')->toArray())
    @if(count($userProviders) != count($configuredProviders))
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">New Social Logins</h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-info"><strong>Information:</strong> Using the buttons below you can attach your account to multiple secondary authentications.</div>

            @foreach($configuredProviders as $provider)
                @set($info, array_get($socialiteProviders, $provider))

                @if(in_array($provider, $userProviders))
                    @continue
                @endif

                <a href="{{ route('pxcms.user.provider', ['provider' => $provider])}}" class="btn btn-sm"><i class="fa fa-fw {{ array_get($info, 'icon') }}"></i> {{ ucwords($provider) }}</a>

            @endforeach
        </div>
    </div>
    @endif

@else
    <div class="alert alert-warning">
        <strong>Information:</strong> There are currently no social providers configured.
    </div>
@endif

@stop
