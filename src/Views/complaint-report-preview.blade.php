<!-- Stored in resources/views/openpolice/complaint-report-preview.blade.php -->
<div class="pB20 mB20 pT20">
    <h3 class="mT0 slBlueDark"></h3>
    <div class="row slReportPreview">
        <div class="col-md-6 pB10 pT5">
            <p><span class="slGrey">Allegations</span><br /><b class="fPerc125">{!! $allegations !!}</b></p>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <p><span class="slGrey">Incident</span><br />
            {{ $comDate }}, {!! $comWhere !!}<br />{!! $deptList !!}</p>
        </div>
    </div>
    <p>{{ $storyPrev }}</p>
    <p><a href="/complaint/read-{{ $complaint->ComPublicID }}" class="btn btn-primary mT10"
        >Read Complaint #{{ $complaint->ComPublicID }}</a></p>
</div>