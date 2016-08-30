@extends('master.master')
@section('content')
<div id="page-content-wrapper" class="">
    <div class="row">
        <div class="col-md-9 col-md-offset-2">
            @if(Session::has('message'))
                <p class="{{ Session::get('alert-class') }}">{{ Session::get('message') }}</p>
            @endif    
            <div id="results">
            <?php var_dump($artist_albums); ?>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">

</script>
@endpush

