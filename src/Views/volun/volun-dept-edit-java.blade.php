/* resources/views/vendor/openpolice/volun/volun-dept-edit-java.blade.php */
window.onload = function() {
    var input = document.getElementById("DeptEmailID").focus();
}
function checkScore() {
    var newScore = 0;
    if (document.getElementById("IAOverWebsiteID") && document.getElementById("IAOverWebsiteID").value != "")                             newScore += {{ $deptPoints["Website"] }};
    if (document.getElementById("IAOverFacebookID") && document.getElementById("IAOverFacebookID").value != "")                         newScore += {{ $deptPoints["FB"] }};
    if (document.getElementById("IAOverTwitterID") && document.getElementById("IAOverTwitterID").value != "")                             newScore += {{ $deptPoints["Twit"] }};
    if (document.getElementById("IAOverYouTubeID") && document.getElementById("IAOverYouTubeID").value != "")                             newScore += {{ $deptPoints["YouTube"] }};
    if (document.getElementById("IAOverWebComplaintInfoID") && document.getElementById("IAOverWebComplaintInfoID").value != "")         newScore += {{ $deptPoints["ComplaintInfo"] }};
    if (document.getElementById("IAOverComplaintPDFID") && document.getElementById("IAOverComplaintPDFID").value != "")                 newScore += {{ $deptPoints["FormPDF"] }};
    if (document.getElementById("IAOverHomepageComplaintLinkA") && document.getElementById("IAOverHomepageComplaintLinkA").checked)     newScore += {{ $deptPoints["ComplaintInfoHomeLnk"] }};
    @foreach ($ways as $i => $w)
        @if ($i > 0) 
            if (document.getElementById("IA{{ $waysFlds[$i] }}ID") && document.getElementById("IA{{ $waysFlds[$i] }}ID").checked)         newScore += {{ $wayPoints[$i] }};
        @endif
    @endforeach
    if (document.getElementById("IAOverComplaintWebFormID") && document.getElementById("IAOverComplaintWebFormID").value != "")         newScore += {{ $deptPoints["FormPDF"] }};
    if (document.getElementById("ScoreOpen")) document.getElementById("ScoreOpen").value=newScore;
    if (document.getElementById("ScoreOpenVis")) document.getElementById("ScoreOpenVis").innerHTML=newScore;
    return true;
}
setTimeout("checkScore()", 50);

function formSub()
{
    /*
    if (document.getElementById("IAOverWebComplaintInfoID").value == "" && document.getElementById("CivOverWebComplaintInfoID").value != "") {
        document.getElementById("IAOverWebComplaintInfoID").value = document.getElementById("CivOverWebComplaintInfoID").value;
    }
    if (document.getElementById("IAOverComplaintPDFID").value == "" && document.getElementById("CivOverComplaintPDFID").value != "") {
        document.getElementById("IAOverComplaintPDFID").value = document.getElementById("CivOverComplaintPDFID").value;
    }
    @foreach ($ways as $i => $w)
        if (!document.getElementById("{{ $waysFlds[$i] }}ID").checked && document.getElementById("Civ{{ $waysFlds[$i] }}ID").checked) {
            document.getElementById("{{ $waysFlds[$i] }}ID").checked = true;
        }
    @endforeach
    if (document.getElementById("OverComplaintWebFormID").value == "" && document.getElementById("CivOverComplaintWebFormID").value != "") {
        document.getElementById("OverComplaintWebFormID").value = document.getElementById("CivOverComplaintWebFormID").value;
    }
    */
    checkScore();
    return true;
}
function checkStar(checkboxID, starID)
{
    if (document.getElementById(checkboxID) && document.getElementById(starID))
    {
        if (document.getElementById(checkboxID).checked)
        {
            document.getElementById(starID).src='/openpolice/star1.png';
            document.getElementById(starID+'b').src='/openpolice/star1.png';
            document.getElementById(starID+'c').src='/openpolice/star1.png';
        }
        else
        {
            document.getElementById(starID).src='/openpolice/star1-gry.png';
            document.getElementById(starID+'b').src='/openpolice/star1-gry.png';
            document.getElementById(starID+'c').src='/openpolice/star1-gry.png';
        }
    }
    return true;
}
