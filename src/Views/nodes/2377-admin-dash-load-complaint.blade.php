<!-- resources/views/vendor/openpolice/nodes/2377-admin-dash-load-complaint.blade.php -->
@if ($coreID > 0)
    <div id="admDashReportWrap" class="w100 h100">
        <div class="pT30 pB30">{!! $GLOBALS["SL"]->spinner() !!}</div>
    </div>
    <style> 
    body, #admDashReportWrap, #mainBody {
        overflow-y: visible; 
    }
    #mainBody {
        padding-bottom: 70px;
    }
    #hidivBtnAdmFoot {
        display: none; 
    }
    </style>
@else
    <div class="pT20 pB20 mT10 mB10">
        <i>Complaint Not Found</i>
    </div>
@endif
