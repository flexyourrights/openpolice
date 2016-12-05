<!-- resources/views/vendor/openpolice/volun/inc-oversightEdit.blade.php -->

<div class="row gry9">
    <div class="col-md-6">
        @if ($overType == 'IA')
            <h2 class="slBlueDark m0">Internal Affairs Office</h2>
            <input type="hidden" name="OverID" 
            @if (isset($overRow->OverID) && intVal($overRow->OverID) > 0) 
                value="{{ $overRow->OverID }}" 
            @else 
                value="-3" 
            @endif 
            >
        @else 
            <h2 class="slBlueDark m0">Civilian Oversight Office</h2>
                @if (isset($overRow->OverID) && intVal($overRow->OverID) > 0)
                    <div id="CivOverForm" class="disBlo">
                @else 
                    <div id="CivOversightWrap" class="disBlo">
                        <a id="CivOversightBtn" class="btn btn-default" href="javascript:void(0)"
                            ><i class="fa fa-plus-circle"></i> Add Civilian Oversight Agency</a>
                    </div>
                    <div id="CivOverForm" class="disNon">
                @endif
            <input type="hidden" name="CivOverID" 
            @if (isset($overRow->OverID) && intVal($overRow->OverID) > 0)
                value="{{ $overRow->OverID }}"
            @else 
                value="-3"
            @endif
            >
            <fieldset class="form-group">
                <label for="CivOverAgncNameID"><b class="blk">Civilian Oversight Agency Name</b></label>
                <input id="CivOverAgncNameID" name="CivOverAgncName" 
                    value="{{ $overRow->OverAgncName }}" type="text" class="form-control" > 
            </fieldset>
            <fieldset class="form-group">
                <label for="{{ $overType }}OverWebsiteID"><b class="blk">Website</b> <span class="red">*required</span></label>
                <input id="{{ $overType }}OverWebsiteID" name="{{ $overType }}OverWebsite" 
                    value="{{ $overRow->OverWebsite }}" type="text" class="form-control" >
            </fieldset>
            <fieldset class="form-group">
                <label for="CivOverCivModel">Civilian Oversight Model</label>
                <select id="CivOverCivModelID" name="CivOverCivModel" class="form-control">
                    <option value="0" @if (intVal($overRow->OverCivModel) == 0) SELECTED @endif >select model</option>
                    <option value="300" @if (intVal($overRow->OverCivModel) == 300) SELECTED @endif >Investigative</option>
                    <option value="301" @if (intVal($overRow->OverCivModel) == 301) SELECTED @endif >Review</option>
                    <option value="302" @if (intVal($overRow->OverCivModel) == 302) SELECTED @endif >Audit</option>
                </select>
            </fieldset>
        @endif
        
        <fieldset class="form-group">
            <label for="{{ $overType }}OverEmailID">Email Address 
                <small class="text-muted gryA mL20">(if no general email, then the best person to email with complaints)</small></label>
            <input id="{{ $overType }}OverEmailID" name="{{ $overType }}OverEmail" 
                value="{{ $overRow->OverEmail }}" type="text" class="form-control" >
        </fieldset>
        <fieldset class="form-group">
            <label for="{{ $overType }}OverPhoneWorkID">Phone Number <small class="text-muted gryA mL20">(w/ extension)</small></label>
            <input id="{{ $overType }}OverPhoneWorkID" name="{{ $overType }}OverPhoneWork" 
                value="{{ $overRow->OverPhoneWork }}" type="text" class="form-control" >
        </fieldset>
        
        <fieldset class="form-group">
            <label for="{{ $overType }}OverAddressID">Street Address</label>
            <input id="{{ $overType }}OverAddressID" name="{{ $overType }}OverAddress" 
                value="{{ $overRow->OverAddress }}" type="text" class="form-control" > 
        </fieldset>
        <fieldset class="form-group">
            <label for="{{ $overType }}OverAddress2ID">Address Line 2</label>
            <input id="{{ $overType }}OverAddress2ID" name="{{ $overType }}OverAddress2" 
                value="{{ $overRow->OverAddress2 }}" type="text" class="form-control" > 
        </fieldset>
        <div class="row">
            <div class="col-md-6">
                <fieldset class="form-group">
                    <label for="{{ $overType }}OverAddressCityID">City</label>
                    <input id="{{ $overType }}OverAddressCityID" name="{{ $overType }}OverAddressCity" 
                        value="{{ $overRow->OverAddressCity }}" type="text" class="form-control" > 
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset class="form-group">
                    <label for="{{ $overType }}OverAddressStateID">State</label>
                    <select id="{{ $overType }}OverAddressStateID" name="{{ $overType }}OverAddressState" class="form-control" autocomplete="off" >
                        {!! $stateDrop !!}
                    </select>
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset class="form-group">
                    <label for="{{ $overType }}OverAddressZipID">Zip</label>
                    <input id="{{ $overType }}OverAddressZipID" name="{{ $overType }}OverAddressZip" 
                        value="{{ $overRow->OverAddressZip }}" type="text" class="form-control" > 
                </fieldset>
            </div>
        </div>
        @if ($overType == 'Civ')
            </div>
        @endif
    </div>
    
    <div class="col-md-1"></div>
    
    <div class="col-md-5 nobld gry9">
        @if (!$alreadyHascontact)
            <div id="{{ $overType }}ContactBtn" class="disBlo pT20">
                <a href="javascript:void(0)" class="btn btn-default" id="{{ $overType }}OverContactBtn"
                    ><i class="fa fa-plus-circle"></i> Add Primary Contact</a>
            </div>
            <div id="{{ $overType }}OverContactForm" class="disNon pT5">
        @endif
        <h3 class="blk">Primary Contact:</h3>
        <fieldset class="form-group">
            <label for="{{ $overType }}OverNameFirstID">First Name</label>
            <input id="{{ $overType }}OverNameFirstID" name="{{ $overType }}OverNameFirst" 
                value="{{ $overRow->OverNameFirst }}" type="text" class="form-control" > 
        </fieldset>
        <fieldset class="form-group">
            <label for="{{ $overType }}OverNameLastID">Last Name</label>
            <input id="{{ $overType }}OverNameLastID" name="{{ $overType }}OverNameLast" 
                value="{{ $overRow->OverNameLast }}" type="text" class="form-control" > 
        </fieldset>
        <fieldset class="form-group">
            <label for="{{ $overType }}OverTitleID">Job Title</label>
            <input id="{{ $overType }}OverTitleID" name="{{ $overType }}OverTitle" 
                value="{{ $overRow->OverTitle }}" type="text" class="form-control" > 
        </fieldset>
        @if ($overType == 'IA')
            <fieldset class="form-group">
                <label for="{{ $overType }}OverIDnumberID">ID#</label>
                <input id="{{ $overType }}OverIDnumberID" name="{{ $overType }}OverIDnumber" 
                    value="{{ $overRow->OverIDnumber }}" type="text" class="form-control" > 
            </fieldset>
        @endif
        <fieldset class="form-group">
            <label for="{{ $overType }}OverNicknameID">Nickname</label>
            <input id="{{ $overType }}OverNicknameID" name="{{ $overType }}OverNickname" 
                value="{{ $overRow->OverNickname }}" type="text" class="form-control" > 
        </fieldset>
        @if (!$alreadyHascontact) 
            </div> <!-- end Primary Contact -->
        @endif
    </div>
</div>