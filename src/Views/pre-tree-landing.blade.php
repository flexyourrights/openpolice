<!-- resources/views/vendor/openpolice/pre-tree-landing.blade.php -->

<div class="pT20 pB10 slBlueDark">
    <h1 class="disIn mR10"><nobr>Filing Your Complaint,</nobr></h1> <h2 class="disIn"><nobr>Sharing Your Story</nobr></h2>
</div>

<div id="aboutTxt" class="f22 gry6">

    <p class="p10">
        <div class="fPerc125 slBlueDark">Open Police Complaints</div> strives to help <b>empower</b>
        victims or witnesses of police misconduct to create high-quality complaints about the police. 
        We will then do our best to <b>officially file your complaint</b> with the appropriate 
        police department or its civilian oversight agency.
    </p>
    <p class="p10">
        <div class="fPerc125 slBlueDark">Your complaint will also be published on this web site</div> 
        (without any personal contact information) so you can easily share your story with others. 
        (<a href="#privOpts">Privacy options are described below.</a>)
    </p>
    <p class="p10">
        <div class="fPerc125 slBlueDark">The more questions you answer, the <b>stronger</b> your complaint will be.</div> 
        You will also strengthen everybody who is working to <b>improve police accountability nationwide</b>, 
        by adding to this growing collection of open data about police complaints.
    </p>
    <div class="p10"></div>
    <p class="pT10 pB20">
        <a id="startBtn" class="btn btn-lg btn-primary" href="/start">Start Creating A New Complaint</a>
    </p>
    
    <p class="p10 pB20 gry9">
        <b class="red fPerc125">If</b> you started a complaint but got disconnected, you can 
        <a href="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/login">login</a> to finish it.<br />
        <a name="privOpts"></a>
        If you submitted a complaint and want to post updates about the investigation, you can 
        <a href="{{ $GLOBALS['SL']->sysOpts['app-url'] }}/login">login</a> for that too.
    </p>
    
    <h1 class="mT20 slBlueDark">Privacy / Transparency Options</h1>
    <i>You will have a number of different options of how to submit your police complaint...</i>
    <ul>
        <li>
            <h3 class="slBlueDark">Try to officially file your complaint with the police department</h3>
            <div class="p5"></div>
            <ul>
                <li>
                    <b class="slBlueDark">Publicly publish your complaint on this web site.</b><br />
                    <small>Choose to either make <span class=" class="slBlueDark"">all names public</span>, or make no names public 
                    (for <span class="slBlueDark">both civilians and officers</span>).</small>
                    <div class="p10"></div>
                </li>
                <li>
                    File an completely anonymous complaint.<br />
                    <small>This means any information you provide is shared with official investigators, 
                    but only your answers to multiple-choice questions will appear on this web site.</small>
                </li>
            </ul>
        </li>
    </ul>
    <div class="p10"></div>
    <ul>
        <li>
            <h3 class="slBlueDark">OR -- Do not officially file or publish your complaint right now</h3>
            <i>(strongly recommended if anyone has any unresolved criminal charges)</i>
            <div class="p10"></div>
            <ul>
                <li>Just print out your high-quality complaint for your attorney<div class="p10"></div></li>
                <li>Save your full complaint as a file to upload here later<div class="p10"></div></li>
                <li>Provide anonymous data to help police accountability<div class="p10"></div></li>
            </ul>
        </li>
    </ul>
    
    <p class="pT10 pB20">
        <a id="startBtn2" class="btn btn-lg btn-primary" href="/start">Start Creating A New Complaint</a>
    </p>
    
</div>

<style>
#startBtn, #startBtn2 {
    width: 100%; 
    font-size: 44px; 
    padding: 20px 40px;
}
@media screen and (max-width: 768px) {
    #startBtn, #startBtn2 {
        font-size: 30px; 
        padding: 20px 10px;
    }
}
@media screen and (max-width: 480px) {
    #startBtn, #startBtn2 {
        font-size: 24px; 
        padding: 20px 5px;
    }
    #aboutTxt {
        font-size: 18px;
    }
}
</style>