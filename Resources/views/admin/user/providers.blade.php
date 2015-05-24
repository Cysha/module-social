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
                    <th>Provider</th>
                    <th>Avatar</th>
                    <th>Details</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($user->providers()->get() as $provider)
                <tr>
                    <td>{{ ucwords($provider->provider) }}</td>
                    <td><img src="{{ $provider->avatar }}" alt="" style="height: 80px; width: 80px;"></td>
                    <td>{{ ($provider->created_at) }}</td>
                    <td>{{ $provider->provider }}</td>
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
