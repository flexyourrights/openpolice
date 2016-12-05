<!-- Stored in resources/views/openpolice/complaint-report-what-hap.blade.php -->

<div class="reportBlock">
    <div class="row">
        <div class="col-md-4">
            <div class="f20 pB10">
                {!! $eventDesc !!}
            </div>
            <div class="pL10">
                @if (sizeof($allegs) > 0)
                    <div class="f16 gry6">Allegations:</div>
                    @foreach ($allegs as $alleg)
                        <div class="pB20">
                            <div class="f18"><b>{!! $alleg[1] !!}</b></div>
                            @if (!in_array($alleg[1], ['Miranda Rights', 'Officer Refused To Provide ID']))
                                <div class="pL20"><span class="mR5">Why?</span> {!! $alleg[2] !!}</div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <!-- <i class="gryA">No Related Allegations</i> -->
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <table class="table"><tr class="disNon"></tr>
            @forelse ($deets as $i => $deet)
                @if ($i == floor(sizeof($deets)/2)) 
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table"><tr class="disNon"></tr>
                @endif
                <tr><td>{!! $deet !!}</td></tr>
            @empty
            @endforelse
            </table>
        </div>
    </div>
</div>