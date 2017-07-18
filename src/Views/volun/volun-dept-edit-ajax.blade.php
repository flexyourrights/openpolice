/* resources/views/vendor/openpolice/volun/volun-dept-edit-ajax.blade.php */

$("#navBtnPhone").click(function() {
    if (document.getElementById("rightSide")) {
        if (!document.getElementById("rightSide").className || document.getElementById("rightSide").className == 'disNon') showRightSide();
        else hideRightSide();
    }
});

function loadtab(newTab) {
    var tabList = ["Contact", "Web", "IA", "Over", "Save", "Edits", "Check", "FAQ"]; // , "Chklst", "Phone"
    for (var i=0; i<tabList.length; i++) {
        if (newTab.localeCompare(tabList[i]) == 0) {
            if (document.getElementById("curr"+tabList[i]+"")) document.getElementById("curr"+tabList[i]+"").style.display='inline';
            //else if (tabList[i] != 'Save') alert("not found: curr"+tabList[i]+"");
            if (document.getElementById("dept"+tabList[i]+"")) document.getElementById("dept"+tabList[i]+"").style.display='block';
            //else alert("not found: dept"+tabList[i]+"");
            //alert(tabList[i]+' matches '+document.getElementById("dept"+tabList[i]+"").style.display);
        }
        else {
            if (document.getElementById("curr"+tabList[i]+"")) document.getElementById("curr"+tabList[i]+"").style.display='none';
            //else if (tabList[i] != 'Save') alert("not found: curr"+tabList[i]+"");
            if (document.getElementById("dept"+tabList[i]+"")) document.getElementById("dept"+tabList[i]+"").style.display='none';
            //else alert("not found: dept"+tabList[i]+"");
            //alert(tabList[i]+' no match '+document.getElementById("dept"+tabList[i]+"").style.display);
        }
    }
    return true;
}
$("#navbarDept").click(function()         { loadtab('Contact'); });
$("#navBtnContact0").click(function()     { loadtab('Contact'); });
$("#navBtnContact").click(function()     { loadtab('Contact'); });
$("#navBtnWeb").click(function()         { loadtab('Web'); });
$("#navBtnIA").click(function()         { loadtab('IA'); });
$("#navBtnOver").click(function()         { loadtab('Over'); });
$("#navBtnSave").click(function()         { loadtab('Save'); });
$("#navBtnEdits").click(function()         { loadtab('Edits'); });
$("#navBtnCheck").click(function()         { loadtab('Check'); });
$("#navBtnFAQ").click(function()         { loadtab('FAQ'); });
loadtab('Contact');

$("#IAOverContactBtn").click(function() {
    document.getElementById("IAContactBtn").style.display="none";
    $("#IAOverContactForm").fadeIn("fast");
});
$("#CivOverContactBtn").click(function() {
    document.getElementById("CivContactBtn").style.display="none";
    $("#CivOverContactForm").fadeIn("fast");
});
$("#CivOversightBtn").click(function() {
    document.getElementById("CivOversightWrap").style.display="none";
    $("#CivOverForm").fadeIn("fast");
});

if (window.location.hash) {
    if (window.location.hash == '#over') loadtab('Over');
}
