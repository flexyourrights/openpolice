<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-history.blade.php -->
@if (isset($history) && sizeof($history) > 0)
    @foreach ($history as $i => $h)
        @if ($i > 0) <hr> @endif
        <p>
        @if ($h["type"] == 'Status') Status: 
        @elseif ($h["type"] == 'Email') Email: 
        @endif
        {!! str_replace('Oversight', 'Investigative Agency', $h["desc"]) !!}
        @if (isset($h["note"]) && trim($h["note"]) != '') 
            @if (trim($h["desc"]) != '') <br /> @endif
            {!! str_replace("\n", "<br />", $h["note"]) !!}
        @endif
        <br />{{ date("n/j/y g:ia", $h["date"]) }} by {!! $h["who"] !!}
        </p>
    @endforeach
@else
    <p><i>This complaint has not been reviewed yet.</i></p>
@endif