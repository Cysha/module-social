@extends(partial('auth::admin.user._layout'))

@section('user-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Social Providers</h3>
        </div>
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
            @foreach ($user->providers()->get() as $provider)
                <tr>
                    <td>{{ $provider->username }} <br /><img src="{{ $provider->avatar }}" alt="" style="height: 80px; width: 80px;"></td>
                    <td>{{ ucwords($provider->provider) }}</td>
                    <td>{!! array_get(date_array($provider->created_at), 'element') !!}</td>
                    <td><a href="{{ route('admin.user.remove_provider', ['provider' => $provider->provider, 'user_id' => $user->id]) }}" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}
@stop
