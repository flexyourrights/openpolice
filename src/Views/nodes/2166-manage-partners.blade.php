<!-- resources/views/vendor/openpolice/nodes/2166-manage-partners.blade.php -->
<a href="/dashboard/start/partner-profile/?nv2074={{ $prtnType['defID'] }}" 
    class="btn btn-secondary btn-sm pull-right"
    ><i class="fa fa-plus-circle"></i> Add {{ $prtnType["sing"] }}</a>
<h2>Manage {{ $prtnType["plur"] }}</h2>
<div class="p10"><div class="row slGrey">
    <div class="col-md-1 taC">Active</div>
    <div class="col-md-4">{{ $prtnType["sing"] }} Name</div>
    <div class="col-md-3">
        Location @if ($prtnType["abbr"] == 'attorney') - Firm Name @endif
    </div>
    <div class="col-md-3">User</div>
    <div class="col-md-1">Edit</div>
</div></div>
@forelse ($partners as $i => $p)
    <div class="p10 @if ($i%2 == 0) row2 @endif ">
        <div class="row">
            <div class="col-md-1 col-sm-1 taC">
                @if (isset($p->part_status) && intVal($p->part_status) == 1)
                    <i class="fa fa-check" aria-hidden="true"></i>
                @endif 
            </div>
            <div class="col-md-4 col-sm-5">
                <a href="/org/{{ $p->part_slug }}">
                    @if (isset($p->prsn_nickname)) {{ $p->prsn_nickname }} 
                    @else <span class="slGrey">(empty)</span>
                    @endif
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                @if (isset($p->prsn_address_city)) {{ $p->prsn_address_city }}, @endif
                @if (isset($p->prsn_address_state)) {{ $p->prsn_address_state }} @endif
                @if ($prtnType["abbr"] == 'attorney') 
                    @if (isset($p->part_company_name)) - {{ $p->part_company_name }} @endif
                @endif
            </div>
            <div class="col-md-3 col-sm-11">
                @if (isset($p->prsn_email)) 
                    @if (isset($p->prsn_name_first) && trim($p->prsn_name_first) != ''
                        && isset($p->prsn_name_last) && trim($p->prsn_name_last) != '')
                        {{ $p->prsn_name_first }} {{ $p->prsn_name_last }},
                    @elseif (isset($p->prsn_name_first) && trim($p->prsn_name_first) != '')
                        {{ $p->prsn_name_first }},
                    @elseif (isset($p->prsn_name_last) && trim($p->prsn_name_last) != '')
                        {{ $p->prsn_name_last }},
                    @endif
                    <a href="mailto:{{ $p->prsn_email }}">{{ $p->prsn_email }}</a>
                @elseif (isset($p->name) && isset($p->email)) 
                    <a href="/profile/{{ $p->name }}">{{ $p->name }}</a>, 
                    <a href="mailto:{{ $p->email }}">{{ $p->email }}</a>
                @endif
            </div>
            <div class="col-md-1 col-sm-1">
                <div class="mBn5">
                    <a href="/dashboard/start-{{ $p->part_id }}/partner-profile"
                        class="btn btn-secondary btn-sm">
                        <i class="fa fa-pencil" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
</div>
@empty <div class="pT20"><i>No {{ $prtnType["plur"] }} Found.</i></div>
@endforelse