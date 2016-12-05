<!-- Stored in resources/views/openpolice/complaint-staff-emails.blade.php -->

<div id="analystEmailer" class="row2 p20 round5 disBlo">
    <div class="p20 round5 brd">
        <form action="/dashboard/complaint/{{ $cID }}/emails/type" method="post" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="cID" value="{{ $cID }}">
        
        <div class="row">
            <div class="col-md-6">
                <h1>Complaint #{{ $cID }}: Sending Email</h1>
            </div>
            <div class="col-md-6 taR redDrk">
                <i>Current Complaint Status:</i>
                @if (isset($latestReview->ComRevStatus)) <h3 class="mT0">{{ $latestReview->ComRevStatus }}</h3> @endif
            </div>
        </div>
        
        <h3>1. Select which email template(s) you want to send right now.</h3>
        <div class="row mL20">
            <div class="col-md-4 pR20">
                <h2>Email To Complainant</h2>
                <select name="email1" class="form-control f18" autocomplete=off >
                    <option value="" > No email to complainant right now</option>
                    @forelse ($emailList as $i => $email)
                        @if ($email->ComEmailType == 'To Complainant')
                            <option value="{{ $email->ComEmailID }}"
                                @if (isset($latestReview->ComRevStatus) && isset($emailMap[$latestReview->ComRevStatus]) && sizeof($emailMap[$latestReview->ComRevStatus]) > 0 
                                    && in_array($email->ComEmailID, $emailMap[$latestReview->ComRevStatus]))
                                    SELECTED
                                @endif
                                >{{ $email->ComEmailName }}</option>
                        @endif
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-4 pR20">
                <h2>Email To Oversight Agency</h2>
                <select name="email2" class="form-control f18" autocomplete=off >
                    <option value="" > No email to oversight agency right now</option>
                    @forelse ($emailList as $i => $email)
                        @if ($email->ComEmailType == 'To Oversight')
                            <option value="{{ $email->ComEmailID }}"
                                @if (isset($latestReview->ComRevStatus) && isset($emailMap[$latestReview->ComRevStatus]) && sizeof($emailMap[$latestReview->ComRevStatus]) > 0 
                                    && in_array($email->ComEmailID, $emailMap[$latestReview->ComRevStatus]))
                                    SELECTED
                                @endif
                                >{{ $email->ComEmailName }}</option>
                        @endif
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-md-3 taR pT20 pR20">
                <input type="submit" class="btn btn-lg btn-primary f24 mT20" value="Load Templates">
            </div>
        </div>
        </form>
        
        <hr>
        @if ($viewType == 'emailsType' && ($email1 > 0 || $email2 > 0))
            
            <h3>2. Write custom messages within email template.</h3>
            
            <div class="pL20 mL10 f20">
                <form action="/dashboard/complaint/{{ $cID }}/emails/type" method="post" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="cID" value="{{ $cID }}">
                
                <style>
                textarea.emailEvalMsg { margin-top: 20px; font-size: 20px; height: 60px; }
                </style>
                <script type="text/javascript">
                $(document).ready(function () {
                    $(document).on("click", ".ComEmailTypeBtn", function() {
                        if ($(this).attr("id").indexOf("email1TypeBtn") >= 0) {
                            var ind = $(this).attr("id").replace("email1TypeBtn", "");
                            $("#email1TypeWrap"+ind+"").slideDown("fast");
                            $("#email1TypeBtn"+ind+"Wrap").slideUp("fast");
                        }
                        else {
                            var ind = $(this).attr("id").replace("email2TypeBtn", "");
                            $("#email2TypeWrap"+ind+"").slideDown("fast");
                            $("#email2TypeBtn"+ind+"Wrap").slideUp("fast");
                        }
                    });
                });
                </script>
                
                @if ($email1 > 0 && $e1 && isset($e1["rec"]) && $e1["rec"] !== false && sizeof($e1["rec"]) > 0)
                
                    <h2>To Complainant</h2>
                    <div class="pL20 mL20">
                        @if (sizeof($e1["splits"]) > 0)
                            @foreach ($e1["splits"] as $i => $split)
                                @if ($i > 0)
                                    <div id="email1TypeBtn{{ $i }}Wrap" class="disBlo">
                                        <a href="javascript:void(0)" id="email1TypeBtn{{ $i }}" class="ComEmailTypeBtn opac50 mT10 mB10 mL20 pT5 btn btn-xs btn-default"
                                        ><i class="fa fa-plus" aria-hidden="true"></i> <i class="fa fa-pencil-square-o f20" aria-hidden="true"></i></a>
                                    </div>
                                    <div id="email1TypeWrap{{ $i }}" class="disNon ComEmailTypeWrap">
                                        <textarea name="email1Type{{ $i }}" class="form-control emailEvalMsg" ></textarea>
                                    </div>
                                @endif
                                {!! $split !!}
                            @endforeach
                        @else
                        
                        @endif
                    </div>
                    
                @endif
    
                @if ($email2 > 0 && $e2 && isset($e2["rec"]) && $e2["rec"] !== false && sizeof($e2["rec"]) > 0)
                    
                    <h2>To Oversight Agency</h2>
                    <div class="pL20 mL20">
                        @if (sizeof($e2["splits"]) > 0)
                            @foreach ($e2["splits"] as $i => $split)
                                @if ($i > 0)
                                    <textarea name="email2Type{{ $i }}" class="form-control emailEvalMsg" ></textarea>
                                @endif
                                {!! $split !!}
                            @endforeach
                        @else
                        
                        @endif
                    </div>
                    
                @endif
                
                </form>
            </div>
            
        @else
            <h3 class="gry9">2. Write custom messages within email template.</h3>
        @endif
        
    </div>
</div>