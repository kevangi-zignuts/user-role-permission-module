@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
@endphp

@isset($configData["layout"])
  @include((( $configData["layout"] === 'horizontal') ? 'layouts.horizontalLayout' :
  (( $configData["layout"] === 'blank') ? 'layouts.blankLayout' : 'layouts.contentNavbarLayout') ))
@endisset




  {{-- @if (!isset(Auth::user()->tokens))
  <h1 class="text-center">Hello</h1>
  @if(file_exists(resource_path('views/content/authentications/auth-login-basic.blade.php')))
    @include('content.authentications.auth-login-basic')
  @else
    <p>Error: The login basic file is missing.</p>
  @endif

@else
  @isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
  @endisset
  @php
    $configData = Helper::appClasses();
  @endphp

  @isset($configData["layout"])
  @include((( $configData["layout"] === 'horizontal') ? 'layouts.horizontalLayout' :
    (( $configData["layout"] === 'blank') ? 'layouts.blankLayout' : 'layouts.contentNavbarLayout') ))
  @endisset
@endif --}}
