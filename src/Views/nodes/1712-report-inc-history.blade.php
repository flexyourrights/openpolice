<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-history.blade.php -->
@if (isset($history) && sizeof($history) > 0)
    @foreach ($history as $i => $h)
        @if ($i > 0) <hr> @endif
        <p>
        @if ($h["type"] == 'Status') Status: @elseif ($h["type"] == 'Email') Email: @endif
        {!! $h["desc"] !!}<br />
        {{ date("n/j/y g:ia", $h["date"]) }} by {!! $h["who"] !!}
        @if (isset($h["note"]) && trim($h["note"]) != '') 
            <br />{!! str_replace("\n", "<br />", $h["note"]) !!}
        @endif
        </p>
    @endforeach
@else
    <p><i>This complaint has not been reviewed yet.</i></p>
@endif