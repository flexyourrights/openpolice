<!-- resources/views/vendor/openpolice/nodes/138-privacy-options.blade.php -->

<input type="hidden" name="n138Visible" id="n138VisibleID" value="1">
<div id="node138" class="nodeWrap">
    <h2 class="mT0 slBlueDark">Transparency Options</h2>
    <div class="nPrompt">
        How do you want the names of people involved to appear on your public complaint?
    </div>

    <div class="div mT10 pT10 pB20 f22">
        <label for="n138fld0">
            <h2>
                <input type="radio" name="n138fld" id="n138fld0" class="mR10" autocomplete="off" value="304" 
                    @if ($ComPrivacy == 304) CHECKED @endif  >
                Full Transparency
            </h2>
            <div class="pL20">
                I want to publish all the names of civilians and police officers on this website.
            </div>
        </label>
    </div>
    
    <div class="pB20 f22">
        <label for="n138fld1">
            <h2 class="mT10">
                <input type="radio" name="n138fld" id="n138fld1" class="mR10" autocomplete="off" value="305" 
                    @if ($ComPrivacy == 305) CHECKED @endif  >
                No Names Public
            </h2>
            <div class="pL20">
                I don't want to publish any names on this website.
            </div>
        </label>
        <div class="gry9 f16 pL20"><i>
            This includes police officers' names and badge numbers too.
        </i></div>
    </div>
    
    <div class="pB20 f22">
        <label for="n138fld2">
            <h2 class="mT10">
                <input type="radio" name="n138fld" id="n138fld2" class="mR10" autocomplete="off" value="306" 
                    @if ($ComPrivacy == 306) CHECKED @endif  >
                Anonymous
            </h2>
            <div class="pL20">
                I need my complaint to be completely anonymous, even though it will be harder to investigate.
            </div>
        </label>
        <div class="gry9 f16 pL20"><i>
            No names will be published on this website. Neither OPC staff nor investigators will be able to contact you. 
            Any details that could be used for personal identification may be deleted from the database.
        </i></div>
    </div>
    
    <div class="nPrompt pT10">
        <i><b>Privacy Note:</b> No matter which option you select, we'll never publish anyone's private information. 
        (This includes addresses, phone numbers, emails, etc.) We will only share this with appropriate agencies who can investigate your complaint.</i>
    </div>

</div> <!-- end #node138 -->

<div class="nodeGap"></div>