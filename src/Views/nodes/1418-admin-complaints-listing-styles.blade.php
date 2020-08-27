<!-- resources/views/vendor/openpolice/nodes/1418-admin-complaints-listing-styles.blade.php -->
<style>

@if ($view == 'list')
body {
    overflow-x: hidden;
    overflow-y: hidden;
}
@endif
#hidivBtnAdmFoot {
    display: none;
}
.admDashTopCard {
    position: relative;
    margin-top: 5px;
    margin-bottom: 15px;
}
.admDashTopCard h4, .admDashTopCard .row .col-md-8 h4 {
    display: inline;
    margin: 5px 10px 0px 0px;
}
#admDashListView {
    margin-bottom: -40px;
}
.complaintRowWrp, .complaintRowWrpActive {
    position: relative;
    min-height: 75px;
    width: 100%;
}
.complaintRowWrp .resultSpin, .complaintRowWrpActive .resultSpin {
    position: absolute;
    right: 15px;
    top: 58px;
    color: #FFF;
    font-size: 80%;
}
a.complaintRowA:link .float-left, a.complaintRowA:visited .float-left, 
a.complaintRowA:active .float-left, a.complaintRowA:hover .float-left {
    padding-top: 15px;
}
a.complaintRowA:link .float-left.complaintAlert, a.complaintRowA:visited .float-left.complaintAlert, 
a.complaintRowA:active .float-left.complaintAlert, a.complaintRowA:hover .float-left.complaintAlert {
    padding-left: 15px;
}
a.complaintRowA:link .float-right, a.complaintRowA:visited .float-right, 
a.complaintRowA:active .float-right, a.complaintRowA:hover .float-right {
    padding-top: 15px;
    padding-right: 15px;
    text-align: right;
}
.complaintRowWrp a.complaintRowA:link, .complaintRowWrp a.complaintRowA:visited,
.complaintRowWrp a.complaintRowA:active, .complaintRowWrp a.complaintRowA:hover,
.complaintRowWrpActive a.complaintRowA:link, .complaintRowWrpActive a.complaintRowA:visited,
.complaintRowWrpActive a.complaintRowA:active, .complaintRowWrpActive a.complaintRowA:hover {
    position: absolute;
    display: block;
    width: 100%;
    min-height: 75px;
    text-decoration: none;
    color: #416CBD;
    background: #FFF;
    border-top: 1px #CCC solid;
}
.complaintRowWrp a.complaintRowA:hover {
    background: #F5FBFF;
}
.complaintRowWrpActive a.complaintRowA:link, .complaintRowWrpActive a.complaintRowA:visited, .complaintRowWrpActive a.complaintRowA:active, .complaintRowWrpActive a.complaintRowA:hover {
    color: #F5FBFF;
    background: #416CBD;
}
.complaintRowWrpActive a.complaintRowA:hover {
    color: #FFF;
}
.complaintRowWrpActive a.complaintRowA:link .slGrey, .complaintRowWrpActive a.complaintRowA:visited .slGrey, .complaintRowWrpActive a.complaintRowA:active .slGrey, .complaintRowWrpActive a.complaintRowA:hover .slGrey {
    color: #AAA;
}
.complaintAlert {
    width: 30px;
    min-width: 30px;
    max-width: 30px;
}
.complaintAlert .litRedDot, .complaintAlert .litRedDottie {
    margin-top: -16px;
}
.complaintRowWrp a.complaintRowFull:link, .complaintRowWrp a.complaintRowFull:visited, 
.complaintRowWrp a.complaintRowFull:active, .complaintRowWrp a.complaintRowFull:hover,
.complaintRowWrpActive a.complaintRowFull:link, .complaintRowWrpActive a.complaintRowFull:visited, 
.complaintRowWrpActive a.complaintRowFull:active, .complaintRowWrpActive a.complaintRowFull:hover {
    position: absolute;
    top: 15px;
    right: 15px;
}
.complaintRowWrpActive a.complaintRowFull:link, .complaintRowWrpActive a.complaintRowFull:visited, .complaintRowWrpActive a.complaintRowFull:active, .complaintRowWrpActive a.complaintRowFull:hover {
    color: #FFF;
}

</style>