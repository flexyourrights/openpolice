<!-- resources/views/vendor/openpolice/nodes/1-landing-page.blade.php -->

<div class="nPromptHeader f36"><label for="n1FldID">Share Your Story</label></div>
<div id="nLabel1" class="fR w35 mB20 nPrompt">
    Memories fade fast. So please take a few minutes to write down all of the details you can remember below. 
    Don't worry about spelling or grammar yet. You'll be able to edit your story anytime.
</div>
<div class="redDrk pL5 mTn20">*required</div>
<div id="nField" class="fL w60 mR20 nFld">
    <textarea name="n1fld" id="n1FldID" onKeyUp="wordCountKeyUp(1);" class="form-control" style="height: 345px;" 
    >{{ $ComSummary }}</textarea>
    <input type="hidden" name="n1Visible" id="n1VisibleID" value="1">
</div>
<div id="nLabel2" class="fR w35 mT20 nPrompt">
    <b>Pro-Tips</b>
    <ul style="margin: 0px;">
    <li class="mLn20 mB10">If you've had an ongoing problem with police, focus on one incident for now.</li>
    <li class="mLn20"><div>Keep this under 400 words.</div><i>word count:</i> &nbsp;&nbsp;<div id="wordCnt1" class="f18 disIn"></div></li>
    </ul>
</div>
<div id="nClearBot" class="clearfix p5"></div>

<style>
@media screen and (max-width: 768px) {
    #nLabel1 { float: none; width: 100%; }
    #nField { float: none; width: 100%; margin-right: 0px; }
    #nLabel2 { float: none; width: 100%; }
    #nClearBot { padding: 0px; margin-bottom: -20px; }
}
</style>

<script type="text/javascript">
addFld("n1FldID");
/* window.onload = function() { var input = document.getElementById("n1FldID").focus(); } */
setTimeout("wordCountKeyUp(1)", 1000);
</script>