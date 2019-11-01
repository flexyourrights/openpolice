<!-- resources/views/vendor/openpolice/inc-documentation-navigation.blade.php -->

<h4 class="slBlueDark">Software Documentation</h4>

@forelse ($docuNav as $i => $category)
    <div class="pT15"><b>{{ $category[0] }}</b></div>
    @forelse ($category[1] as $j => $nav)
        <div class="pT5 pB5 pL15">
            <a href="{{ $nav[0] }}" 
                @if ($currPage == $nav[0]) class="bld slBlueDark" @endif
                >{!! $nav[1] !!}</a>
        @if (sizeof($nav) > 2 && is_array($nav[2]) && sizeof($nav[2]) == 2)
            <div class="pT5 pB5 pL15"><a href="{{ $nav[2][0] }}">{!! $nav[2][1] !!}</a></div>
        @endif
        </div>
    @empty
    @endforelse
@empty
@endforelse

<div class="mT20">
    <a href="https://github.com/flexyourrights/openpolice" target="_blank"
        class="btn btn-secondary btn-block taL"><nobr><i class="fa fa-github mR5" aria-hidden="true"></i> 
        flexyourrights/openpolice</nobr></a>
</div>
