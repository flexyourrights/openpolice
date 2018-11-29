<?php 
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SLDefinitions;

use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPPersonContact;
use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;
use App\Models\OPzVolunTmp;
use App\Models\OPzVolunUserInfo;

use OpenPolice\Controllers\VolunteerLeaderboard;
use OpenPolice\Controllers\OpenPoliceAdmin;

class VolunteerController extends OpenPoliceAdmin
{
    
    private $indexFlds = "`DeptID`, `DeptName`, `DeptSlug`, `DeptAddressCity`, `DeptAddressState`, "
        . "`DeptVerified`, `DeptScoreOpenness`";
    
    protected function tweakAdmMenu($currPage = '')
    {
        $this->v["ways"] = [
            'Online-Submittable Form', 
            'Submit via Email Allowed', 
            'Verbally on Phone Allowed', 
            'Paper Form via Snail Mail Allowed', 
            'Requires In-Person Visit', 
            'Official Form NOT Required for Investigation', 
            'Anonymous Complaints Investigated', 
            'Requires Notary (for any type of complaint)', 
        ];
        $this->v["waysFlds"] = [
            'OverWaySubOnline', 
            'OverWaySubEmail', 
            'OverWaySubVerbalPhone', 
            'OverWaySubPaperMail', 
            'OverWaySubPaperInPerson', 
            'OverOfficialFormNotReq', 
            'OverOfficialAnon', 
            'OverWaySubNotary', 
        ];
        $this->v["wayPoints"] = [30, 15, 3, 2, 0, 15, 15, -10];
        $this->v["deptPoints"] = [
            "Website"              => 5, 
            "FB"                   => 5, 
            "Twit"                 => 5, 
            "YouTube"              => 5, 
            "ComplaintInfo"        => 20, 
            "ComplaintInfoHomeLnk" => 15, 
            "FormPDF"              => 15
        ];
        
        
    	if (isset($this->v["deptSlug"])) {
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                $this->admMenuData["currNavPos"] = [2, 3, -1, -1];
            } else {
                $this->admMenuData["currNavPos"] = [1, -1, -1, -1];
            }
            $volunteeringSubMenu = [
                'javascript:;" id="navBtnContact0',
                '<b>Verifying Department</b>',
                1,
                [
                    [
                        'javascript:;" id="navBtnContact',
                        '&nbsp;&nbsp;Contact Info <div id="currContact" class="disIn float-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                    ], [
                        'javascript:;" id="navBtnWeb',
                        '&nbsp;&nbsp;Web & Complaints <div id="currWeb" class="disNon float-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                    ], [
                        'javascript:;" id="navBtnIA',
                        '&nbsp;&nbsp;Internal Affairs <div id="currIA" class="disNon float-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                    ], [
                        'javascript:;" id="navBtnOver',
                        '&nbsp;&nbsp;Civilian Oversight <div id="currOver" class="disNon float-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                    ], [
                        'javascript:;" id="navBtnSave',
                        '<span class="btn btn-lg btn-primary">Save All Changes <i class="fa fa-floppy-o"></i></span>',
                        1,
                    ], [
                        'javascript:;" id="navBtnEdits',
                        '<span class="gry9">&nbsp;&nbsp;Past Edits:</span> ' 
                            . ((isset($this->v["editsSummary"][1])) ? $this->v["editsSummary"][1]: '') 
                            . '<div id="currEdits" class="disNon float-right mL20">'
                            . '<i class="fa fa-chevron-right"></i></div>',
                        1,
                    ], [
                        'javascript:;" id="navBtnCheck',
                        '<span class="gry9">&nbsp;&nbsp;Volunteer Checklist</span> <div id="currCheck" '
                            . 'class="disNon float-right mL20"><i class="fa fa-chevron-right"></i></div>',
                        1,
                    ], [
                        'javascript:;" id="navBtnPhone',
                        '<span class="gry9">&nbsp;&nbsp;Sample Phone Script</span>',
                        1,
                    ], [
                        'javascript:;" id="navBtnFAQ',
                        '<span class="gry9">&nbsp;&nbsp;Frequently Asked <i class="fa fa-question"></i>s</span> <div '
                            . 'id="currFAQ" class="disNon float-right mL20"><i class="fa fa-chevron-right"></i></div>',
                        1,
                    ]
                ]
            ];
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                //$this->admMenuData["adminNav"][2][3][3] = $volunteeringSubMenu;
            } else { // is Volunteer
                //$volunteeringSubMenu[1] .= ' <span class="float-right"><i class="fa fa-check"></i></span>';
                //$this->admMenuData["adminNav"][1] = $volunteeringSubMenu;
            }
        }
        if (!$this->v["user"]->hasRole('administrator|staff|databaser')) {
            $score = 0;
            if (isset($this->v["yourUserInfo"]->UserInfoStars)) {
                $score = intVal($this->v["yourUserInfo"]->UserInfoStars);
            }
            $this->admMenuData["adminNav"][2][1] = str_replace('[[score]]', $score, 
                $this->admMenuData["adminNav"][2][1]);
        }
        return true;
    }
    
    protected function genDeptAdmTopMenu($deptRow)
    {
        return '<ul class="nav navbar-nav">
            <li class="active"><a id="navbarDept" class="f26" style="background: none;" href="javascript:;" 
            title="Verifying Department: ' . $deptRow->DeptName . '">Verify: ' 
            . str_replace('Department', 'Dept', $deptRow->DeptName) . ' 
            <span class="f10 mL10">' . $deptRow->DeptAddressCity . ', ' 
            . $deptRow->DeptAddressState . '</span></a></li>
        </ul>';
    }
    
    protected function getNextDept()
    {
        $this->v["nextDept"] = array(0, '', '');
        $recentDate = date("Y-m-d H:i:s", time(date("H")-6, date("i"), date("s"), date("n"), date("j"), date("Y")));
        OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpDate', '<', $recentDate)
            ->delete();
        // First check for department temporarily reserved for this user
        $tmpReserve = OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpUser', Auth::user()->id)
            ->first();
        if ($tmpReserve && isset($tmpReserve->TmpVal) && intVal($tmpReserve->TmpVal) > 0) {
            $nextRow = OPDepartments::where('DeptID', $tmpReserve->TmpVal)
                ->first();
            $this->v["nextDept"] = [
                $nextRow->DeptID,
                $nextRow->DeptName,
                $nextRow->DeptSlug
            ];
        } else { // no department reserved yet, find best next choice...
            $nextRow = NULL;
            $qmen = [];
            $qBase = "SELECT `DeptID`, `DeptName`, `DeptSlug` FROM `OP_Departments` WHERE ";
            $qReserves = " AND `DeptID` NOT IN (SELECT `TmpVal` FROM `OP_zVolunTmp` WHERE "
                . "`TmpType` LIKE 'ZedDept' AND `TmpUser` NOT LIKE '" . Auth::user()->id . "')";
            $qmen[] = $qBase . "(`DeptVerified` < '2015-01-01 00:00:00' OR `DeptVerified` IS NULL) " 
                . $qReserves . " ORDER BY RAND()";
            $qmen[] = $qBase . "1 " . $qReserves . " ORDER BY `DeptVerified`";
            $qmen[] = $qBase . "1 ORDER BY RAND()"; // worst-case backup
            for ($i = 0; ($i < sizeof($qmen) && !$nextRow); $i++) {
                $nextRow = DB::select( DB::raw( $qmen[$i]." LIMIT 1" ) );
            }
            $this->v["nextDept"] = [
                $nextRow[0]->DeptID, 
                str_replace('Department', 'Dept', $nextRow[0]->DeptName), 
                $nextRow[0]->DeptSlug
            ];
            
            // Temporarily reserve this department for this user
            $newTmp = new OPzVolunTmp;
            $newTmp->TmpUser = Auth::user()->id;
            $newTmp->TmpDate = date("Y-m-d H:i:s");
            $newTmp->TmpType = 'ZedDept';
            $newTmp->TmpVal  = $this->v["nextDept"][0];
            $newTmp->save();
        }
        return $this->v["nextDept"];
    }
    
    public function getVolunEditsOverview()
    {
        $retArr = $userTots = $uNames = [];
        $userEdits = DB::table('OP_Zedit_Departments')
            ->join('users', 'users.id', '=', 'OP_Zedit_Departments.ZedDeptUserID')
            ->select('users.id', 'users.name', 'OP_Zedit_Departments.ZedDeptDeptID')
            ->get();
        if ($userEdits->isNotEmpty()) {
            foreach ($userEdits as $row) {
                if (!isset($userTots[$row->id])) $userTots[$row->id] = [];
                if (!in_array($row->DeptID, $userTots[$row->id])) {
                    $userTots[$row->id] = array($row->ZedDeptDeptID);
                }
                if (!isset($uNames[$row->id])) {
                    $uNames[$row->id] = '<a href="/admin/user/' . $row->id . '">' . $row->name . '</a>';
                }
            }
        }
        foreach ($userTots as $u => $d) $userTots[$u] = sizeof($d);
        arsort($userTots);
        foreach ($userTots as $u => $d) $retArr = array($uNames[$u], $d);
        return $retArr;
    }
    
    public function index(Request $request)
    {
        $this->admControlInit($request, '/dashboard/volunteer');
        $this->v["viewType"] = 'priority';
        $this->v["deptRows"] = [];
        $this->v["searchForm"] = $this->deptSearchForm();
        $qman = " `DeptVerified` > '2015-01-01 00:00:00' ";
        $this->v["deptRows"] = DB::select( DB::raw("SELECT * FROM `OP_Departments` WHERE " . $qman 
            . " ORDER BY `DeptScoreOpenness` DESC, `DeptVerified` DESC, `DeptName`, `DeptAddressState`") );
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard()
            . '<div class="taC p10 f16 gry9"><i>' . $GLOBALS["SL"]->dbRow->DbMission . '</i></div>';
        $GLOBALS["SL"]->pageAJAX .= '$("#newDeptBtn").click(function() { $("#newDeptForm").slideToggle("fast"); }); ';
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunteer', $this->v)->render();
    }
    
    public function indexAll(Request $request)
    {
        $this->admControlInit($request, '/dashboard/volunteer');
        $this->v["viewType"] = 'all';
        $this->v["deptRows"] = [];
        $this->v["searchForm"] = $this->deptSearchForm();
        $this->v["deptRows"] = OPDepartments::orderBy('DeptName', 'asc')->paginate(50);
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard();
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunteer', $this->v)->render();
    }
    
    protected function deptSearchForm($state = '', $deptName = '')
    {
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunEditSearch', [ 
            "deptName"  => $deptName, 
            "stateDrop" => $GLOBALS["SL"]->states->stateDrop($state) 
        ])->render();
    }
    
    public function deptIndexSearch($deptRows = [], $state = '', $deptName = '')
    {
        $this->v["viewType"] = 'search';
        $this->v["deptRows"] = $deptRows;
        $this->v["userTots"] = $this->getVolunEditsOverview();
        $this->v["searchForm"] = str_replace('<select', '<div class="p5"><select', 
            str_replace('class="w33"', 'class="w33 f22"', $this->deptSearchForm($state, $deptName) )) . '</div>';
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard();
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunteer', $this->v)->render();
    }
    
    public function deptIndexSearchS(Request $request, $state = '')
    {
        $this->admControlInit($request, '/dashboard/volunteer');
        $deptRows = OPDepartments::where('DeptAddressState', '=', $state)
            ->orderBy('DeptName', 'asc')
            ->paginate(50);
        return $this->deptIndexSearch($deptRows, $state, '');
    }
    
    public function deptIndexSearchD(Request $request, $deptName = '')
    {
        $this->admControlInit($request, '/dashboard/volunteer');
        $this->v["deptName"] = '';
        if (trim($deptName) != '') $this->v["deptName"] = $deptName;
        return $this->deptIndexSearch($this->processSearchDepts('', $deptName), '', $deptName);
    }
    
    public function deptIndexSearchSD(Request $request, $state = '', $deptName = '')
    {
        $this->admControlInit($request, '/dashboard/volunteer');
        $this->v["deptName"] = '';
        if (trim($deptName) != '') $this->v["deptName"] = $deptName;
        return $this->deptIndexSearch($this->processSearchDepts($state, $deptName), $state, $deptName);
    }
    
    protected function processSearchDepts($state = '', $deptName = '')
    {
        $deptName = str_replace('  ', ' ', str_replace('  ', ' ', str_replace('  ', ' ', $deptName)));
        $searches = ['%'.$deptName.'%'];
        if (strpos($deptName, ' ') !== false) {
            $words = explode(' ', $deptName);
            foreach ($words as $w) {
                if (!in_array(strtolower($w), ['city', 'county', 'sherrif\'s', 'police', 'department', 'dept'])) {
                    $searches = '%'.$w.'%';
                }
            }
        }
        $deptRows = [];
        $evalQry = "\$deptRows = App\\Models\\OPDepartments::"
            . ((trim($state) != '') ? "where('DeptAddressState', '=', \$state)->" : "")
            . "where(function(\$query) { return \$query->where('DeptName', 'LIKE', '" 
            . addslashes($searches[0]) . "')";
            for ($i = 1; $i < sizeof($searches); $i++) {
                $evalQry .= "->orWhere('DeptName', 'LIKE', '" . addslashes($searches[$i]) . "')";
            }
        $evalQry .= "; })->orderBy('DeptName', 'asc')->paginate(50);";
        eval($evalQry);
        return $deptRows;
    }
    
    
    public function nextDept()
    {
        $this->getNextDept();
        return $this->redir('/dashboard/volunteer/verify/'.$this->v["nextDept"][2]);
    }
    
    
    public function newDept(REQUEST $request)
    {
        if ($request->has('deptName') && $request->has('DeptAddressState')) {
            $newDept = $this->newDeptAdd($request->deptName, $request->DeptAddressState);
            return $this->redir('/dashboard/volunteer/verify/' . $newDept->DeptSlug);
        }
        return $this->redir('/dashboard/volunteer');
    }
    
    public function newDeptAdd($deptName = '', $deptState = '') {
        if (trim($deptName) != '' && trim($deptState) != '') {
            $newDept = OPDepartments::where('DeptName', $deptName)->where('DeptAddressState', $deptState)->first();
            if ($newDept && isset($newDept->DeptSlug)) return $this->redir('/dashboard/volunteer/verify/'.$newDept->DeptSlug);
            $newDept = new OPDepartments;
            $newIA   = new OPOversight;
            $newEdit = new OPZeditDepartments;
            $iaEdit  = new OPZeditOversight;
            $newIA->OverType            = $iaEdit->ZedOverOverType          = 303;
            $newDept->DeptName          = $newEdit->ZedDeptName         = $deptName;
            $newDept->DeptAddressState  = $newEdit->ZedDeptAddressState = (($deptState != 'US') ? $deptState : '');
            $newDept->DeptSlug          = $newEdit->ZedDeptSlug         = $deptState . '-' . Str::slug($deptName);
            $newDept->DeptType          = $newEdit->ZedDeptType         = (($deptState == 'US') ? 366 : 0);
            $newDept->DeptStatus        = 1;
            $newDept->save();
            $newIA->OverDeptID          = $newEdit->ZedDeptDeptID       = $iaEdit->ZedOverDeptID = $newDept->DeptID;
            $newIA->save();
            $iaEdit->ZedOverOverID     = $newIA->OverID;
            $newEdit->ZedDeptUserID      = $iaEdit->ZedOverUser          = Auth::user()->id;
            $newEdit->ZedDeptDeptVerified  = $iaEdit->ZedOverOverVerified      = date("Y-m-d H:i:s");
            $newEdit->save();
            $iaEdit->ZedOverZedDeptID = $newEdit->ZedDeptID;
            $iaEdit->ZedOverNotes      = 'NEW DEPARTMENT ADDED TO DATABASE!';
            $iaEdit->save();
            return $newDept;
        }
        return '';
    }
    
    
    public function deptEdit(REQUEST $request, $deptSlug)
    {
    	$this->loadDbLookups($request);
        $this->v["deptSlug"]    = $deptSlug;
        $this->v["deptRow"]     = OPDepartments::where('DeptSlug', $deptSlug)->first();
        $this->v["editsIA"]     = $this->v["editsCiv"] = $this->v["userEdits"] = $this->v["userNames"] = [];
        $this->v["editTots"]    = ["notes" => 0, "online" => 0, "callDept" => 0, "callIA" => 0];
        $this->v["user"]        = Auth::user();
        $this->v["neverEdited"] = false;
        $this->v["recentEdits"] = '';
        
        if (!isset($this->v["deptRow"]->DeptID) || intVal($this->v["deptRow"]->DeptID) <= 0) {
            return $this->redir('/dashboard/volunteer');
        }
        
        $recentEdits = OPZeditDepartments::where('ZedDeptDeptID', $this->v["deptRow"]->DeptID)
            ->orderBy('ZedDeptDeptVerified', 'desc')
            ->get();
        if ($recentEdits->isNotEmpty()) {
            foreach ($recentEdits as $i => $edit) {
                $this->v["editsIA"][$i]  = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', 303)
                    ->first();
                $this->v["editsCiv"][$i] = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', 302)
                    ->first();
                if ($this->v["editsIA"][$i]) {
                    if (trim($this->v["editsIA"][$i]->ZedOverNotes) != '') {
                        $this->v["editTots"]["notes"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]->ZedOverOnlineResearch) == 1) {
                        $this->v["editTots"]["online"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]->ZedOverMadeDeptCall) == 1) {
                        $this->v["editTots"]["callDept"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]->ZedOverMadeIACall) == 1) {
                        $this->v["editTots"]["callIA"]++;
                    }
                }
                if (!isset($this->v["userNames"][$edit->ZedDeptUserID])) {
                    $this->v["userNames"][$edit->ZedDeptUserID] = User::find($edit->ZedDeptUserID)
                        ->printUsername(true, '/dashboard/volun/user/');
                }
                if ($this->v["user"]->hasRole('administrator|staff')) {
                    $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                        "user"     => $this->v["userNames"][$edit->ZedDeptUserID], 
                        "deptRow"  => $this->v["deptRow"], 
                        "deptEdit" => $edit, 
                        "deptType" => $GLOBALS["SL"]->def->getVal('Department Types', $edit->DeptType),
                        "iaEdit"   => $this->v["editsIA"][$i], 
                        "civEdit"  => $this->v["editsCiv"][$i]
                    ])->render();
                }
            }
        } else {
            $this->v["neverEdited"] = true;
        }
        $this->loadDeptEditsSummary();
        $this->admControlInit($request, '/dashboard/volunteer/verify');
        if (!$request->session()->has('whatNext')) $request->session()->put('whatNext', 'another');
        $this->getNextDept();
        $this->v["whatNext"]             = $request->session()->get('whatNext');
        $this->v["volunChecklist"]       = $GLOBALS["SL"]->getBlurbAndSwap('Volunteer Checklist');
        $this->v["FAQs"]                 = $GLOBALS["SL"]->getBlurb('Volunteer Data Mining FAQs');
        $this->v["rightSide"]            = $this->getSidebarScript();
        $GLOBALS["SL"]->loadStates();
        $this->v["stateDrop"]            = $GLOBALS["SL"]->states->stateDrop($this->v["deptRow"]->DeptAddressState);
        $this->v["iaRow"]                = OPOversight::where('OverDeptID', $this->v["deptRow"]->DeptID)
                                             ->where('OverType', 303)
                                             ->first();
        if (!$this->v["iaRow"]) {
            $this->v["iaRow"]            = new OPOversight;
            $this->v["iaRow"]->OverType  = 303; // definition ID for Internal Affairs
        }
        $this->v["civRow"]  = OPOversight::where('OverDeptID', $this->v["deptRow"]->DeptID)
            ->where('OverType', 302)
            ->first();
        if (!$this->v["civRow"]) {
            $this->v["civRow"]           = new OPOversight;
            $this->v["civRow"]->OverType = 302; // definition ID for Civilian Oversight
        }
        $this->v["iaForms"]              = $this->deptEditPrintOver($this->v["iaRow"]);
        $this->v["civForms"]             = $this->deptEditPrintOver($this->v["civRow"], 'Civ');
        $this->v["iaComplaints"]         = $this->deptEditPrintOverComplaints($this->v["deptRow"], $this->v["iaRow"]);
        $this->v["admTopMenu"]           = $this->genDeptAdmTopMenu($this->v["deptRow"]);
        $this->v["deptTypes"]            = SLDefinitions::where('DefSet', 'Value Ranges')
                                             ->where('DefSubset', 'Department Types')
                                             ->orderBy('DefOrder')
                                             ->get();
        $GLOBALS["SL"]->pageJAVA .= view('vendor.openpolice.volun.volun-dept-edit-java', $this->v)->render();
        $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.volun.volun-dept-edit-ajax', $this->v)->render();
        return view('vendor.openpolice.volun.volun-dept-edit', $this->v)->render();
    }
    
    public function deptEditPrintOver($overRow = [], $overType = 'IA') 
    {
        $alreadyHascontact = ((isset($overRow->OverNameFirst) && trim($overRow->OverNameFirst) != '')
            || (isset($overRow->OverNameLast) && trim($overRow->OverNameLast) != '') 
            || (isset($overRow->OverTitle) && trim($overRow->OverTitle) != ''));
        return view('vendor.openpolice.volun.inc-oversightEdit', [
            'overRow' => $overRow, 
            'overType' => $overType, 
            'DeptName' => $this->v["deptRow"]->DeptName, 
            'stateDrop' => $this->v["stateDrop"], 
            'alreadyHascontact' => $alreadyHascontact, 
            'neverEdited' => $this->v["neverEdited"]
        ])->render();
    }
    
    public function deptEditPrintOverComplaints($deptRow = [], $overRow = [], $overType = 'IA') 
    {
        $waysChecked = [];
        foreach ($this->v["ways"] as $i => $w) {
            eval("\$waysChecked[\$i] = ((isset(\$overRow->" . $this->v["waysFlds"][$i] 
                . ") && \$overRow->" . $this->v["waysFlds"][$i] . " == 1) ? true : false);");
        }
        return view('vendor.openpolice.volun.inc-oversightComplaints', [
            'deptRow' => $deptRow, 
            'overRow' => $overRow, 
            'overType' => $overType, 
            'DeptName' => $this->v["deptRow"]->DeptName, 
            'ways' => $this->v["ways"], 
            'waysFlds' => $this->v["waysFlds"], 
            'wayPoints' => $this->v["wayPoints"], 
            'waysChecked' => $waysChecked, 
            'deptPoints' => $this->v["deptPoints"], 
            'neverEdited' => $this->v["neverEdited"]
        ])->render();
    }
    
    public function loadDeptEditsSummary()
    {
        $this->v["editsSummary"] = ['<b>Last Verified: ', ''];
        $this->v["editsSummary"][0] .= (($this->v["neverEdited"]) 
            ? date('n/j', strtotime($this->v["deptRow"]->DeptVerified)) : 'Never') 
        . '</b> &nbsp;&nbsp;&nbsp;'
            . '<nobr>' . intVal($this->v["editTots"]["online"]) 
            . '<span class="f10">x</span><i class="fa fa-laptop"></i> Online Research,</nobr><br />'
            . '<nobr>' . intVal($this->v["editTots"]["callDept"]) 
            . '<span class="f10">x</span><i class="fa fa-phone"></i> Department Calls,</nobr> '
            . '&nbsp;&nbsp;&nbsp;<nobr><span class="slBlueDark">' . intVal($this->v["editTots"]["callIA"]) 
            . '<span class="f10">x</span><i class="fa fa-phone slBlueDark"></i> '
            . 'Internal Affairs Calls</span></nobr>';
        $this->v["editsSummary"][1] = intVal($this->v["editTots"]["online"]) 
            . '<i class="fa fa-laptop"></i>, ' . intVal($this->v["editTots"]["callDept"]) 
            . '<i class="fa fa-phone"></i>, <span class="slBlueDark">' 
            . intVal($this->v["editTots"]["callIA"]) . '<i class="fa fa-phone slBlueDark"></i></span>';
        return true;
    }
    
    public function deptEditSave(Request $request, $deptSlug = '') 
    {
        $this->v["deptSlug"] = $deptSlug;
        $this->v["deptRow"] = OPDepartments::find($request->DeptID);
        $this->admControlInit($request, '/dashboard/volunteer/verify');
        
        $ia = $civ = $deptEdit = $iaEdit = $civEdit = [];
        
        $this->v["deptRow"] = OPDepartments::find($request->DeptID);
        $deptEdit = new OPZeditDepartments;
        if (!isset($request->OverID) || intVal($request->OverID) <= 0) {
            $ia = new OPOversight;
            $ia->OverDeptID = $request->DeptID;
            $ia->OverType = 303;
        } else {
            $ia = OPOversight::find($request->OverID);
        }
        $iaEdit = new OPZeditOversight;
        
        $deptEdit->ZedDeptDeptID                          = $iaEdit->ZedOverDeptID                         = $request->DeptID;
        $deptEdit->ZedDeptUserID                            = $iaEdit->ZedOverUser                           = Auth::user()->id;
        $deptEdit->ZedDeptDuration                        = time()-intVal($request->formLoaded);
        $iaEdit->ZedOverOverType                              = 303;
        
        $this->v["deptRow"]->DeptVerified                  = $deptEdit->ZedDeptDeptVerified                 = date("Y-m-d H:i:s");
        $this->v["deptRow"]->DeptName                      = $deptEdit->ZedDeptName                         = $request->DeptName;
        $this->v["deptRow"]->DeptSlug                      = $deptEdit->ZedDeptSlug                         = $request->DeptSlug;
        $this->v["deptRow"]->DeptType                      = $deptEdit->ZedDeptType                         = intVal($request->DeptType);
        $this->v["deptRow"]->DeptStatus                    = $deptEdit->ZedDeptStatus                       = $request->DeptStatus;
        $this->v["deptRow"]->DeptAddress                   = $deptEdit->ZedDeptAddress                      = $request->DeptAddress;
        $this->v["deptRow"]->DeptAddress2                  = $deptEdit->ZedDeptAddress2                     = $request->DeptAddress2;
        $this->v["deptRow"]->DeptAddressCity               = $deptEdit->ZedDeptAddressCity                  = $request->DeptAddressCity;
        $this->v["deptRow"]->DeptAddressState              = $deptEdit->ZedDeptAddressState                 = $request->DeptAddressState;
        $this->v["deptRow"]->DeptAddressZip                = $deptEdit->ZedDeptAddressZip                   = $request->DeptAddressZip;
        $this->v["deptRow"]->DeptAddressCounty             = $deptEdit->ZedDeptAddressCounty                = $request->DeptAddressCounty;
        $this->v["deptRow"]->DeptEmail                     = $deptEdit->ZedDeptEmail                        = $request->DeptEmail;
        $this->v["deptRow"]->DeptPhoneWork                 = $deptEdit->ZedDeptPhoneWork                    = $request->DeptPhoneWork;
        $this->v["deptRow"]->DeptTotOfficers               = $deptEdit->ZedDeptTotOfficers                  = str_replace(',', '', $request->DeptTotOfficers);
        $this->v["deptRow"]->DeptJurisdictionPopulation    = $deptEdit->ZedDeptJurisdictionPopulation       = str_replace(',', '', $request->DeptJurisdictionPopulation);
        $this->v["deptRow"]->DeptScoreOpenness             = $deptEdit->ZedDeptScoreOpenness                = $request->DeptScoreOpenness;
        
        $ia->OverVerified                 = $iaEdit->ZedOverOverVerified                 = date("Y-m-d H:i:s");
        $ia->OverAgncName                 = $iaEdit->ZedOverAgncName                 = $request->DeptName;
        $ia->OverAddress                  = $iaEdit->ZedOverAddress                  = $request->IAOverAddress;
        $ia->OverAddress2                 = $iaEdit->ZedOverAddress2                 = $request->IAOverAddress2;
        $ia->OverAddressCity              = $iaEdit->ZedOverAddressCity              = $request->IAOverAddressCity;
        $ia->OverAddressState             = $iaEdit->ZedOverAddressState             = $request->IAOverAddressState;
        $ia->OverAddressZip               = $iaEdit->ZedOverAddressZip               = $request->IAOverAddressZip;
        $ia->OverEmail                    = $iaEdit->ZedOverEmail                    = $request->IAOverEmail;
        $ia->OverPhoneWork                = $iaEdit->ZedOverPhoneWork                = $request->IAOverPhoneWork;
        $ia->OverNameFirst                = $iaEdit->ZedOverNameFirst                = $request->IAOverNameFirst;
        $ia->OverNameMiddle               = $iaEdit->ZedOverNameMiddle               = $request->IAOverNameMiddle;
        $ia->OverNameLast                 = $iaEdit->ZedOverNameLast                 = $request->IAOverNameLast;
        $ia->OverTitle                    = $iaEdit->ZedOverTitle                    = $request->IAOverTitle;
        $ia->OverIDnumber                 = $iaEdit->ZedOverIDnumber                 = $request->IAOverIDnumber;
        $ia->OverNickname                 = $iaEdit->ZedOverNickname                 = $request->IAOverNickname;
        $ia->OverWebsite                  = $iaEdit->ZedOverWebsite                  = $this->fixURL($request->IAOverWebsite);
        $ia->OverFacebook                 = $iaEdit->ZedOverFacebook                 = $this->fixURL($request->IAOverFacebook);
        $ia->OverTwitter                  = $iaEdit->ZedOverTwitter                  = $this->fixURL($request->IAOverTwitter);
        $ia->OverYouTube                  = $iaEdit->ZedOverYouTube                  = $this->fixURL($request->IAOverYouTube);
        $ia->OverWebComplaintInfo         = $iaEdit->ZedOverWebComplaintInfo         = $this->fixURL($request->IAOverWebComplaintInfo);
        $ia->OverComplaintPDF             = $iaEdit->ZedOverComplaintPDF             = $this->fixURL($request->IAOverComplaintPDF);
        $ia->OverComplaintWebForm         = $iaEdit->ZedOverComplaintWebForm         = $this->fixURL($request->IAOverComplaintWebForm);
        $ia->OverHomepageComplaintLink    = $iaEdit->ZedOverHomepageComplaintLink    = $request->IAOverHomepageComplaintLink;
        foreach ($this->v["waysFlds"] as $fld) {
            eval("\$ia->" . $fld . " = \$iaEdit->Edit" . $fld . " = ((isset(\$request->IA"
                . $fld . ") && \$request->IA" . $fld . " == 1) ? 1 : 0);");
        }
        $ia->OverSubmitDeadline           = $iaEdit->ZedOverSubmitDeadline           = intVal($request->IAOverSubmitDeadline);
        if ($request->has('IAOverSubmitAnytime') && intVal($request->IAOverSubmitAnytime) == -1) {
            $ia->OverSubmitDeadline       = $iaEdit->ZedOverSubmitDeadline           = -1;
        }
        
        $iaEdit->ZedOverOnlineResearch   = $request->ZedOverOnlineResearch;
        $iaEdit->ZedOverMadeDeptCall     = $request->ZedOverMadeDeptCall;
        $iaEdit->ZedOverMadeIACall       = $request->ZedOverMadeIACall;
        $iaEdit->ZedOverNotes            = $request->ZedOverNotes;
        
        $this->v["deptRow"]->save();
        $ia->save();
        $deptEdit->save();
        $iaEdit->ZedOverOverID           = $ia->OverID;
        $iaEdit->ZedOverZedDeptID       = $deptEdit->DeptEditID;
        $iaEdit->save();
        
        if (trim($request->CivOverAgncName) != '' || trim($request->CivOverWebsite) != '' 
            || intVal($request->CivOverID) > 0) {
            if (!isset($request->CivOverID) || intVal($request->CivOverID) <= 0) {
                $civ = new OPOversight;
                $civ->OverDeptID          = $request->DeptID;
                $civ->OverType            = 302;
            } else {
                $civ = OPOversight::find($request->CivOverID);
            }
            $civEdit = new OPZeditOversight;
            $civEdit->ZedOverDeptID      = $request->DeptID;
            $civEdit->ZedOverZedDeptID  = $deptEdit->ZedDeptID;
            $civEdit->ZedOverUser        = Auth::user()->id;
            $civEdit->ZedOverOverType        = 302;
            
            $civ->OverVerified            = $civEdit->ZedOverOverVerified = date("Y-m-d H:i:s");
            $civ->OverAgncName            = $civEdit->ZedOverAgncName = $request->CivOverAgncName;
            $this->collectCivOversightForm($civ, $civEdit, $request);
            
            $civ->save();
            $civEdit->ZedOverOverID      = $civ->OverID;
            $civEdit->save();
        }
        
        $tmpReserve = OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpUser', Auth::user()->id)
            ->where('TmpVal', $request->DeptID)
            ->delete();
        
        if ($request->whatNext == 'again') {
            $request->session()->put('whatNext', 'again');
            return $this->redir('/dashboard/volunteer/verify/'.$request->DeptSlug);
        } elseif ($request->whatNext == 'list') {
            $request->session()->put('whatNext', 'list');
            return $this->redir('/dashboard/volunteer');
        } else { // moving to next (reserved) department
            $request->session()->put('whatNext', 'another');
            return $this->redir('/dashboard/volunteer/verify/'.$request->whatNext);
        }
        return $this->redir('/dashboard/volunteer'); // this line shouldn't happen
    }
    
    protected function collectCivOversightForm(&$civ, &$civEdit, $request)
    {
        $civ->OverAddress           = $civEdit->ZedOverAddress          = $request->CivOverAddress;
        $civ->OverAddress2          = $civEdit->ZedOverAddress2         = $request->CivOverAddress2;
        $civ->OverAddressCity       = $civEdit->ZedOverAddressCity      = $request->CivOverAddressCity;
        $civ->OverAddressState      = $civEdit->ZedOverAddressState     = $request->CivOverAddressState;
        $civ->OverAddressZip        = $civEdit->ZedOverAddressZip       = $request->CivOverAddressZip;
        $civ->OverEmail             = $civEdit->ZedOverEmail            = $request->CivOverEmail;
        $civ->OverPhoneWork         = $civEdit->ZedOverPhoneWork        = $request->CivOverPhoneWork;
        $civ->OverNameFirst         = $civEdit->ZedOverNameFirst        = $request->CivOverNameFirst;
        $civ->OverNameMiddle        = $civEdit->ZedOverNameMiddle       = $request->CivOverNameMiddle;
        $civ->OverNameLast          = $civEdit->ZedOverNameLast         = $request->CivOverNameLast;
        $civ->OverTitle             = $civEdit->ZedOverTitle            = $request->CivOverTitle;
        $civ->OverIDnumber          = $civEdit->ZedOverIDnumber         = $request->CivOverIDnumber;
        $civ->OverNickname          = $civEdit->ZedOverNickname         = $request->CivOverNickname;
        $civ->OverWebsite           = $civEdit->ZedOverWebsite          = $request->CivOverWebsite;
        /*
        $civ->OverFacebook          = $civEdit->ZedOverFacebook         = $request->CivOverFacebook;
        $civ->OverTwitter           = $civEdit->ZedOverTwitter          = $request->OverTwitter;
        $civ->OverWebComplaintInfo  = $civEdit->ZedOverWebComplaintInfo = $request->CivOverWebComplaintInfo;
        $civ->OverComplaintPDF      = $civEdit->ZedOverComplaintPDF     = $request->CivOverComplaintPDF;
        $civ->OverComplaintWebForm  = $civEdit->ZedOverComplaintWebForm =$request->CivOverComplaintWebForm;
        $civ->OverHomepageComplaintLink = $civEdit->ZedOverHomepageComplaintLink = $request->CivOverHomepageComplaintLink;
        foreach ($this->v["waysFlds"] as $fld) {
            eval("\$civ->" . $fld . " = \$civEdit->Edit" . $fld . " = ((isset(\$request->Civ" 
                . $fld . ") && \$request->Civ" . $fld . " == 1) ? 1 : 0);");
        }
        $civ->OverSubmitDeadline    = $civEdit->ZedOverSubmitDeadline  = intVal($request->CivOverSubmitDeadline);
        */
        return true;
    }
    
    protected function getSidebarScript()
    {
        $script1 = $GLOBALS["SL"]->getBlurb('Phone Script: Department');
        $script2 = $GLOBALS["SL"]->getBlurb('Phone Script: Internal Affairs');
        return view('vendor.openpolice.volun.volunScript-cache', [
            'script1' => $script1, 
            'script2' => $script2
        ])->render();
    }
    
    public function deptEditCheck()
    {
        $this->v["content"] = '<div class="p20"></div>
            <h1 class="slBlueDark" style="margin-bottom: 5px;">Department Info: Volunteer Checklist</h1>
            <a href="/dashboard/volunteer"><i class="fa fa-caret-left"></i> Back To Department List</a>' 
            . $GLOBALS["SL"]->getBlurbAndSwap('Volunteer Checklist') . '<div class="p20"></div>';
        return view('vendor.survloop.master', $this->v)->render();
    }
    
    
    public function saveDefaultState(Request $request)
    {
        $this->admControlInit($request);
        if ($GLOBALS["SL"]->REQ->has('newState')) {
            $this->v["yourContact"]->update([ "PrsnAddressState" => $GLOBALS["SL"]->REQ->newState ]);
        }
        if ($GLOBALS["SL"]->REQ->has('newPhone')) {
            $this->v["yourContact"]->update([ "PrsnPhoneMobile" => $GLOBALS["SL"]->REQ->newPhone ]);
        }
        exit;
    }
    
    protected function printSidebarLeaderboard() 
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        return view('vendor.openpolice.volun.volun-sidebar-leaderboard', [
            "leaderboard" => $this->v["leaderboard"]
        ])->render();
    }
    
    public function printStars(Request $request)
    {
        $this->admControlInit($request, '/dashboard/volunteer/stars');
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        $this->v["yourStats"] = [];
        if ($this->v["leaderboard"]->UserInfoStars && sizeof($this->v["leaderboard"]->UserInfoStars) > 0) {
            foreach ($this->v["leaderboard"]->UserInfoStars as $i => $volunUser) {
                if ($volunUser->UserInfoUserID == $this->v["user"]->id) {
                    $this->v["yourStats"] = $volunUser;
                }
            }
        }
        return view('vendor.openpolice.volun.stars', $this->v)->render();
    }
    
    public function volunProfileAdm(Request $request, $uid)
    {
        $this->admControlInit($request, '/dashboard/volun/stars');
        return $this->volunProfile($request, $uid, true);
    }
    
    public function volunProfile(Request $request, $uid, $isAdmin = false)
    {
        if (!$isAdmin) $this->admControlInit($request, '/dashboard/volunteer/stars');
        $this->v["isAdminList"] = $isAdmin;
        $this->v["userObj"] = User::find($uid);
        $this->v["userStats"] = OPzVolunUserInfo::where('UserInfoUserID', $uid)
        	->first();
        $this->v["userInfo"] = OPPersonContact::find($this->v["userStats"]->UserInfoPersonContactID);
        $deptEdits = [];
        $recentEdits = OPZeditDepartments::where('ZedDeptUserID', $uid)
            ->orderBy('ZedDeptDeptVerified', 'desc')
            ->get();
        if ($recentEdits->isNotEmpty()) {
            foreach ($recentEdits as $i => $edit) {
                $iaEdit  = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', 303)
                    ->first();
                $civEdit = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', 302)
                    ->first();
                $userObj = User::find($edit->ZedDeptUserID);
                $deptEdits[] = [
                    $userObj->printUsername(true, '/dashboard/volun/user/'), 
                    $edit, 
                    $iaEdit, 
                    $civEdit
                ];
            }
        }
        $this->v["recentEdits"] = '';
        if ($deptEdits->isNotEmpty()) {
			foreach ($deptEdits as $deptEdit) {
				$this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
					"user"     => $deptEdit[0], 
					"deptRow"  => OPDepartments::find($deptEdit[1]->ZedDeptDeptID), 
					"deptEdit" => $deptEdit[1], 
					"deptType" => $GLOBALS["SL"]->def->getVal('Department Types', $deptEdit[1]->ZedDeptType),
					"iaEdit"   => $deptEdit[2], 
					"civEdit"  => $deptEdit[3]
				])->render();
			}
		}
        return view('vendor.openpolice.admin.volun.volunProfile', $this->v)->render();
    }
    
    
    public function fixURL($str)
    {
        $str = trim($str);
        if ($str == '' || strtolower($str) == 'none') return '';
        if (substr($str, 0, 1) == '@') return 'https://twitter.com/' . substr($str, 1);
        if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://') {
            $str = 'http:' . ((substr($str, 0, 2) != '//') ? '//' : '') . $str;
        }
        return $str;
    }
    
}
