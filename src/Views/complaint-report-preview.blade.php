<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="slCard">
    <h3 class="mT0 slBlueDark">{!! $deptList !!}</h3>
    <div class="row slReportPreview">
        <div class="col-md-5">
            <div class="slGrey">Incident:</div><h4 class="mT0 mL10">{{ $comDate }}<br />{!! $comWhere !!}</h4>
            <div class="mT10 slGrey">Allegations:</div><h4 class="mT0 mL10">{!! $allegations !!}</h4>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-6 pB10 pT5">
            <p>{{ $storyPrev }}</p>
            <p><a href="/complaint/read-{{ $complaint->ComPublicID }}" class="btn btn-primary mT10"
                    >Read Complaint #{{ $complaint->ComPublicID }}</a></p>
        </div>
    </div>
</div>