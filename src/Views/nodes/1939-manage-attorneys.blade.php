<!-- resources/views/vendor/openpolice/nodes/1939-manage-attorneys.blade.php -->
<p>&nbsp;</p>
<a href="/dashboard/start/partner-profile/?nv2074={{ $prtnType['defID'] }}" class="btn btn-primary pull-right"
    ><i class="fa fa-plus-circle"></i> Add {{ $prtnType["sing"] }}</a>
<h2>Manage {{ $prtnType["plur"] }}</h2>
<div class="p10"><div class="row">
    <div class="col-md-4"><b>{{ $prtnType["sing"] }} Name</b></div>
    <div class="col-md-4"><b>Location @if ($prtnType["abbr"] == 'attorney') - Firm Name @endif </b></div>
    <div class="col-md-4"><b>User</b></div>
</div></div>
@forelse ($partners as $i => $p)
    <div class="p10 @if ($i%2 == 0) row2 @endif "><div class="row">
        <div class="col-md-4">
            <a href="/dashboard/start-{{ $p->PartID }}/partner-profile">
                @if (isset($p->PrsnNickname)) {{ $p->PrsnNickname }} 
                @else <span class="slGrey">(empty)</span> @endif </a>
        </div>
        <div class="col-md-4">
            @if (isset($p->PrsnAddressState)) {{ $p->PrsnAddressState }} @endif
            @if (isset($p->PrsnAddressCity)) {{ $p->PrsnAddressCity }} @endif
            @if ($prtnType["abbr"] == 'attorney') 
                @if (isset($p->PartCompanyName)) - {{ $p->PartCompanyName }} @endif
            @endif
        </div>
        <div class="col-md-4">
            @if (isset($p->name) && isset($p->email)) 
                <a href="/profile/{{ $p->name }}" class="fPerc80">{{ $p->name }}</a>, 
                <a href="mailto:{{ $p->email }}" class="fPerc80">{{ $p->email }}</a>
            @endif
        </div>
    </div></div>
@empty <div class="pT20"><i>No {{ $prtnType["plur"] }} Found.</i></div>
@endforelse