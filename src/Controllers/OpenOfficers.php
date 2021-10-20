<?php
/**
  * OpenOfficers is a mid-level class which handles most functions
  * for managing the officer-based reporting from the database.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.5
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use App\Models\OPOfficersVerified;
use App\Models\OPOfficers;
use App\Models\OPPersonContact;
use App\Models\OPPhysicalDesc;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintOversight;
use App\Models\OPLinksComplimentOversight;
use App\Models\OPLinksOfficerDept;
use FlexYourRights\OpenPolice\Controllers\OpenDepts;

class OpenOfficers extends OpenDepts
{
    /**
     * Prints listing of officers with allegations and commendations on a
     * department's profile page. The variable $d passed to the view
     * contains a bunch of department data loaded from DepartmentScores.
     *
     * @return string
     */
    protected function printDeptOfficerComplaints()
    {
        // Double-checking pre-requisites for the department page load, generally
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }

        // Load objects with extra info for this department's verified officers
        $officerObjs = [];
        $officersVerif = $this->getDeptOfficerRecords($this->v["deptID"]);
        if ($officersVerif->isNotEmpty()) {
            foreach ($officersVerif as $offVer) {
                $officerObjs[] = new VerifiedOfficer($offVer);
            }
        }

        // Return the HTML back from this custom printing of a Node within
        // the Tree that generates the department profile page.
        return view(
            'vendor.openpolice.nodes.2720-dept-page-officer-listing',
            [
                "d"        => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]],
                "officers" => $officerObjs
            ]
        )->render();
    }

    /**
     * Retrieve all the verified officer records tied to one department
     * along with the officers name information.
     *
     * @param  int $deptID
     * @return App\Models\OPOfficersVerified
     */
    protected function getDeptOfficerRecords($deptID = -3)
    {
        return DB::table('op_officers_verified')
            ->join('op_person_contact', 'op_officers_verified.off_ver_person_id',
                '=', 'op_person_contact.prsn_id')
            //->where('op_officers_verified.off_ver_cnt_complaints', '>', 0)
            ->whereIn('op_officers_verified.off_ver_id', function($query) use ($deptID)
            {
                $query->select('lnk_off_dept_officer_id')
                    ->from(with(new OPLinksOfficerDept)->getTable())
                    ->where('lnk_off_dept_dept_id', $deptID);
            })
            ->select(
                'op_officers_verified.*',
                'op_person_contact.prsn_name_prefix',
                'op_person_contact.prsn_name_first',
                'op_person_contact.prsn_nickname',
                'op_person_contact.prsn_name_middle',
                'op_person_contact.prsn_name_last',
                'op_person_contact.prsn_name_suffix'
            )
            ->get();
    }


}

/**
 * This helper class loads everything one needs
 * to know about a specific verified officer.
 */
class VerifiedOfficer
{
    public $id          = 0;    // core verified officer record ID#
    public $rec         = null; // core verified officer record

    public $complaints  = []; // complaints linked to original officer records
    public $officerRecs = []; // original/raw officer records created by complainant
    public $allegations = []; // allegation information tied to officer record
    public $departments = []; // not needed for department page

    // could extend to stats, etc

    /**
     * This class can construct with full lookups of one verified officer's
     * complaints for use of counting allegations, or other analysis.
     *
     * @param  App/Models/OPOfficersVerified $rec
     * @return void
     */
    public function __construct($rec = null)
    {
        $this->rec = $rec;
        if ($this->rec && isset($this->rec->off_ver_id)) {
            $this->id = $this->rec->off_ver_id;
        }
        $this->loadOfficerComplaints();


        // ..?..


    }

    /**
     * Load complaints, original officer records, and allegations.
     *
     */
    public function loadOfficerComplaints()
    {





        // ..?..





    }

}
