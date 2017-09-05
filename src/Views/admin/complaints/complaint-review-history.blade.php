<!-- resources/views/vendor/openpolice/admin/complaints/complaint-review-history.blade.php -->

@if ($history && sizeof($history) > 0)
    @foreach ($history as $i => $h)
        <div class="p5 brdBot">
            <h4 class="m0 slBlueDark">
                @if ($h["type"] == 'Status')
                    <i class="fa fa-tachometer mR5" aria-hidden="true"></i> 
                @elseif ($h["type"] == 'Email')
                    <i class="fa fa-envelope mR5" aria-hidden="true"></i>
                @endif
                {!! $h["desc"] !!}
            </h4>
            <span class="slGrey">{!! $h["who"] !!}, {{ date("n/j/y h:ia", $h["date"]) }}</span>
        </div>
    @endforeach
@else
    <div class="p5 brdBot"><i>This complaint has not been reviewed yet.</i></div>
@endif