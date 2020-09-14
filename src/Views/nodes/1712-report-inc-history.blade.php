<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-history.blade.php -->
<div class="pL15 pR15">
@if (isset($history) && sizeof($history) > 0)
    @foreach ($history as $i => $h)
        @if ($i > 0) <hr> @endif
        <div class="disBlo">
        {!! $GLOBALS["SL"]->printAccordian(
            (($h["type"] == 'Status') 
                ? 'Status: ' 
                : (($h["type"] == 'Email') ? 'Email: ' : ''))
                . $h["desc"],
            '<div class="pB15">' . $h["note"] . '</div>',
            false,
            false,
            'text'
        ) !!}
        </div>
        <div class="clearfix"></div>
        <div class="disBlo">
            {{ date("n/j/y g:i a", $h["date"]) }} by {!! $h["who"] !!}
        </div>
    @endforeach
@else
    <p><i>This complaint has not been reviewed yet.</i></p>
@endif
</div>
