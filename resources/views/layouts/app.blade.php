@extends('layouts.base')

@section('body')

<x-layouts.navigation></x-layouts.navigation>
<x-layouts.sidebar></x-layouts.sidebar>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      @yield('content')
    </div>
  </section>
</div>
<x-layouts.footer></x-layouts.footer>
@endsection