<?php 
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SLDefinitions;
use App\Models\SLInstructs;

use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPPersonContact;
use App\Models\OPzVolunEditsDepts;
use App\Models\OPzVolunEditsOvers;
use App\Models\OPzVolunTmp;
use App\Models\OPzVolunUserInfo;

use OpenPolice\Controllers\VolunteerLeaderboard;
use OpenPolice\Controllers\OpenPoliceAdmin;

class VolunteerController extends OpenPoliceAdmin
{
    
    private $indexFlds = "`DeptID`, `DeptName`, `DeptSlug`, `DeptAddressCity`, `DeptAddressState`, `DeptVerified`, `DeptScoreOpenness`";
    
    protected function initExtra(Request $request) 
    {
        $this->v["management"] = ($this->v["user"]->hasRole('administrator') || $this->v["user"]->hasRole('staff'));
        $this->v["volunOpts"] = (($this->REQ->session()->has('volunOpts')) ? $this->REQ->session()->get('volunOpts') : 1);
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
        $this->v["deptPoints"] = ["Website" => 5, "FB" => 5, "Twit" => 5, "YouTube" => 5, "ComplaintInfo" => 20, "ComplaintInfoHomeLnk" => 15, "FormPDF" => 15];
        if (!isset($this->v["currPage"])) $this->v["currPage"] = '/volunteer';
        return true;
    }
    
    protected function tweakAdmMenu($currPage = '')
    {
        //echo '<pre>'; print_r($this->admMenuData["adminNav"]); echo '</pre>';
        if (isset($this->v["deptSlug"])) {
            if ($this->v["user"]->hasRole('administrator') || $this->v["user"]->hasRole('staff') || $this->v["user"]->hasRole('databaser')) {
                $this->admMenuData["currNavPos"] = array(2, 3, -1, -1);
            }
            else $this->admMenuData["currNavPos"] = array(1, -1, -1, -1);
            $volunteeringSubMenu = ['javascript:;" id="navBtnContact0',    '<b>Verifying Department</b>', 1, [
                ['javascript:;" id="navBtnContact',    '&nbsp;&nbsp;Contact Info         <div id="currContact" class="disIn pull-right mL20"><i class="fa fa-chevron-right"></i></div>',         1, []], 
                ['javascript:;" id="navBtnWeb',        '&nbsp;&nbsp;Web & Complaints     <div id="currWeb" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',         1, []], 
                ['javascript:;" id="navBtnIA',        '&nbsp;&nbsp;Internal Affairs     <div id="currIA" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',     1, []], 
                ['javascript:;" id="navBtnOver',    '&nbsp;&nbsp;Civilian Oversight <div id="currOver" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',     1, []], 
                ['javascript:;" id="navBtnSave',    '<span class="btn btn-lg btn-primary">Save All Changes <i class="fa fa-floppy-o"></i></span>', 1, []], 
                ['javascript:;" id="navBtnEdits',     '<span class="gry9">&nbsp;&nbsp;Past Edits:</span> ' 
                                                    . ((isset($this->v["editsSummary"][1])) ? $this->v["editsSummary"][1]: '') 
                                                    . '<div id="currEdits" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>', 1, []], 
                ['javascript:;" id="navBtnCheck',     '<span class="gry9">&nbsp;&nbsp;Volunteer Checklist</span> <div id="currCheck" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>', 1, []], 
                ['javascript:;" id="navBtnPhone',    '<span class="gry9">&nbsp;&nbsp;Sample Phone Script</span>',     1, []], 
                ['javascript:;" id="navBtnFAQ',    '<span class="gry9">&nbsp;&nbsp;Frequently Asked <i class="fa fa-question"></i>s</span> <div id="currFAQ" class="disNon pull-right mL20"><i class="fa fa-chevron-right"></i></div>',     1, []]
            ]];
            if ($this->v["user"]->hasRole('administrator') || $this->v["user"]->hasRole('staff') || $this->v["user"]->hasRole('databaser')) {
                $this->admMenuData["adminNav"][2][3][3] = $volunteeringSubMenu;
            }
            else { // is Volunteer
                $volunteeringSubMenu[1] .= ' <span class="pull-right"><i class="fa fa-check"></i></span>';
                $this->admMenuData["adminNav"][1] = $volunteeringSubMenu;
            }
        }
        if (!$this->v["user"]->hasRole('administrator') && !$this->v["user"]->hasRole('staff') && !$this->v["user"]->hasRole('databaser')) {
            $score = ((isset($this->v["yourUserInfo"]->UserInfoStars)) ? intVal($this->v["yourUserInfo"]->UserInfoStars) : 0);
            $this->admMenuData["adminNav"][2][1] = str_replace('[[score]]', $score, $this->admMenuData["adminNav"][2][1]);
        }
        return true;
    }
    
    protected function genDeptAdmTopMenu($deptRow)
    {
        return '<ul class="nav navbar-nav">
            <li class="active"><a id="navbarDept" class="f26" style="background: none;" href="javascript:;" 
            title="Verifying Department: ' . $deptRow->DeptName . '">Verify: ' . str_replace('Department', 'Dept', $deptRow->DeptName) . ' 
            <span class="f10 mL10">' . $deptRow->DeptAddressCity . ', ' . $deptRow->DeptAddressState . '</span></a></li>
        </ul>';
    }
    
    // This will be removed after the big 50 have been completed
    private $big50 = [
        ['New York City Police Department', 'NY'], ['Chicago Police Dept', 'IL'], ['Los Angeles Police Department', 'CA'], ['Philadelphia Police Department', 'PA'], 
        ['Houston Police Department', 'TX'], ['Washington Metropolitan Police Department', 'DC'], ['Phoenix Police Department', 'AZ'], ['Dallas Police Department', 'TX'], 
        ['Miami-Dade (County) Police Department', 'FL'], ['Detroit Police Department', 'MI'], ['Baltimore Police Department', 'MD'], ['Baltimore County Police Department', 'MD'], 
        ['Suffolk County Police Department', 'NY'], ['Nassau County Police Department', 'NY'], ['Las Vegas Metro Police Department', 'NV'], ['San Francisco Police Department', 'CA'], 
        ['Boston Police Department', 'MA'], ['Memphis Police Department', 'TN'], ['Milwaukee Police Department', 'WI'], ['San Diego Police Department', 'CA'], 
        ['Honolulu (City and County) Police Department', 'HI'], ['Columbus Police Department', 'OH'], ['San Antonio Police Department', 'TX'], ['Atlanta Police Department', 'GA'], 
        ['Jacksonville Sheriff\'s Office', 'FL'], ['Indianapolis Police', 'IN'], ['Cleveland Police Department', 'OH'], ['Denver Police Department', 'CO'], 
        ['Prince George\'s County Police Department', 'MD'], ['Charlotte - Mecklenburg Police Department', 'NC'], ['Fairfax County Police Department', 'VA'], 
        ['New Orleans Police Department', 'LA'], ['Austin Police Department', 'TX'], ['Fort Worth Police Department', 'TX'], ['Kansas City Police Department', 'KS'], 
        ['Kansas City Police Department', 'MO'], ['San Jose Police Department', 'CA'], ['St. Louis (city) Police Dept', 'MO'], ['Seattle Police Department', 'WA'], 
        ['Newark Police', 'NJ'], ['Montgomery County Police Department', 'MD'], ['Louisville Metro Police Department', 'KY'], ['El Paso Police Department', 'TX'], 
        ['Cincinnati Police Department', 'OH'], ['Miami Police Department', 'FL'], ['Tucson Police Department', 'AZ'], ['Oklahoma City Police Department', 'OK'], 
        ['Tampa Police Department', 'FL'], ['Long Beach Police Department', 'CA'], ['Albuquerque Police Department', 'NM']
    ];
    
    
    protected function getNextDept()
    {
        $this->v["nextDept"] = array(0, '', '');
        OPzVolunTmp::where('TmpType', 'EditDept')
            ->where('TmpDate', '<', date("Y-m-d H:i:s", time(date("H")-6, date("i"), date("s"), date("n"), date("j"), date("Y"))))
            ->delete();
        // First check for department temporarily reserved for this user
        $tmpReserve = OPzVolunTmp::where('TmpType', 'EditDept')
            ->where('TmpUser', Auth::user()->id)
            ->first();
        if ($tmpReserve && isset($tmpReserve->TmpVal) && intVal($tmpReserve->TmpVal) > 0)
        {
            $nextRow = OPDepartments::where('DeptID', $tmpReserve->TmpVal)
                ->first();
            $this->v["nextDept"] = array( $nextRow->DeptID, $nextRow->DeptName, $nextRow->DeptSlug );
        }
        else { // no department reserved yet, find best next choice...
            $nextRow = $qmen = array();
            $qBase = "SELECT `DeptID`, `DeptName`, `DeptSlug` FROM `OP_Departments` WHERE ";
            $qReserves = " AND `DeptID` NOT IN (SELECT `TmpVal` FROM `OP_zVolunTmp` WHERE `TmpType` LIKE 'EditDept' AND `TmpUser` NOT LIKE '" . Auth::user()->id . "')";
            $qBig50 = ""; foreach ($this->big50 as $dept) $qBig50 .= " OR (`DeptName` LIKE '" . addslashes($dept[0]) . "' AND `DeptAddressState` LIKE '" . $dept[1] . "')";
            $qmen[] = $qBase."(`DeptVerified` < '2015-01-01 00:00:00' OR `DeptVerified` IS NULL) AND (".substr($qBig50, 3).") " . $qReserves . " ORDER BY `DeptName`";
            $qmen[] = $qBase."(`DeptVerified` < '2015-01-01 00:00:00' OR `DeptVerified` IS NULL) " . $qReserves . " ORDER BY RAND()";
            $qmen[] = $qBase."1 " . $qReserves . " ORDER BY `DeptVerified`";
            $qmen[] = $qBase."1 ORDER BY RAND()"; // worst-case backup
            for ($i=0; ($i<sizeof($qmen) && sizeof($nextRow) == 0); $i++) 
            {
                //echo ' ??? ' . $qmen[$i]." LIMIT 1" . ' <br />';
                $nextRow = DB::select( DB::raw( $qmen[$i]." LIMIT 1" ) );
            }
            $this->v["nextDept"] = array( $nextRow[0]->DeptID, str_replace('Department', 'Dept', $nextRow[0]->DeptName), $nextRow[0]->DeptSlug );
            
            // Temporarily reserve this department for this user
            $newTmp = new OPzVolunTmp;
            $newTmp->TmpUser = Auth::user()->id;
            $newTmp->TmpDate = date("Y-m-d H:i:s");
            $newTmp->TmpType = 'EditDept';
            $newTmp->TmpVal = $this->v["nextDept"][0];
            $newTmp->save();
        }
        return $this->v["nextDept"];
    }
    
    public function getVolunEditsOverview()
    {
        $retArr = $userTots = $uNames = array();
        $userEdits = DB::table('OP_zVolunEditsDepts')
            ->join('users', 'users.id', '=', 'OP_zVolunEditsDepts.EditDeptUser')
            ->select('users.id', 'users.name', 'OP_zVolunEditsDepts.EditDeptDeptID')
            ->get();
        if (sizeof($userEdits) > 0)
        {
            foreach ($userEdits as $row)
            {
                if (!isset($userTots[$row->id])) $userTots[$row->id] = array();
                if (!in_array($row->DeptID, $userTots[$row->id])) $userTots[$row->id][] = array($row->EditDeptDeptID);
                if (!isset($uNames[$row->id]))
                {
                    $uNames[$row->id] = '<a href="/admin/user/' . $row->id . '">' . $row->name . '</a>';
                }
            }
        }
        foreach ($userTots as $u => $d) $userTots[$u] = sizeof($d);
        arsort($userTots);
        foreach ($userTots as $u => $d) $retArr[] = array($uNames[$u], $d);
        return $retArr;
    }
    
    public function index(Request $request)
    {
        $this->admControlInit($request, '/volunteer');
        $this->v["viewType"]     = 'priority';
        $this->v["deptRows"]     = array();
        $this->v["searchForm"]     = $this->deptSearchForm();
        $qman = " `DeptVerified` > '2015-01-01 00:00:00' ";
        foreach ($this->big50 as $dept) $qman .= " OR (`DeptName` LIKE " . DB::getPdo()->quote($dept[0]) . " AND `DeptAddressState` LIKE " . DB::getPdo()->quote($dept[1]) . ")";
        $this->v["deptRows"] = DB::select( DB::raw("SELECT * FROM `OP_Departments` WHERE " . $qman . " ORDER BY `DeptScoreOpenness` DESC, `DeptVerified` DESC, `DeptName`, `DeptAddressState`") );
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard()
            . '<div class="taC p10 f16 gry9"><i>' . $GLOBALS["DB"]->dbRow->DbMission . '</i></div>';
        //echo '<pre>'; print_r($this->v["yourUserInfo"]); echo '</pre>';
        return view('vendor.openpolice.volun.volunteer', $this->v);
    }
    
    public function indexAll(Request $request)
    {
        $this->admControlInit($request, '/volunteer');
        $this->v["viewType"] = 'all';
        $this->v["deptRows"] = array();
        $this->v["searchForm"] = $this->deptSearchForm();
        $this->v["deptRows"] = OPDepartments::orderBy('DeptName', 'asc')->paginate(50);
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard();
        return view('vendor.openpolice.volun.volunteer', $this->v);
    }
    
    protected function deptSearchForm($state = '', $deptName = '')
    {
        return view('vendor.openpolice.volun.volunEditSearch', [ 
            "deptName" => $deptName, 
            "stateDrop" => $GLOBALS["DB"]->states->stateDrop($state) ])->render();
    }
    
    public function indexSearch($deptRows = array(), $state = '', $deptName = '')
    {
        $this->v["viewType"] = 'search';
        $this->v["deptRows"] = $deptRows;
        $this->v["userTots"] = $this->getVolunEditsOverview();
        $this->v["searchForm"] = str_replace('<select', '<div class="p5"><select', 
            str_replace('class="w33"', 'class="w33 f22"', $this->deptSearchForm($state, $deptName) )) . '</div>';
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard();
        return view('vendor.openpolice.volun.volunteer', $this->v);
    }
    
    public function indexSearchS(Request $request, $state = '')
    {
        $this->admControlInit($request, '/volunteer');
        $deptRows = OPDepartments::where('DeptAddressState', '=', $state)->orderBy('DeptName', 'asc')->paginate(50);
        return $this->indexSearch($deptRows, $state, '');
    }
    
    public function indexSearchD(Request $request, $deptName = '')
    {
        $this->admControlInit($request, '/volunteer');
        $this->v["deptName"] = '';
        if (trim($deptName) != '') $this->v["deptName"] = $deptName;
        return $this->indexSearch($this->processSearchDepts('', $deptName), '', $deptName);
    }
    
    public function indexSearchSD(Request $request, $state = '', $deptName = '')
    {
        $this->admControlInit($request, '/volunteer');
        $this->v["deptName"] = '';
        if (trim($deptName) != '') $this->v["deptName"] = $deptName;
        return $this->indexSearch($this->processSearchDepts($state, $deptName), $state, $deptName);
    }
    
    protected function processSearchDepts($state = '', $deptName = '')
    {
        $deptName = str_replace('  ', ' ', str_replace('  ', ' ', str_replace('  ', ' ', $deptName)));
        $searches = array('%'.$deptName.'%');
        if (strpos($deptName, ' ') !== false)
        {
            $words = explode(' ', $deptName);
            foreach ($words as $w) 
            {
                if (!in_array(strtolower($w), array('city', 'county', 'sherrif\'s', 'police', 'department', 'dept')))
                {
                    $searches[] = '%'.$w.'%';
                }
            }
        }
        $deptRows = array();
        $evalQry = "\$deptRows = App\\Models\\OPDepartments::"
            . ((trim($state) != '') ? "where('DeptAddressState', '=', \$state)->" : "")
            . "where(function(\$query) { return \$query->where('DeptName', 'LIKE', '" . addslashes($searches[0]) . "')";
            for ($i = 1; $i < sizeof($searches); $i++)
            {
                $evalQry .= "->orWhere('DeptName', 'LIKE', '" . addslashes($searches[$i]) . "')";
            }
        $evalQry .= "; })->orderBy('DeptName', 'asc')->paginate(50);";
        eval($evalQry);
        return $deptRows;
    }
    
    
    public function nextDept() {
        $this->getNextDept();
        return redirect('/volunteer/verify/'.$this->v["nextDept"][2]);
    }
    
    
    public function newDept(REQUEST $request) {
        if ($request->has('deptName') && $request->has('DeptAddressState'))
        {
            $newDept = $this->newDeptAdd($request->deptName, $request->DeptAddressState);
            return redirect('/volunteer/verify/' . $newDept->DeptSlug);
        }
        return redirect('/volunteer');
    }
    
    public function newDeptAdd($deptName = '', $deptState = '') {
        if (trim($deptName) != '' && trim($deptState) != '')
        {
            $newDept = OPDepartments::where('DeptName', $deptName)->where('DeptAddressState', $deptState)->first();
            if ($newDept && isset($newDept->DeptSlug)) redirect('/volunteer/verify/'.$newDept->DeptSlug);
            $newDept     = new OPDepartments;
            $newIA         = new OPOversight;
            $newEdit     = new OPzVolunEditsDepts;
            $iaEdit     = new OPzVolunEditsOvers;
            $newIA->OverType             = $iaEdit->EditOverType             = 303;
            $newDept->DeptName             = $newEdit->EditDeptName             = $deptName;
            $newDept->DeptAddressState     = $newEdit->EditDeptAddressState     = (($deptState != 'US') ? $deptState : '');
            $newDept->DeptSlug             = $newEdit->EditDeptSlug             = $deptState . '-' . Str::slug($deptName);
            $newDept->DeptType             = $newEdit->EditDeptType             = (($deptState == 'US') ? 266 : 0);
            $newDept->DeptStatus         = 1;
            $newDept->save();
            $newIA->OverDeptID             = $newEdit->EditDeptDeptID             = $iaEdit->EditOverDeptID     = $newDept->DeptID;
            $newIA->save();
            $iaEdit->EditOverOverID     = $newIA->OverID;
            $newEdit->EditDeptUser         = $iaEdit->EditOverUser             = Auth::user()->id;
            $newEdit->EditDeptVerified     = $iaEdit->EditOverVerified         = date("Y-m-d H:i:s");
            $newEdit->save();
            $iaEdit->EditOverEditDeptID     = $newEdit->EditDeptID;
            $iaEdit->EditOverNotes = 'NEW DEPARTMENT ADDED TO DATABASE!';
            $iaEdit->save();
            return $newDept;
        }
        return '';
    }
    
    
    public function deptEdit(REQUEST $request, $deptSlug)
    {
        $this->v["deptSlug"]         = $deptSlug;
        $this->v["deptRow"]         = OPDepartments::where('DeptSlug', $deptSlug)->first();
        $this->v["editsIA"]         = $this->v["editsCiv"] = $this->v["userEdits"] = $this->v["userNames"] = array();
        $this->v["editTots"]         = ["notes" => 0, "online" => 0, "callDept" => 0, "callIA" => 0];
        $this->v["user"]             = Auth::user();
        $this->v["neverEdited"]     = false;
        $this->v["recentEdits"]        = '';
        
        if (!isset($this->v["deptRow"]->DeptID) || intVal($this->v["deptRow"]->DeptID) <= 0) 
        {
            return redirect('/volunteer');
        }
        
        $recentEdits = OPzVolunEditsDepts::where('EditDeptDeptID', $this->v["deptRow"]->DeptID)
            ->orderBy('EditDeptVerified', 'desc')
            ->get();
        if ($recentEdits && sizeof($recentEdits) > 0)
        {
            foreach ($recentEdits as $i => $edit)
            {
                $this->v["editsIA"][$i]  = OPzVolunEditsOvers::where('EditOverEditDeptID', $edit->EditDeptID)
                    ->where('EditOverType', 303)
                    ->first();
                $this->v["editsCiv"][$i] = OPzVolunEditsOvers::where('EditOverEditDeptID', $edit->EditDeptID)
                    ->where('EditOverType', 302)
                    ->first();
                if ($this->v["editsIA"][$i])
                {
                    if (trim($this->v["editsIA"][$i]->EditOverNotes) != '')             $this->v["editTots"]["notes"]++;
                    if (intVal($this->v["editsIA"][$i]->EditOverOnlineResearch) == 1)     $this->v["editTots"]["online"]++;
                    if (intVal($this->v["editsIA"][$i]->EditOverMadeDeptCall) == 1)     $this->v["editTots"]["callDept"]++;
                    if (intVal($this->v["editsIA"][$i]->EditOverMadeIACall) == 1)         $this->v["editTots"]["callIA"]++;
                }
                if (!isset($this->v["userNames"][$edit->EditDeptUser]))
                {
                    $this->v["userNames"][$edit->EditDeptUser] = User::find($edit->EditDeptUser)
                        ->printUsername(true, '/dashboard/volun/user/');
                }
                if ($this->v["user"]->hasRole('administrator') || $this->v["user"]->hasRole('staff'))
                {
                    $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                        "user"         => $this->v["userNames"][$edit->EditDeptUser], 
                        "deptRow"     => $this->v["deptRow"], 
                        "deptEdit"     => $edit, 
                        "deptType"     => $GLOBALS["DB"]->getDefValue('Types of Departments', $edit->DeptType),
                        "iaEdit"     => $this->v["editsIA"][$i], 
                        "civEdit"     => $this->v["editsCiv"][$i]
                    ])->render();
                }
            }
        }
        else $this->v["neverEdited"] = true;
        $this->loadDeptEditsSummary();
        $this->admControlInit($request, '/volunteer/verify');
        if (!$request->session()->has('whatNext')) $request->session()->put('whatNext', 'another');
        $this->getNextDept();
        $this->v["whatNext"]         = $request->session()->get('whatNext');
        $this->v["volunChecklist"]     = $this->deptEditChecklistHTML();
        $this->v["FAQs"]             = $this->deptEditFaqHTML();
        $this->v["rightSide"]        = $this->getSidebarScript();
        $this->v["stateDrop"]         = $GLOBALS["DB"]->states->stateDrop($this->v["deptRow"]->DeptAddressState);
        $this->v["iaRow"]           = OPOversight::where('OverDeptID', $this->v["deptRow"]->DeptID)->where('OverType', 303)->first();
        if (!isset($this->v["iaRow"]) || sizeof($this->v["iaRow"]) == 0)
        {
            $this->v["iaRow"]         = new OPOversight;
            $this->v["iaRow"]->OverType = 303; // definition ID for Internal Affairs
        }
        $this->v["civRow"]  = OPOversight::where('OverDeptID', $this->v["deptRow"]->DeptID)
            ->where('OverType', 302)
            ->first();
        if (!isset($this->v["civRow"]) || sizeof($this->v["civRow"]) == 0)
        {
            $this->v["civRow"]         = new OPOversight;
            $this->v["civRow"]->OverType = 302; // definition ID for Civilian Oversight
        }
        $this->v["iaForms"]          = $this->deptEditPrintOver($this->v["iaRow"]);
        $this->v["civForms"]          = $this->deptEditPrintOver($this->v["civRow"], 'Civ');
        $this->v["iaComplaints"]      = $this->deptEditPrintOverComplaints($this->v["deptRow"], $this->v["iaRow"]);
        $this->v["admTopMenu"]      = $this->genDeptAdmTopMenu($this->v["deptRow"]);
        $this->v["deptTypes"]          = SLDefinitions::where('DefSet', 'Value Ranges')
                                            ->where('DefSubset', 'Types of Departments')
                                            ->orderBy('DefOrder')
                                            ->get();
        //echo '<pre>'; print_r($this->v["deptTypes"]); echo '</pre>';
        //echo '<pre>'; print_r($this->v["iaRow"]); echo '</pre>';
        return view('vendor.openpolice.volun.volunDeptEdit', $this->v);
    }
    
    public function deptEditPrintOver($overRow = array(), $overType = 'IA') 
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
        ]);
    }
    
    public function deptEditPrintOverComplaints($deptRow = array(), $overRow = array(), $overType = 'IA') 
    {
        $waysChecked = array();
        foreach ($this->v["ways"] as $i => $w)
        {
            eval("\$waysChecked[\$i] = ((isset(\$overRow->".$this->v["waysFlds"][$i].") && \$overRow->".$this->v["waysFlds"][$i]." == 1) ? true : false);");
        }
        //echo '<pre>'; print_r($waysChecked); echo '</pre>';
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
        ]);
    }
    
    public function loadDeptEditsSummary()
    {
        $this->v["editsSummary"] = ['<b>Last Verified: ', ''];
        $this->v["editsSummary"][0] .= (($this->v["neverEdited"]) ? date('n/j', strtotime($this->v["deptRow"]->DeptVerified)) : 'Never') . '</b> &nbsp;&nbsp;&nbsp;'
            . '<nobr>' . intVal($this->v["editTots"]["online"]) . '<span class="f10">x</span><i class="fa fa-laptop"></i> Online Research,</nobr><br />'
            . '<nobr>' . intVal($this->v["editTots"]["callDept"]) . '<span class="f10">x</span><i class="fa fa-phone"></i> Department Calls,</nobr> &nbsp;&nbsp;&nbsp;'
            . '<nobr><span class="slBlueDark">' . intVal($this->v["editTots"]["callIA"]) . '<span class="f10">x</span><i class="fa fa-phone slBlueDark"></i> Internal Affairs Calls</span></nobr>';
        $this->v["editsSummary"][1] = intVal($this->v["editTots"]["online"]) . '<i class="fa fa-laptop"></i>, '
            . intVal($this->v["editTots"]["callDept"]) . '<i class="fa fa-phone"></i>, '
            . '<span class="slBlueDark">' . intVal($this->v["editTots"]["callIA"]) . '<i class="fa fa-phone slBlueDark"></i></span>';
        return true;
    }
    
    public function deptEditSave(Request $request, $deptSlug = '') 
    {
        $this->v["deptSlug"] = $deptSlug;
        $this->v["deptRow"] = OPDepartments::find($request->DeptID);
        $this->admControlInit($request, '/volunteer/verify');
        
        $ia = $civ = $deptEdit = $iaEdit = $civEdit = array();
        
        $this->v["deptRow"] = OPDepartments::find($request->DeptID);
        $deptEdit = new OPzVolunEditsDepts;
        if (!isset($request->OverID) || intVal($request->OverID) <= 0)
        {
            $ia = new OPOversight;
            $ia->OverDeptID = $request->DeptID;
            $ia->OverType = 303;
        }
        else $ia = OPOversight::find($request->OverID);
        $iaEdit = new OPzVolunEditsOvers;
        
        $deptEdit->EditDeptDeptID                          = $iaEdit->EditOverDeptID                         = $request->DeptID;
        $deptEdit->EditDeptUser                            = $iaEdit->EditOverUser                           = Auth::user()->id;
        $deptEdit->EditDeptPageTime                        = time()-intVal($request->formLoaded);
        $iaEdit->EditOverType                              = 303;
        
        $this->v["deptRow"]->DeptVerified                  = $deptEdit->EditDeptVerified                     = date("Y-m-d H:i:s");
        $this->v["deptRow"]->DeptName                      = $deptEdit->EditDeptName                         = $request->DeptName;
        $this->v["deptRow"]->DeptSlug                      = $deptEdit->EditDeptSlug                         = $request->DeptSlug;
        $this->v["deptRow"]->DeptType                      = $deptEdit->EditDeptType                         = $request->DeptType;
        $this->v["deptRow"]->DeptStatus                    = $deptEdit->EditDeptStatus                       = $request->DeptStatus;
        $this->v["deptRow"]->DeptAddress                   = $deptEdit->EditDeptAddress                      = $request->DeptAddress;
        $this->v["deptRow"]->DeptAddress2                  = $deptEdit->EditDeptAddress2                     = $request->DeptAddress2;
        $this->v["deptRow"]->DeptAddressCity               = $deptEdit->EditDeptAddressCity                  = $request->DeptAddressCity;
        $this->v["deptRow"]->DeptAddressState              = $deptEdit->EditDeptAddressState                 = $request->DeptAddressState;
        $this->v["deptRow"]->DeptAddressZip                = $deptEdit->EditDeptAddressZip                   = $request->DeptAddressZip;
        $this->v["deptRow"]->DeptAddressCounty             = $deptEdit->EditDeptAddressCounty                = $request->DeptAddressCounty;
        $this->v["deptRow"]->DeptEmail                     = $deptEdit->EditDeptEmail                        = $request->DeptEmail;
        $this->v["deptRow"]->DeptPhoneWork                 = $deptEdit->EditDeptPhoneWork                    = $request->DeptPhoneWork;
        $this->v["deptRow"]->DeptTotOfficers               = $deptEdit->EditDeptTotOfficers                  = str_replace(',', '', $request->DeptTotOfficers);
        $this->v["deptRow"]->DeptJurisdictionPopulation    = $deptEdit->EditDeptJurisdictionPopulation       = str_replace(',', '', $request->DeptJurisdictionPopulation);
        $this->v["deptRow"]->DeptScoreOpenness             = $deptEdit->EditDeptScoreOpenness                = $request->DeptScoreOpenness;
        
        $ia->OverVerified                 = $iaEdit->EditOverVerified                 = date("Y-m-d H:i:s");
        $ia->OverAgncName                 = $iaEdit->EditOverAgncName                 = $request->DeptName;
        $ia->OverAddress                  = $iaEdit->EditOverAddress                  = $request->IAOverAddress;
        $ia->OverAddress2                 = $iaEdit->EditOverAddress2                 = $request->IAOverAddress2;
        $ia->OverAddressCity              = $iaEdit->EditOverAddressCity              = $request->IAOverAddressCity;
        $ia->OverAddressState             = $iaEdit->EditOverAddressState             = $request->IAOverAddressState;
        $ia->OverAddressZip               = $iaEdit->EditOverAddressZip               = $request->IAOverAddressZip;
        $ia->OverEmail                    = $iaEdit->EditOverEmail                    = $request->IAOverEmail;
        $ia->OverPhoneWork                = $iaEdit->EditOverPhoneWork                = $request->IAOverPhoneWork;
        $ia->OverNameFirst                = $iaEdit->EditOverNameFirst                = $request->IAOverNameFirst;
        $ia->OverNameMiddle               = $iaEdit->EditOverNameMiddle               = $request->IAOverNameMiddle;
        $ia->OverNameLast                 = $iaEdit->EditOverNameLast                 = $request->IAOverNameLast;
        $ia->OverTitle                    = $iaEdit->EditOverTitle                    = $request->IAOverTitle;
        $ia->OverIDnumber                 = $iaEdit->EditOverIDnumber                 = $request->IAOverIDnumber;
        $ia->OverNickname                 = $iaEdit->EditOverNickname                 = $request->IAOverNickname;
        $ia->OverWebsite                  = $iaEdit->EditOverWebsite                  = $this->fixURL($request->IAOverWebsite);
        $ia->OverFacebook                 = $iaEdit->EditOverFacebook                 = $this->fixURL($request->IAOverFacebook);
        $ia->OverTwitter                  = $iaEdit->EditOverTwitter                  = $this->fixURL($request->IAOverTwitter);
        $ia->OverYouTube                  = $iaEdit->EditOverYouTube                  = $this->fixURL($request->IAOverYouTube);
        $ia->OverWebComplaintInfo         = $iaEdit->EditOverWebComplaintInfo         = $this->fixURL($request->IAOverWebComplaintInfo);
        $ia->OverComplaintPDF             = $iaEdit->EditOverComplaintPDF             = $this->fixURL($request->IAOverComplaintPDF);
        $ia->OverComplaintWebForm         = $iaEdit->EditOverComplaintWebForm         = $this->fixURL($request->IAOverComplaintWebForm);
        $ia->OverHomepageComplaintLink    = $iaEdit->EditOverHomepageComplaintLink    = $request->IAOverHomepageComplaintLink;
        foreach ($this->v["waysFlds"] as $fld)
        {
            eval("\$ia->".$fld." = \$iaEdit->Edit".$fld." = ((isset(\$request->IA".$fld.") && \$request->IA".$fld." == 1) ? 1 : 0);");
        }
        $ia->OverSubmitDeadline           = $iaEdit->EditOverSubmitDeadline           = $request->IAOverSubmitDeadline;
        if ($request->has('IAOverSubmitAnytime') && intVal($request->IAOverSubmitAnytime) == -1)
        {
            $ia->OverSubmitDeadline       = $iaEdit->EditOverSubmitDeadline           = -1;
            //echo '<br /><br />ummmm?' . $ia->OverSubmitDeadline . '<br />'; exit;
        }
        
        $iaEdit->EditOverOnlineResearch   = $request->EditOverOnlineResearch;
        $iaEdit->EditOverMadeDeptCall     = $request->EditOverMadeDeptCall;
        $iaEdit->EditOverMadeIACall       = $request->EditOverMadeIACall;
        $iaEdit->EditOverNotes            = $request->EditOverNotes;
        
        $this->v["deptRow"]->save();
        $ia->save();
        $deptEdit->save();
        $iaEdit->EditOverOverID           = $ia->OverID;
        $iaEdit->EditOverEditDeptID       = $deptEdit->DeptEditID;
        $iaEdit->save();
        
        if (trim($request->CivOverAgncName) != '' || trim($request->CivOverWebsite) != '' 
            || intVal($request->CivOverID) > 0)
        {
            if (!isset($request->CivOverID) || intVal($request->CivOverID) <= 0)
            {
                $civ = new OPOversight;
                $civ->OverDeptID          = $request->DeptID;
                $civ->OverType            = 302;
            }
            else $civ = OPOversight::find($request->CivOverID);
            $civEdit = new OPzVolunEditsOvers;
            $civEdit->EditOverDeptID      = $request->DeptID;
            $civEdit->EditOverEditDeptID  = $deptEdit->EditDeptID;
            $civEdit->EditOverUser        = Auth::user()->id;
            $civEdit->EditOverType        = 302;
            
            $civ->OverVerified            = $civEdit->EditOverVerified         = date("Y-m-d H:i:s");
            $civ->OverAgncName            = $civEdit->EditOverAgncName         = $request->CivOverAgncName;
            $this->collectCivOversightForm($civ, $civEdit, $request);
            
            $civ->save();
            $civEdit->EditOverOverID      = $civ->OverID;
            $civEdit->save();
        }
        
        $tmpReserve = OPzVolunTmp::where('TmpType', 'EditDept')
            ->where('TmpUser', Auth::user()->id)
            ->where('TmpVal', $request->DeptID)
            ->delete();
        
        if ($request->whatNext == 'again')
        {
            $request->session()->put('whatNext', 'again');
            return redirect('/volunteer/verify/'.$request->DeptSlug);
        }
        elseif ($request->whatNext == 'list')
        {
            $request->session()->put('whatNext', 'list');
            return redirect('/volunteer');
        }
        else
        {   // moving to next (reserved) department
            $request->session()->put('whatNext', 'another');
            return redirect('/volunteer/verify/'.$request->whatNext);
        }
        return redirect('/volunteer'); // this line shouldn't happen
    }
    
    protected function collectCivOversightForm(&$civ, &$civEdit, $request)
    {
        $civ->OverAddress           = $civEdit->EditOverAddress         = $request->CivOverAddress;
        $civ->OverAddress2          = $civEdit->EditOverAddress2        = $request->CivOverAddress2;
        $civ->OverAddressCity       = $civEdit->EditOverAddressCity     = $request->CivOverAddressCity;
        $civ->OverAddressState      = $civEdit->EditOverAddressState    = $request->CivOverAddressState;
        $civ->OverAddressZip        = $civEdit->EditOverAddressZip      = $request->CivOverAddressZip;
        $civ->OverEmail             = $civEdit->EditOverEmail           = $request->CivOverEmail;
        $civ->OverPhoneWork         = $civEdit->EditOverPhoneWork       = $request->CivOverPhoneWork;
        $civ->OverNameFirst         = $civEdit->EditOverNameFirst       = $request->CivOverNameFirst;
        $civ->OverNameMiddle        = $civEdit->EditOverNameMiddle      = $request->CivOverNameMiddle;
        $civ->OverNameLast          = $civEdit->EditOverNameLast        = $request->CivOverNameLast;
        $civ->OverTitle             = $civEdit->EditOverTitle           = $request->CivOverTitle;
        $civ->OverIDnumber          = $civEdit->EditOverIDnumber        = $request->CivOverIDnumber;
        $civ->OverNickname          = $civEdit->EditOverNickname        = $request->CivOverNickname;
        $civ->OverWebsite           = $civEdit->EditOverWebsite         = $request->CivOverWebsite;
        /*
        $civ->OverFacebook          = $civEdit->EditOverFacebook        = $request->CivOverFacebook;
        $civ->OverTwitter           = $civEdit->EditOverTwitter         = $request->OverTwitter;
        $civ->OverWebComplaintInfo  = $civEdit->EditOverWebComplaintInfo = $request->CivOverWebComplaintInfo;
        $civ->OverComplaintPDF      = $civEdit->EditOverComplaintPDF     = $request->CivOverComplaintPDF;
        $civ->OverComplaintWebForm  = $civEdit->EditOverComplaintWebForm =$request->CivOverComplaintWebForm;
        $civ->OverHomepageComplaintLink = $civEdit->EditOverHomepageComplaintLink = $request->CivOverHomepageComplaintLink;
        foreach ($this->v["waysFlds"] as $fld) {
            eval("\$civ->".$fld." = \$civEdit->Edit".$fld." = ((isset(\$request->Civ".$fld.") && \$request->Civ".$fld." == 1) ? 1 : 0);");
        }
        $civ->OverSubmitDeadline     = $civEdit->EditOverSubmitDeadline     = $request->CivOverSubmitDeadline;
        */
        return true;
    }
    
    public function deptEditChecklistHTML()
    {
        $script1 = SLInstructs::where('InstructSpot', 'Phone Script: Department')->first();
        $script2 = SLInstructs::where('InstructSpot', 'Phone Script: Internal Affairs')->first();
        $instruct = SLInstructs::where('InstructSpot', 'Volunteer Department Data Mining')->first();
        // doing this manually for now...
        $fullChecklist = str_replace('[[ Phone Script: Department ]]', $script1->InstructHTML, 
            str_replace('[[ Phone Script: Internal Affairs ]]', $script2->InstructHTML, $instruct->InstructHTML)); 
        //echo '<textarea>'; print_r($instruct); echo '</textarea>';
        if (isset($instruct->InstructHTML)) return $fullChecklist;
        return '';
    }
    
    protected function getSidebarScript()
    {
        $script1 = SLInstructs::where('InstructSpot', 'Phone Script: Department')->first();
        $script2 = SLInstructs::where('InstructSpot', 'Phone Script: Internal Affairs')->first();
        return view('vendor.openpolice.volun.volunScript-cache', ['script1' => $script1->instructHTML, 'script2' => $script2->instructHTML]);
    }
    
    public function deptEditFaqHTML()
    {
        $instruct = SLInstructs::where('InstructSpot', 'Volunteer Data Mining FAQs')->first();
        //echo '<textarea>'; print_r($instruct); echo '</textarea>';
        if (isset($instruct->InstructHTML)) return $instruct->InstructHTML;
        return '';
    }
    
    public function deptEditCheck()
    {
        $this->v["content"] = '<div class="p20"></div>
            <h1 class="slBlueDark" style="margin-bottom: 5px;">Department Info: Volunteer Checklist</h1>
            <a href="/volunteer"><i class="fa fa-caret-left"></i> Back To Department List</a>' 
            . $this->deptEditChecklistHTML() . '<div class="p20"></div>';
        return view( 'vendor.survloop.master', $this->v );
    }
    
    
    public function saveDefaultState(Request $request)
    {
        $this->admControlInit($request);
        if ($this->REQ->has('newState')) $this->v["yourContact"]->update([ "PrsnAddressState" => $this->REQ->newState ]);
        if ($this->REQ->has('newPhone')) $this->v["yourContact"]->update([ "PrsnPhoneMobile" => $this->REQ->newPhone ]);
        exit;
    }
    
    protected function printSidebarLeaderboard() 
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        return view( 'vendor.openpolice.volun.volun-sidebar-leaderboard', ["leaderboard" => $this->v["leaderboard"]] )->render();
    }
    
    public function printStars(Request $request)
    {
        $this->admControlInit($request, '/volunteer/stars');
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        $this->v["yourStats"] = array();
        if ($this->v["leaderboard"]->UserInfoStars && sizeof($this->v["leaderboard"]->UserInfoStars) > 0)
        {
            foreach ($this->v["leaderboard"]->UserInfoStars as $i => $volunUser)
            {
                if ($volunUser->UserInfoUserID == $this->v["user"]->id) $this->v["yourStats"] = $volunUser;
            }
        }
        return view( 'vendor.openpolice.volun.stars', $this->v );
    }
    
    public function volunProfileAdm(Request $request, $uid)
    {
        $this->admControlInit($request, '/dashboard/volun/stars');
        return $this->volunProfile($request, $uid, true);
    }
    
    public function volunProfile(Request $request, $uid, $isAdmin = false)
    {
        if (!$isAdmin) $this->admControlInit($request, '/volunteer/stars');
        $this->v["isAdminList"] = $isAdmin;
        $this->v["userObj"] = User::find($uid);
        $this->v["userStats"] = OPzVolunUserInfo::find($uid);
        $this->v["userInfo"] = OPPersonContact::find($this->v["userStats"]->UserInfoPersonContactID);
        $deptEdits = array();
        $recentEdits = OPzVolunEditsDepts::where('EditDeptUser', $uid)
            ->orderBy('EditDeptVerified', 'desc')->get();
        if ($recentEdits && sizeof($recentEdits) > 0)
        {
            foreach ($recentEdits as $i => $edit)
            {
                $iaEdit  = OPzVolunEditsOvers::where('EditOverEditDeptID', $edit->EditDeptID)
                    ->where('OverType', 303)
                    ->first();
                $civEdit = OPzVolunEditsOvers::where('EditOverEditDeptID', $edit->EditDeptID)
                    ->where('OverType', 302)
                    ->first();
                $userObj = User::find($edit->EditDeptUser);
                $deptEdits[] = [
                    $userObj->printUsername(true, '/dashboard/volun/user/'), 
                    $edit, 
                    $iaEdit, 
                    $civEdit
                ];
            }
        }
        //echo '<pre>'; print_r($deptEdits); echo '</pre>';
        $this->v["recentEdits"] = '';
        foreach ($deptEdits as $deptEdit)
        {
            $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                "user"         => $deptEdit[0], 
                "deptRow"      => OPDepartments::find($deptEdit[1]->EditDeptDeptID), 
                "deptEdit"     => $deptEdit[1], 
                "deptType"     => $GLOBALS["DB"]->getDefValue('Types of Departments', $deptEdit[1]->EditDeptType),
                "iaEdit"       => $deptEdit[2], 
                "civEdit"      => $deptEdit[3]
            ])->render();
        }
        return view( 'vendor.openpolice.admin.volun.volunProfile', $this->v );
    }
    
    
    public function fixURL($str)
    {
        $str = trim($str);
        if ($str == '' || strtolower($str) == 'none') return '';
        if (substr($str, 0, 1) == '@') return 'https://twitter.com/' . substr($str, 1);
        if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
        {
            $str = 'http:' . ((substr($str, 0, 2) != '//') ? '//' : '') . $str;
        }
        return $str;
    }
    
}
