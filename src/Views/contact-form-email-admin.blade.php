<p><b>Type of Inquiry</b><br />
@if ($GLOBALS["SL"]->REQ->has('n829fld'))
    {{ $GLOBALS["SL"]->REQ->n829fld }}
    @if ($GLOBALS["SL"]->REQ->n829fld == 'Complainant' && $GLOBALS["SL"]->REQ->has('n1879fld'))
        - {{ $GLOBALS["SL"]->REQ->n1879fld }}
    @elseif ($GLOBALS["SL"]->REQ->n829fld == 'Law enforcement' && $GLOBALS["SL"]->REQ->has('n1880fld'))
        - {{ $GLOBALS["SL"]->REQ->n1880fld }}
    @elseif ($GLOBALS["SL"]->REQ->n829fld == 'Attorney' && $GLOBALS["SL"]->REQ->has('n1881fld'))
        - {{ $GLOBALS["SL"]->REQ->n1881fld }}
    @elseif ($GLOBALS["SL"]->REQ->n829fld == 'Volunteer' && $GLOBALS["SL"]->REQ->has('n1873fld'))
        - {{ implode(', ', $GLOBALS["SL"]->REQ->n1873fld) }}
    @endif
@endif </p>
@if ($GLOBALS["SL"]->REQ->has('n828fld')) <p><b>Email</b><br />{{ $GLOBALS["SL"]->REQ->n828fld }}</p> @endif
@if ($GLOBALS["SL"]->REQ->has('n1972fld')) <p><b>Name</b><br />{{ $GLOBALS["SL"]->REQ->n1972fld }}</p> @endif
@if ($GLOBALS["SL"]->REQ->has('n830fld'))
    <p><b>Message</b><br />{{ str_replace("\n", "</p><p>", strip_tags($GLOBALS["SL"]->REQ->n830fld)) }}</p>
@endif
