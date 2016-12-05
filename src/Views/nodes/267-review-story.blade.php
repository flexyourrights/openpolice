<!-- resources/views/vendor/openpolice/nodes/267-review-story.blade.php -->

<div class="nPromptHeader f36"><label for="n1FldID">Review Your Story</label></div>
<div id="nLabel1" class="fR w35 mB20 nPrompt">
    We will submit this story to official police oversight agencies. 
    So please check that your spelling and grammar is as good as you can make it.
    <div class="pT5 slBlueDark"><b>Your Allegations:</b> {!! $allegs !!}</div>
</div>
<div class="redDrk pL5 mTn20">*required</div>
<div id="nField" class="fL w60 mR20 nFld">
    <textarea name="n267fld" id="n267FldID"  onKeyUp="wordCountKeyUp(267);" style="height: 400px; width: 100%; font-size: 14pt; padding: 10px;" >{!! $ComSummary !!}</textarea>
    <input type="hidden" name="n267Visible" id="n267VisibleID" value="1">
</div>
<div id="nLabel2" class="fR w35 mT20 nPrompt">
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
window.onload = function() { var input = document.getElementById("n267FldID").focus(); }
setTimeout("wordCountKeyUp(267)", 1000);
</script>