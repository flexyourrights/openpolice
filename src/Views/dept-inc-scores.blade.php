<!-- resources/views/vendor/openpolice/dept-inc-scores.blade.php -->
@if ($score && sizeof($score) > 0)
    @if (isset($twocol) && $twocol)
        @foreach ($score as $i => $s)
            <div class=" @if ($i%2 == 0) row2 @endif 
                @if ($s[2]) scoreRowOn @else scoreRowOff @endif">
                <div class="row" style="margin: -5px;">
                    <div class="col-2">
                        {{ $s[0] }} 
                        @if ($s[2])
                            <br /><i class="fa fa-check-circle mL3" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="col-10">{{ $s[1] }}</div>
                </div>
            </div>
        @endforeach
    @else
        @foreach ($score as $i => $s)
            @if ($s[2])
                <div class=" @if ($i%2 == 0) row2 @endif scoreRowOn"><div class="row">
                <div class="col-1"><i class="fa fa-check-circle mL5" aria-hidden="true"></i></div>
            @else
                <div class=" @if ($i%2 == 0) row2 @endif scoreRowOff"><div class="row">
                <div class="col-1">&nbsp;</div>
            @endif
                <div class="col-1 taR">{{ $s[0] }}</div>
                <div class="col-9">{{ $s[1] }}</div>
            </div></div>
        @endforeach
    @endif
@endif
