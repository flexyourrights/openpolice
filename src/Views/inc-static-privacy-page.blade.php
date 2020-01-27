<!-- resources/views/vendor/openpolice/inc-static-privacy-page.blade.php -->

<form method="post" name="ownerPublish" action="?ownerPublish=1&refresh=1{{
    (($GLOBALS['SL']->REQ->has('frame')) ? '&frame=1' : '') }}">
<input type="hidden" id="csrfTok" name="_token" value="{{ csrf_token() }}">

<div id="nodeStaticPrivacy" class="nodeWrap">
    <input type="hidden" name="n2018radioCurr" id="n2018radioCurrID" value="">
    <div id="nLabel2018" class="nPrompt">
        <!--- <h2 class="slBlueDark">Publishing Privacy Options</h2> --->
        <p>After filing your complaint for investigation, your full story can 
        be published on OpenPolice.org. Please select your privacy option.</p>
        <p><b>No matter which one you choose, we <nobr>will ...</nobr></b></p>
        <ul>
            <li>Publish no one’s private information. 
                That includes addresses, phone numbers, <nobr>emails, etc.</nobr></li>
            <li>Try to send your full complaint to a police investigative agency.</li>
        </ul>
        <p>
            You have @if ($twoOptions) two @else three @endif
            options for how we collect your data and how we share it. 
        <!--- <span id="req2018" class="rqd"><nobr>*required</nobr></span> --->
        </p>
    </div>
    <div class="nFld" style="margin-top: 12px;">
        <label for="n2018fld0" id="n2018fld0lab" class="finger">
            <div class="disIn mR5">
                <input id="n2018fld0" value="304" type="radio" name="n2018fld" data-nid="2018" 
                    class="nCbox2018  slTab slNodeChange ntrStp" autocomplete="off" tabindex="1">
            </div>
            <h4 class="disIn slBlueDark">Full Transparency</h4>
            <div class="privOptPadL">
                <ul>
                    <li>You will publish your FULL complaint on OpenPolice.org. That includes your written story, the names of civilians and police officers, and your detailed survey answers.</li>
                    <li>Search engines will index your complaint. That means you cannot erase it, and you will publicly link it to your name.</li>
                </ul>
            </div>
        </label>
        <label for="n2018fld1" id="n2018fld1lab" class="finger">
            <div class="disIn mR5">
                <input id="n2018fld1" value="305" type="radio" name="n2018fld" data-nid="2018" 
                class="nCbox2018  slTab slNodeChange ntrStp" autocomplete="off" tabindex="2">
            </div>
            <h4 class="disIn slBlueDark">No Names Public</h4>
            <div class="privOptPadL">
                <ul>
                    <li>You will only publish your multiple-choice answers on OpenPolice.org. That will NOT include your written story nor information showing police officers’ identities.</li>
                </ul>
            </div>
        </label>
    @if (!$twoOptions)
        <label for="n2018fld2" id="n2018fld2lab" class="finger">
            <div class="disIn mR5">
                <input id="n2018fld2" value="306" type="radio" name="n2018fld" data-nid="2018" 
                class="nCbox2018  slTab slNodeChange ntrStp" autocomplete="off" tabindex="3">
            </div>
            <h4 class="disIn slBlueDark">Anonymous</h4>
            <div class="privOptPadL">
                <ul>
                    <li>Investigators cannot contact you. 
                    That will make it harder to investigate your complaint.</li>
                    <li>You will only publish your multiple-choice answers on OpenPolice.org. 
                    That will NOT include your written story nor information 
                    showing police officers’ identities.</li>
                    <li>We will delete all personal information from our records.</li>
                </ul>
            </div>
        </label>
    @endif
    </div>

    <center><input type="submit" value="Save Privacy Options" class="btn btn-lg btn-primary mT20"
        onMouseOver="this.style.color='#2b3493';" onMouseOut="this.style.color='#FFF';"
        style="color: #FFF;"></center>
    </form>
    
</div>

<style>
h4.disIn { padding-top: 5px; }
.privOptPadL { padding-top: 10px; }
</style>