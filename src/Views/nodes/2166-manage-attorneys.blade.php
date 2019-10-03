<!-- resources/views/vendor/openpolice/nodes/2166-manage-attorneys.blade.php -->
<a href="/dashboard/start/partner-profile/?nv2074={{ $prtnType['defID'] }}" class="btn btn-primary pull-right"
    ><i class="fa fa-plus-circle"></i> Add {{ $prtnType["sing"] }}</a>
<h2>Manage {{ $prtnType["plur"] }}</h2>
<div class="p10"><div class="row slGrey">
    <div class="col-md-4">{{ $prtnType["sing"] }} Name</div>
    <div class="col-md-3">
        Location @if ($prtnType["abbr"] == 'attorney') - Firm Name @endif
    </div>
    <div class="col-md-5">User</div>
</div></div>
@forelse ($partners as $i => $p)
    <div class="p10 @if ($i%2 == 0) row2 @endif "><div class="row">
        <div class="col-md-4">
            <a href="/dashboard/start-{{ $p->PartID }}/partner-profile">
                @if (isset($p->PrsnNickname)) {{ $p->PrsnNickname }} 
                @else <span class="slGrey">(empty)</span> @endif </a>
        </div>
        <div class="col-md-3">
            @if (isset($p->PrsnAddressCity)) {{ $p->PrsnAddressCity }}, @endif
            @if (isset($p->PrsnAddressState)) {{ $p->PrsnAddressState }} @endif
            @if ($prtnType["abbr"] == 'attorney') 
                @if (isset($p->PartCompanyName)) - {{ $p->PartCompanyName }} @endif
            @endif
        </div>
        <div class="col-md-5">
            @if (isset($p->PrsnEmail)) 
                @if (isset($p->PrsnNameFirst) && trim($p->PrsnNameFirst) != ''
                    && isset($p->PrsnNameLast) && trim($p->PrsnNameLast) != '')
                    {{ $p->PrsnNameFirst }} {{ $p->PrsnNameLast }},
                @elseif (isset($p->PrsnNameFirst) && trim($p->PrsnNameFirst) != '')
                    {{ $p->PrsnNameFirst }},
                @elseif (isset($p->PrsnNameLast) && trim($p->PrsnNameLast) != '')
                    {{ $p->PrsnNameLast }},
                @endif
                <a href="mailto:{{ $p->PrsnEmail }}">{{ $p->PrsnEmail }}</a>
            @elseif (isset($p->name) && isset($p->email)) 
                <a href="/profile/{{ $p->name }}">{{ $p->name }}</a>, 
                <a href="mailto:{{ $p->email }}">{{ $p->email }}</a>
            @endif
        </div>
    </div></div>
@empty <div class="pT20"><i>No {{ $prtnType["plur"] }} Found.</i></div>
@endforelse