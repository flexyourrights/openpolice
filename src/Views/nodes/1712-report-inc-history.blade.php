<!-- resources/views/vendor/openpolice/nodes/1712-report-inc-history.blade.php -->
@if (isset($history) && sizeof($history) > 0)
    <div class="pL15 pR15 fPerc80 slGrey">[New] Status, Date, By Who</div>
    <table class="table table-striped">
    @foreach ($history as $i => $h)
        <tr @if ($i%2 == 0) class="row2" @endif >
            <td><h4 class="m0 slBlueDark">
                        @if ($h["type"] == 'Status')    <i class="fa fa-sign-in" aria-hidden="true"></i>
                        @elseif ($h["type"] == 'Email') <i class="fa fa-envelope" aria-hidden="true"></i> @endif
            </h4></td>
            <td class="w100">
                <h4 class="m0 slBlueDark">{!! $h["desc"] !!}</h4>
                <div class="fPerc80 slGrey">{{ date("n/j/y h:ia", $h["date"]) }}, {!! $h["who"] !!}</div>
                @if (isset($h["note"]) && trim($h["note"]) != '') {!! $h["note"] !!} @endif
            </td>
        </tr>
    @endforeach
    </table>
@else
    <div class="p5 brdBot"><i>This complaint has not been reviewed yet.</i></div>
@endif