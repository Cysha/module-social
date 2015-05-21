<div role="tabpanel">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#site-login" role="tab" data-toggle="tab">Site Login</a></li>
    <li role="presentation"><a href="#social-login" role="tab" data-toggle="tab">Social Login</a></li>
    <li role="presentation" class="pull-right"><a href="{{ route('pxcms.user.register') }}"role="tab" data-toggle="tab">Register</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="site-login">
        <div class="page-header">
            <h2>{{ config('cms.core.app.site-name') }} Login</h2>
        </div>
        {!! Theme::partial('core.login') !!}

    </div>
    <div role="tabpanel" class="tab-pane" id="social-login">
        <div class="page-header">
            <h2>Site Login</h2>
        </div>

        <div class="alert alert-info"><strong>Information:</strong> Using the buttons below you can login using your preffered method. Once logged in, you can add more to your account as a backup.</div>
        <table class="panel-body table table-bordered table-striped">
            <tbody>
            <?php $counter = 0; ?>
            <tr>
            @foreach($socialiteProviders as $provider => $info)
                <?php
                if (!in_array(strtolower($provider), $configuredProviders)) {
                    continue;
                }
                ?>
                @if ($counter % 6 == 0)
                    </tr><tr>
                @endif
                    <td><a href="{{ route('pxcms.user.provider', ['provider' => $provider])}}" class="btn btn-sm"><i class="fa fa-fw {{ array_get($info, 'icon') }}"></i> {{ ucwords($provider) }}</a></td>
                <?php $counter++; ?>
            @endforeach
            </tr>
            </tbody>
        </table>
    </div>
  </div>
</div>
