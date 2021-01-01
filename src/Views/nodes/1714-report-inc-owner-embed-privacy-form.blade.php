<!-- resources/views/vendor/openpolice/nodes/1714-report-inc-owner-embed-privacy-form.blade.php -->

<div id="ownerPrivacyWrap" class="pL15 pR15 pT15 pB30">
    {!! $GLOBALS["SL"]->spinner() !!}
</div>
<script type="text/javascript"> $(document).ready(function(){
    <?php $src = "/toolbox-complainant-privacy-form/readi-" 
        . $complaint->com_id . "?ajax=1"; ?>
    setTimeout(function() {
        console.log("{!! $src !!}"); 
        $("#ownerPrivacyWrap").load("{!! $src !!}");
    }, 100);
}); </script>
<div class="p10"></div>
