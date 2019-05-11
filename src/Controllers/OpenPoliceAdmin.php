<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use SurvLoop\Models\User;
use SurvLoop\Models\SLDefinitions;
use SurvLoop\Models\SLNode;
use OpenPolice\Models\OPComplaints;
use OpenPolice\Models\OPAllegations;
use OpenPolice\Models\OPOfficers;
use OpenPolice\Models\OPPersonContact;
use OpenPolice\Models\OPDepartments;
use OpenPolice\Models\OPOversight;
use OpenPolice\Models\OPLinksComplaintDept;
use OpenPolice\Models\OPCustomers;
use OpenPolice\Models\OPZeditDepartments;
use OpenPolice\Models\OPZeditOversight;
use OpenPolice\Models\OPzVolunStatDays;
use OpenPolice\Models\OPzVolunUserInfo;
use SurvLoop\Models\SLEmails;
use SurvLoop\Models\SLEmailed;
use OpenPolice\Models\OPzComplaintEmailed;
use OpenPolice\Models\OPzComplaintReviews;
use OpenPolice\Controllers\OpenPolice;
use OpenPolice\Controllers\VolunteerLeaderboard;
use SurvLoop\Controllers\Admin\AdminController;

class OpenPoliceAdmin extends AdminController
{
    private $currUser = null;
    private $currPage = '';
    
    function __construct($currUser = null, $currPage = '')
    {
        $this->currUser = $currUser;
        $this->currPage = $currPage;
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
