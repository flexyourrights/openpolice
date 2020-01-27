<!-- resources/views/vendor/openpolice/nodes/1217-volun-home-your-info.blade.php -->
@if (!isset($yourContact->prsn_address_state) 
    || !$yourContact->prsn_address_state 
    || trim($yourContact->prsn_address_state) == '')
    <form name="volunStateForm" method="post" action="?saveDefaultState=1">
    <input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">
    <div class="mB20 pB10"><div id="volunState" class="slCard w100">
        <h3 class="m0 slBlueDark">Info About You</h3>
        <div class="nFld"><label>
            What State Do You Live In?
            <select id="newStateID" name="newState" class="form-control mB20">
                <option value="" SELECTED >select state</option>
                {!! $GLOBALS['SL']->states->stateDrop() !!}
            </select>
        </label></div>
        <div class="nFld mT0"><label>
            What is your phone number? 
            <input name="newPhone" type="text" class="form-control" 
            @if (isset($yourContact) && isset($yourContact->prsn_phone_mobile))
                value="{{ trim($yourContact->prsn_phone_mobile) }}"
            @else value="" @endif >
        </label></div>
        <input type="submit" class="btn btn-primary disBlo mT10" value="Save Your Info">
    </div></div>
    </form>
@else
    <center><img src="/openpolice/uploads/flex-arm-gold2.png" border=0 class="mT20 w66" ></center>
@endif