<!-- resources/views/vendor/openpolice/nodes/143-dept-loop-custom-row.blade.php -->

<div class="wrapLoopItem"><a name="item{{ $setIndex }}"></a>
    <div id="wrapItem{{ $itemID }}On" class="brdLgt round20 mB20 pL20 pR20">
        <div class="row">
            <div class="col-md-12 p20">
                <div class="fL">
                    <h4 class="m0 gry9">
                        {{ $GLOBALS['SL']->closestLoop["obj"]->DataLoopSingular }} #{{ (1+$setIndex) }}
                        @if (intVal($loopItem->DeptType) > 0) 
                            , {{ $GLOBALS['SL']->def->getValById($loopItem->DeptType) }}
                        @endif
                    </h4>
                    <h2 class="m0">{{ $loopItem->DeptName }}</h2>
                    <p>
                    @if (trim($loopItem->DeptAddress) != '') {{ $loopItem->DeptAddress }}, @endif
                    @if (trim($loopItem->DeptAddress2) != '') {{ $loopItem->DeptAddress2 }}, @endif
                    @if (trim($loopItem->DeptAddressCity) != '') {{ $loopItem->DeptAddressCity }}, @endif
                    {{ $loopItem->DeptAddressState }} {{ $loopItem->DeptAddressZip }}
                    @if (trim($loopItem->DeptAddressCounty) != '') , {{ $loopItem->DeptAddressCounty }} County @endif 
                    @if (trim($loopItem->DeptPhoneWork) != '') , {{ $loopItem->DeptPhoneWork }}<br /> @endif
                    @if (trim($loopItem->DeptWebsite) != '') , <a href="{{ $loopItem->DeptWebsite }}" target="_blank"
                        >{{ $loopItem->DeptWebsite }}</a> @endif 
                    </p>
                </div>
                <a href="javascript:;" id="editLoopItem{{ $itemID }}" class="editLoopItem btn btn-secondary mL10 mR10 fR"
                    ><i class="fa fa-pencil fa-flip-horizontal"></i> Edit</a>
                <a href="javascript:;" id="delLoopItem{{ $itemID }}" 
                    class="delLoopItem nFormLnkDel nobld btn btn-secondary mL10 mR10 fR"
                    ><i class="fa fa-times"></i> Delete</a>
                <input type="checkbox" class="disNon" 
                    name="delItem[]" id="delItem{{ $itemID }}" value="{{ $itemID }}" >
                <div class="fC"></div>
            </div>
        </div>
    </div>
    <div id="wrapItem{{ $itemID }}Off" class="wrapItemOff brdA round20 mB20">
        <i class="mR20 fL">Deleted: {{ $GLOBALS['SL']->closestLoop["obj"]->DataLoopSingular }} #{{ (1+$setIndex) }}: 
            {!! $loopItem->DeptName !!}</i> 
        <a href="javascript:;" id="unDelLoopItem{{ $itemID }}" class="unDelLoopItem nFormLnkEdit mL20 fR"
            ><i class="fa fa-undo"></i> Undo</a>
        <div class="fC"></div>
    </div>
</div>