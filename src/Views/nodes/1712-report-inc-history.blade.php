<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-history.blade.php -->
<div class="w100 mTn15 pL15 pR15 pB30">
@if (isset($history) && sizeof($history) > 0)
    @foreach ($history as $i => $h)
        <?php $status = (($h["type"] == 'Status') 
                ? 'Status: ' 
                : (($h["type"] == 'Email') ? 'Email: ' : ''))
            . $h["desc"] . '<br />'
            . date("n/j/y g:i a", $h["date"]) . ' by '; ?>
        @if ($i > 0) <hr> @endif
        <div class="disBlo">
        @if (trim(strip_tags($h["note"])) != '')
            {!! $GLOBALS["SL"]->printAccordian(
                $status . strip_tags($h["who"]),
                '<div class="pB15 pL15 pR15">'
                    . $h["note"] . '</div>',
                false,
                false,
                'text'
            ) !!}
        @else
            {!! $status . $h["who"] !!}
        @endif
        </div>
    @endforeach
@else
    <p><i>This complaint has not been reviewed yet.</i></p>
@endif
</div>
