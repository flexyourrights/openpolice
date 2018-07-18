<!-- resources/views/vendor/openpolice/volun/volun-dept-edit-adm-menu.blade.php -->
<div class="h100 fixed brdRgtGrey" style="padding-right: 0px; width: 238px;">
    <div id="menuCust" class="admMenu w100 pT15 ovrSho">
        <a href="/dash/volunteer" class="slGrey"
            ><i class="fa fa-long-arrow-left mR5" aria-hidden="true"></i> Back to Department List</a>
        <div class="admMenuTier1 mT5" style="padding-left: 0px;">
            <a href="javascript:;" data-parent="#menuCust" data-toggle="collapse" data-target="#subA10"
                class="primeNav" style="color: #333;"><div class="admMenuIco"><i class="fa fa-eye"></i></div>
                Verifying Department</a>
            <div id="subA10" class="sublinks">
            @foreach ($lnks as $i => $lnk)
                <div class="admMenuTier2" style="padding-left: 23px;">
                    <a data-parent="#menuCust" data-hash="{{ $lnk[0] }}" id="admLnk{{ $lnk[0] }}" href="javascript:;"
                        class=" @if ($i == 0) active @endif hsho @if ($i > 4) slGrey @endif ">{!! $lnk[1] !!}</a></div>
            @endforeach
            </div>
        </div>
    </div>
    <a id="hidivBtnScripts" class="hidivBtnSelf mT20 slBlueLight" href="javascript:;"
        ><i class="fa fa-window-maximize fa-rotate-270 mR5" aria-hidden="true"></i> Scripts</a>
    <div id="hidivScripts" class="w100 disNon relDiv">
        <div class="absDiv fixDiv" style="right: 20px;">
            <a href="javascript:;" id="closeScripts" class="slRedLight"
                ><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <div id="admMenuCustBot" class="w100 ovrFloY pT15 brdTopFnt">
            <h4 class="mT0">Department Script</h4>
            <?php /*
            {!! $GLOBALS["SL"]->getBlurb('Phone Script: Department') !!}
            <hr>
            <h4 class="mT0">Internal Affairs Script</h4>
            {!! $GLOBALS["SL"]->getBlurb('Phone Script: Internal Affairs') !!}
            */ ?>
        </div>
    </div>
</div>