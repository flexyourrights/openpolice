<!-- resources/views/vendor/openpolice/admin/lists/officers.blade.php -->

@extends('vendor.survloop.master')

@section('content')

<h1>
    <i class="fa fa-list-ul"></i> Police Officers 
    @if ($officers->isNotEmpty())
        <nobr><span class="f14">({{ number_format(sizeof($officers)) }})</span></nobr>
    @endif
</h1>

<div class="p5"></div>


<div class="slBlueDark" style="padding: 100px;"><h2><i>Coming Soon</i></h2></div>


<div class="adminFootBuff"></div>

@endsection