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
use OpenPolice\Controllers\OpenPolice;
use OpenPolice\Controllers\VolunteerLeaderboard;
use SurvLoop\Controllers\AdminMenu;

class OpenPoliceAdmin extends AdminMenu
{
    private $currUser = null;
    private $currPage = '';
    
    function __construct($currUser = null, $currPage = '')
    {
        $this->currUser = $currUser;
        $this->currPage = $currPage;
    }
    
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
