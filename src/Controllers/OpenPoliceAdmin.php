<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SLDefinitions;
use App\Models\SLNode;

use App\Models\OPComplaints;
use App\Models\OPAllegations;
use App\Models\OPOfficers;
use App\Models\OPPersonContact;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPCustomers;             

use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;
use App\Models\OPzVolunStatDays;
use App\Models\OPzVolunUserInfo;

use App\Models\SLEmails;
use App\Models\SLEmailed;
use App\Models\OPzComplaintEmailed;
use App\Models\OPzComplaintReviews;

use OpenPolice\Controllers\OpenPoliceReport;
use OpenPolice\Controllers\VolunteerLeaderboard;
use SurvLoop\Controllers\AdminSubsController;

class OpenPoliceAdmin extends AdminSubsController
{
    public $classExtension     = 'OpenPoliceAdmin';
    public $treeID             = 1;
    
    public function initPowerUser($uID = -3)
    {
        if (!$this->v["user"] || intVal($this->v["user"]->id) <= 0
            || !$this->v["user"]->hasRole('administrator|staff|databaser|volunteer')) {
            return $this->redir('/');
        }
        if ($uID <= 0) $uID = $this->v["uID"];
        $this->v["yourUserInfo"] = OPzVolunUserInfo::where('UserInfoUserID', $uID)
            ->first();
        if (!$this->v["yourUserInfo"]) {
            $this->v["yourUserInfo"] = new OPzVolunUserInfo;
            $this->v["yourUserInfo"]->UserInfoUserID = $uID;
            $this->v["yourUserInfo"]->save();
        }
        $this->v["yourUserContact"] = [];
        if (!isset($this->v["yourUserInfo"]->UserInfoPersonContactID) 
            || intVal($this->v["yourUserInfo"]->UserInfoPersonContactID) <= 0) {
            $thisUser = User::select('email')->find($uID);
            $this->v["yourUserContact"] = new OPPersonContact;
            $this->v["yourUserContact"]->PrsnEmail = $thisUser->email;
            $this->v["yourUserContact"]->save();
            $this->v["yourUserInfo"]->UserInfoPersonContactID = $this->v["yourUserContact"]->PrsnID;
            $this->v["yourUserInfo"]->save();
        } else {
            $this->v["yourUserContact"] = OPPersonContact::find($this->v["yourUserInfo"]->UserInfoPersonContactID);
        }
        return [ $this->v["yourUserInfo"], $this->v["yourUserContact"] ];
    }
    
    public function loadAdmMenu()
    {
        if (isset($this->v["user"])) {
            $published = $flagged = 0;
            /* $chk = DSStories::where('StryStatus', $GLOBALS["SL"]->def->getID('Story Status', 'Published'))
                ->select('StryID')
                ->get();
            $published = $chk->isEmpty();
            $flagIDs = [];
            $flags = SLSessEmojis::where('SessEmoTreeID', 1)
                ->where('SessEmoDefID', 194)
                ->select('SessEmoRecID')
                ->get();
            if ($flags->isNotEmpty()) {
                foreach ($flags as $f) {
                    if (!in_array($f->SessEmoRecID, $flagIDs)) $flagIDs[] = $f->SessEmoRecID;
                }
            }
            $chk = DSStories::whereIn('StryID', $flagIDs)
                ->where('StryStatus', $GLOBALS["SL"]->def->getID('Story Status', 'Published'))
                ->select('StryID')
                ->get();
            $flagged = $chk->count(); */
            $treeMenu = [];
            if ($this->v["user"]->hasRole('administrator|staff|databaser')) {
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Complaints', '<i class="fa fa-star"></i>', 1, [
                    $this->admMenuLnk('/dash/all-complete-complaints', 'Complete Complaints'), 
                    $this->admMenuLnk('/dash/all-incomplete-complaints', 'Incomplete Complaints'), 
                    $this->admMenuLnk('javascript:;', 'Department Research', '', 1, [
                            $this->admMenuLnk('/dash/volunteer', 'Department Dashboard'),
                            $this->admMenuLnk('/dash/verify-next-department', 'Verify Random Dept')
                        ]),
                    $this->admMenuLnk('/dash/manage-attorneys', 'Manage Attorneys'),
                    $this->admMenuLnk('/dash/team-resources',   'Team Resources')
                    ]);
                /* $treeMenu[] = $this->admMenuLnk('javascript:;', 'Oversight', 
                    '<i class="fa fa-eye" aria-hidden="true"></i>', 1, [
                    $this->admMenuLnk('/dash/departments', 'Departments'), 
                    $this->admMenuLnk('/dash/oversight',   'Oversight Agencies'), 
                    $this->admMenuLnk('/dash/officers',    'Police Officers'), 
                    $this->admMenuLnk('/dash/attorneys',   'Attorneys')
                    ]); */
                if (!$this->v["user"]->hasRole('staff')) {
                    $treeMenu[0][4][] = $this->admMenuLnkContact(false);
                    $treeMenu = $this->addAdmMenuBasics($treeMenu);
                    $treeMenu[4][4][] = $this->admMenuLnk('/dash/volunteer-edits-history', 'Volunteer History');
                } else {
                    $treeMenu[0][4][] = $this->admMenuLnk('/dash/volunteer-edits-history', 'Volunteer History');
                }
                return $treeMenu;
            } elseif ($this->v["user"]->hasRole('volunteer')) {
                $treeMenu[] = $this->admMenuLnk('/dash/volunteer', 'Police Departments List');
                $treeMenu[] = $this->admMenuLnk('/dash/verify-next-department', 'Verify A Dept.');
                $this->initPowerUser();
                if ($this->v["yourUserInfo"] && isset($this->v["yourUserInfo"]->UserInfoStars)) {
                    $stars = '<div class="mT10 mB5"><div class="disIn mL5"><nobr>';
                    for ($s = 0; $s < $this->v["yourUserInfo"]->UserInfoStars; $s++) {
                        if ($s > 0 && $s%5 == 0) {
                            $stars .= '</nobr></div>' . (($s > 0 && $s%20 == 0) ? '</div><div>' : '') 
                                . '<div class="mL10 disIn"><nobr>';
                        }
                        $stars .= '<img src="/openpolice/star1.png" border=0 height=15 class="mLn10" >';
                    }
                    $stars .= '</nobr></div></div>';
                    $treeMenu[] = $this->admMenuLnk('/dash/volunteer-stars', 
                        $stars . 'You Have ' . number_format($this->v["yourUserInfo"]->UserInfoStars) . ' Stars');
                }
                return $treeMenu;
            }
        }
        $treeMenu = $this->addAdmMenuHome();
        return $treeMenu;
    }
    
    protected function initExtra(Request $request)
    {
        if (!isset($this->v["currPage"])) $this->v["currPage"] = ['/dashboard', ''];
        if (trim($this->v["currPage"][0]) == '') $this->v["currPage"][0] = '/dashboard';
        $this->v["allowEdits"] = ($this->v["user"]->hasRole('administrator|staff'));
        $this->v["management"] = ($this->v["user"]->hasRole('administrator|staff'));
        $this->v["volunOpts"] = 1;
        if ($GLOBALS["SL"]->REQ->session()->has('volunOpts')) {
            $this->v["volunOpts"] = $GLOBALS["SL"]->REQ->session()->get('volunOpts');
        }
        if (!session()->has('opcChks') || !session()->get('opcChks') || $request->has('refresh')) {
            $chk = OPComplaints::where('ComPublicID', null)
                ->where('ComStatus', 'NOT LIKE', $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete'))
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $i => $complaint) {
                    $complaint->update([ 'ComPublicID' => $GLOBALS["SL"]->genNewCorePubID('Complaints') ]);
                }
            }
            session()->put('opcChks', true);
        }
        return true;
    }
    
    public function dashboardDefault(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('administrator|staff|databaser')) {
            if ($user->hasRole('volunteer')) return $this->redir('/dash/volunteer');
            return $this->redir('/');
        }
        return $this->redir( '/dashboard/complaints' );
    }
    
    protected function loadSearchSuggestions()
    {    
        $this->v["searchSuggest"] = [];
        $deptCitys = OPDepartments::select('DeptAddressCity')
            ->distinct()
            ->get();
        if ($deptCitys->isNotEmpty()) {
            foreach ($deptCitys as $dept) {
                if (!in_array($dept->DeptAddressCity, $this->v["searchSuggest"]) && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCity);
                }
            }
        }
        $deptCounties = OPDepartments::select('DeptAddressCounty')
            ->distinct()
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                if (!in_array($dept->DeptAddressCounty, $this->v["searchSuggest"]) && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCounty);
                }
            }
        }
        return true;
    }
    
    function clearIncompletes()
    {
        /*
        if (!isset($_SESSION["clearIncompletes"])) {
            $qmen = $delComs = [];
            $qman = "SELECT * FROM `Complaints` WHERE `ComCreated` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND (`ComSubmissionProgress` < '1' OR `ComSummary` IS NULL)";
            $chk = mysqli_query($GLOBALS["SL"], $qman); // echo $qman . '<br />';
            if ($chk && mysqli_num_rows($chk) > 0) { 
                while ($row = mysqli_fetch_array($chk)) { $delComs[] = $row["ComID"]; }
                $qmen[] = "DELETE FROM `Civilians` WHERE `CivComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Scenes` WHERE `ScnComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Incidents` WHERE `IncComplaintID` IN ('" . implode("', '", $delComs) . "')";
                $qmen[] = "DELETE FROM `Complaints` WHERE `ComID` IN ('" . implode("', '", $delComs) . "')";
            }
            $qmen[] = "DELETE FROM `AuthUser` WHERE `UserCreated` < '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n"), date("j")-2, date("Y"))) . "' AND `UserName` IS NULL AND `UserPassword` IS NULL AND `UserID` NOT IN (SELECT `CivUserID` FROM `Civilians`)";
            foreach ($qmen as $qman) {
                $chk = mysqli_query($GLOBALS["SL"], $qman); // echo $qman . '<br />';
            }
            $_SESSION["clearIncompletes"] = true;
        }
        */
    }
    
    
    
    
    public function listOfficers(Request $request)
    {
        $this->admControlInit($request, '/dashboard/officers');
        $this->v["officers"] = OPOfficers::get();
        return view('vendor.openpolice.admin.lists.officers', $this->v)->render();
    }
    
    public function listDepts(Request $request)
    {
        $this->admControlInit($request, '/dashboard/depts');
        $this->v["deptComplaints"] = $this->v["deptAllegs"] = $deptList = [];
        $comDeptLnks = OPLinksComplaintDept::all();
        if ($comDeptLnks->isNotEmpty()) {
            foreach ($comDeptLnks as $lnk) {
                if (!in_array($lnk->LnkComDeptDeptID, $deptList)) {
                    $deptList[] = $lnk->LnkComDeptDeptID;
                }
                if (!isset($this->v["deptComplaints"][$lnk->LnkComDeptDeptID])) {
                    $this->v["deptComplaints"][$lnk->LnkComDeptDeptID] = [];
                    $this->v["deptAllegs"][$lnk->LnkComDeptDeptID] = [];
                }
                $this->v["deptComplaints"][$lnk->LnkComDeptDeptID][] = $lnk->LnkComDeptComplaintID;
            }
        }
        $this->v["departments"] = OPDepartments::whereIn('DeptID', $deptList)->get();
        return view('vendor.openpolice.admin.lists.depts', $this->v)->render();
    }
    
    public function quickAssign(Request $request)
    {
        $this->admControlInit($request);
        if ($request->OverID > 0 && $request->DeptID > 0) {
            $over = OPOversight::find($request->OverID);
            $over->OverDeptID = $request->DeptID;
            $over->save();
        }
        return $this->redir('/dashboard/overs#o'.$request->OverID);
    }
    
    public function listLegal(Request $request)
    {
        $this->admControlInit($request, '/dashboard/legal');
        $this->v["attorneys"] = OPCustomers::where('CustType', 'Attorney')->get();
        return view('vendor.openpolice.admin.lists.legal', $this->v)->render();
    }
    
    public function listAcademic(Request $request)
    {
        $this->admControlInit($request, '/dashboard/academic');
        $this->v["academic"] = OPCustomers::where('CustType', 'Academic')->get();
        return view('vendor.openpolice.admin.lists.academic', $this->v)->render();
    }
    
    public function listMedia(Request $request)
    {
        $this->admControlInit($request, '/dashboard/media');
        $this->v["journalists"] = OPCustomers::where('CustType', 'Journalist')->get();
        return view('vendor.openpolice.admin.lists.media', $this->v)->render();
    }
    
}
