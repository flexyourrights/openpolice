<?php
/**
  * OpenPolice the core top-level class for which extends both Survloop,
  * and most functions specific to OpenPolice.org.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since  v0.0.1
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OPPersonContact;
use App\Models\OPPhysicalDesc;
use FlexYourRights\OpenPolice\Controllers\OpenDashAdmin;
use FlexYourRights\OpenPolice\Controllers\OpenInitExtras;

class OpenPolice extends OpenInitExtras
{
    /**
     * Overrides primary Survloop printing of individual nodes from 
     * surveys and site pages. This is one of the main routing hubs
     * for OpenPolice.org customizations beyond Survloop defaults.
     *
     * @param  TreeNodeSurv $curr
     * @return string
     */
    protected function customNodePrint(&$curr = null)
    {
        $nID = $curr->nID;
        // Main Complaint Survey
        if (in_array($nID, [145, 920])) {
            return $this->printDeptSearch($nID);
        } elseif ($nID == 416) {
            $this->cleanDeptLnks();
        } elseif ($nID == 203) {
            $this->initBlnkAllegsSilv();
        } elseif ($nID == 3171) {
            return $this->printAllegAudit();
        } elseif (in_array($nID, [270, 973])) {
            $this->clearComplaintCaches();
            return $this->printEndOfComplaintRedirect($nID);
            
        // Home Page
        } elseif ($nID == 1876) {
            return view('vendor.openpolice.nodes.1876-home-page-hero-credit')->render();
        //} elseif ($nID == 1848) {
        //    return view('vendor.openpolice.nodes.1848-home-page-disclaimer-bar')->render();
        
        // FAQ
        } elseif ($nID == 1884) {
            $scroll = 'if (typeof bodyOnScroll === \'function\') bodyOnScroll();';
            $GLOBALS["SL"]->addBodyParams('onscroll="' . $scroll . '"');
            
        // Public Departments Accessibility Overview
        } elseif ($nID == 1816) {
            $GLOBALS["SL"]->addHshoo('#n1917');
            return $this->printDeptAccScoreBars($nID);
        } elseif (in_array($nID, [1863, 1858, 2013])) {
            return $this->publicDeptAccessMap($nID);
            
        } elseif ($nID == 1907) { // Donate Social Media Buttons
            return view('vendor.openpolice.nodes.1907-donate-share-social')->render();
        } elseif ($nID == 1454) {
            return $this->printDeptOverPublic($nID);
        } elseif ($nID == 2804) {
            return $this->printDeptOverPublicTop50s($nID);
                
        // How We Rate Departments Page
        } elseif ($nID == 1127) {
            $nodes = [
                1827, 1825, 1829, 1831, 1833, 1837, 1806, 1835, 
                1, 2, 3, 4, 5, 6, 7
            ];
            foreach ($nodes as $n) {
                $GLOBALS["SL"]->addHshoo('/how-we-rate-departments#n' . $n);
            }
            if ($GLOBALS["SL"]->REQ->has('test')) {

            }
            
        // Department Profile
        } elseif ($nID == 1779) {
            return $this->printDeptComplaints($nID);
        } elseif ($nID == 2706) {
            return $this->printDeptHeaderLoad($nID);
        } elseif ($nID == 2711) {
            return $this->printBasicDeptInfo($nID);
        } elseif ($nID == 2713) {
            return $this->printDeptCallsToAction($nID);
        } elseif ($nID == 2715) {
            return $this->printDeptReportsRecent($nID);
        } elseif ($nID == 2717) {
            return $this->printDeptProfileAccScore($nID);
        } elseif ($nID == 2718) {
            return $this->printDeptProfileHowToFile($nID);
        } elseif ($nID == 2720) {
            return $this->printDeptOfficerComplaints();


            
        // Partner Profiles
        } elseif ($nID == 2179) {
            return $this->printPartnersOverviewPublic($nID);
        } elseif ($nID == 1896) {
            return $this->printAttorneyReferrals($nID);
        } elseif (in_array($nID, [1961, 2062])) {
            return $this->publicPartnerHeader($nID);
        } elseif (in_array($nID, [1898, 2060])) {
            return $this->publicPartnerPage($nID);
        } elseif (in_array($nID, [2115, 2069])) {
            return $this->printPreparePartnerHeader($nID);
        } elseif ($nID == 2677) {
            return $this->publicPartnerPageClinicOnly($nID);
               
        // User Profile
        } elseif ($nID == 1893) {
            return $this->printProfileMyComplaints($nID);
            
        // Complaint Report Tools
        } elseif ($nID == 1712) {
            $this->saveComplaintAdmin();
            return $this->printComplaintAdmin();
        } elseif ($nID == 1713) {
            if ($this->chkOverUserHasCore()) { // Investigative Access
                $this->saveComplaintAdmin();
                return $this->printComplaintOversight();
            }
            return '<!-- no toolbox -->';
        } elseif ($nID == 1714) {
            $this->processOwnerUpdate();
            $this->saveComplaintAdmin();
            return $this->printComplaintOwner();
        } elseif ($nID == 2850) {
            return $this->printComplaintOwnerStatusForm();
        } elseif ($nID == 2852) {
            return $this->printComplaintOwnerPrivacy();
        } elseif ($nID == 1780) {
            return $this->printMfaInstruct();
        } elseif ($nID == 2844) {
            return $this->printComplaintAdminFirstReview();
        } elseif ($nID == 2842) {
            return $this->printComplaintAdminFormStatus();
        } elseif ($nID == 2846) {
            return $this->printComplaintAdminEmailForm();
        } elseif ($nID == 2848) {
            return $this->printComplaintAdminFormEdits();


        // Complaint Report
        } elseif ($nID == 3039) {
            return $this->getReportPublicID($nID);
        } elseif ($nID == 1374) {
            return $this->reportAllegsWhy($nID);
        } elseif ($nID == 1373) {
            return $this->reportStory($nID);
        } elseif (in_array($nID, [2330, 2332])) {
            $deptID = $this->getLoopLinkDeptID();
            if ($deptID == 18124) {
                return '<!-- Not sure about department -->';
            }
            return $this->chkGetReportDept($deptID);
        } elseif (in_array($nID, [1382, 1734])) {
            return $this->getReportDept($this->getLoopLinkDeptID());
        } elseif ($nID == 1690) {
            return $this->getReportByLine();
        } elseif ($nID == 3073) {
            return $this->getReportUserLine();
        } elseif ($nID == 1687) {
            return $this->getReportWhenLine();
        } elseif (in_array($nID, [1688, 1732])) {
            return $this->getReportWhereLine($nID);
        } elseif ($nID == 1691) {
            return [
                'Publishing Settings', 
                $this->getReportPrivacy($nID)
            ];
        } elseif ($nID == 1468) {
            return $this->getCivReportNameHeader($nID);
        } elseif (in_array($nID, [1505, 2637, 1506, 1507])) {
            return $this->getCivReportNameRow($nID);
        } elseif ($nID == 1520) {
            return $this->getOffReportDeptName($nID);
        } elseif ($nID == 1476) {
            return $this->getOffReportNameHeader($nID);
        } elseif ($nID == 1478) {
            $civID = $this->sessData->getLatestDataBranchID();
            return [ $this->getCivSnstvFldsNotPrinted($civID) ];
        } elseif ($nID == 1511) {
            return $this->reportCivAddy($nID);
        } elseif ($nID == 1519) {
            $civID = $this->sessData->getLatestDataBranchID();
            return [ $this->getOffSnstvFldsNotPrinted($civID) ];
        } elseif ($nID == 1566) {
            return $this->getOffProfan();
        } elseif ($nID == 1567) {
            return $this->getCivProfan();
        } elseif ($nID == 1891) {
            return $this->getReportOffAge($nID);
        } elseif ($nID == 1574) {
            return $this->reportEventTitle($this->sessData->getLatestDataBranchID());
        } elseif (in_array($nID, [3092, 3095, 3098, 3102, 3104, 3111, 3115, 
            3118, 3122, 3126, 3131, 3136, 3138, 3143, 3147, 3150, 3153, 3156])) {
            return $this->getAllegOffNameRow($nID);
        } elseif (in_array($nID, [])) {
            return $this->getEventCivNameRow($nID);
        } elseif ($nID == 1579) {
            return $this->getStopReasons($nID);
        } elseif ($nID == 1710) {
            return $this->printReportShare();
        } elseif ($nID == 2899) {
            return $this->reportDeptDesires($nID);
        } elseif ($nID == 1504) {
            return $this->reportCivCharges($nID);
        } elseif ($nID == 1501) {
            return $this->reportCivInjuries($nID);
        } elseif (in_array($nID, [1795, 2266, 2335])) {
            return $this->getReportUploads($nID);
        } elseif ($nID == 1707) {
            return $this->printGlossary();
        } elseif ($nID == 1753) {
            return $this->printFlexVids();
        } elseif ($nID == 1708) {
            return $this->printFlexArts();
        } elseif ($nID == 2164) {
            return $this->printComplaintSessPath();
        } elseif ($nID == 2632) {
            //$this->saveComplaintAdmin();
        } elseif ($nID == 3063) { // end of Toolbox
            $GLOBALS["SL"]->setFullPageLoaded(1000); 
        } elseif ($nID == 1385) {
            if ($GLOBALS["SL"]->REQ->has('refresh')) {
                $this->clearComplaintCaches();
            }
            $GLOBALS["SL"]->pageCSS .= ' #treeWrap1385, #treeWrap2766 { 
                width: 100%; max-width: 100%; padding-left: 0px; padding-right: 0px;
            } ';
            $this->saveComplaintOversight();
        //} elseif ($nID == 2634) {
            $this->processOwnerUpdate();
        } elseif (in_array($nID, [2635, 2378])) {
            $GLOBALS["SL"]->x["needsWsyiwyg"] = $this->v["needsWsyiwyg"] = true;
            
        // Complaint Listings
        } elseif (in_array($nID, [1418, 2384])) {
            $GLOBALS["SL"]->x["isPublicList"] = false;
            if ($nID == 2384) {
                $GLOBALS["SL"]->x["isPublicList"] = true;
                $GLOBALS["SL"]->pageView = 'public';
            } elseif ($GLOBALS["SL"]->REQ->has('refresh')) {
                $this->clearComplaintCaches();
                $GLOBALS["SL"]->forgetAllCachesOfTrees([1, 11, 42, 197]);
                if (trim($GLOBALS["SL"]->REQ->get('refresh')) == 'repeat') {
                    echo '<h2>Refresh Complaints, & Repeat ...</h2>' 
                        . date("H:i:s") . '<script type="text/javascript"> '
                        . 'setTimeout("window.location=\'?refresh=repeat\'", '
                        . '(30*60000)); </script>';
                    exit;
                }
            }
            $GLOBALS["SL"]->x["needsWsyiwyg"] 
                = $this->v["needsWsyiwyg"] 
                = true;
            return $this->printComplaintListing($nID);
        } elseif ($nID == 2377) {
            return $this->printComplaintReportForAdmin($nID);

        // Department Listings
        } elseif ($nID == 2958) {
            return $this->browseSearchDepts($nID);
        } elseif ($nID == 2960) {
            return $this->printSimpleDeptList($nID);
        } elseif (in_array($nID, [2375, 3058])) {
            return $this->printTopComplaintDepts($nID);

        } elseif (in_array($nID, [1221, 2091, 2093, 1219])) {
            return $this->printSearchResults($nID);

        // Staff Area Nodes
        } elseif ($nID == 1416) {
            if (in_array($this->v["uID"], [863])) {
                return '<h4>Staff Dashboard</h4>';
            }
        } elseif ($nID == 1420) {
            return $this->printComplaintListing($nID, 'incomplete');
        } elseif ($nID == 1939) {
            return $this->printPartnersOverview();
        } elseif ($nID == 2169) {
            return $this->printPartnerCapabilitiesOverview();
        } elseif ($nID == 2166) {
            return $this->printManagePartners($nID);
        } elseif ($nID == 2171) {
            return $this->printManagePartners($nID, 'Organization');
        } elseif ($nID == 1924) {
            return $this->initPartnerCaseTypes($nID);
        } elseif ($nID == 2181) {
            if ($this->sessData->dataSets["partners"][0]->part_type 
                == $GLOBALS["SL"]->def->getID('Partner Types', 'Organization')) {
                $GLOBALS["SL"]->setCurrPage('/dash/manage-organizations');
            } else {
                $GLOBALS["SL"]->setCurrPage('/dash/manage-attorneys');
            }
        } elseif ($nID == 2234) {
            return $this->printBetaTesters($nID);
            
        // Volunteer Area Nodes
        } elseif ($nID == 1211) {
            return $this->printVolunPriorityList();
        } elseif ($nID == 1755) {
            return $this->printVolunAllList();
        } elseif ($nID == 1217) {
            return $this->printVolunLocationForm();
        } elseif ($nID == 1225) {
            return $this->printDeptEditHeader();
        } elseif ($nID == 2162) {
            return $this->printDeptEditHeader2();
        } elseif ($nID == 1261) {
            return view(
                'vendor.openpolice.nodes.1261-volun-dept-edit-wiki-stats', 
                $this->v
            )->render();
        } elseif ($nID == 1809) {
            return view(
                'vendor.openpolice.nodes.1809-volun-dept-edit-how-investigate', 
                $this->v
            )->render();
        } elseif ($nID == 1227) {
            return view(
                'vendor.openpolice.nodes.1227-volun-dept-edit-search-complaint', 
                $this->v
            )->render();
        } elseif ($nID == 2962) {
            return view(
                'vendor.openpolice.nodes.2962-volun-dept-edit-search-oversight', 
                $this->v
            )->render();
        } elseif ($nID == 1231) {
            $hist = view(
                'vendor.openpolice.volun.volun-dept-edit-history', 
                $this->v
            )->render();
            return '<div class="nodeAnchor">'
                . '<a id="deptEdits" name="deptEdits"></a></div>'
                . $GLOBALS["SL"]->printAccard('Past Volunteer Edits', $hist);
        } elseif ($nID == 1338) {
            return $GLOBALS["SL"]->getBlurbAndSwap('Volunteer Checklist');
        } elseif ($nID == 1340) {
            return $this->redirAfterDeptEdit();
        } elseif ($nID == 1344) {
            return $this->redirNextDeptEdit();
        } elseif ($nID == 1346) {
            return $this->volunStars();
        } elseif ($nID == 1351) {
            $this->initAdmDash();
            return $this->v["openDash"]->volunDepts();
            
        // Admin Dashboard Page
        } elseif (in_array($nID, [2345, 2961])) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashTopLevStats();
        } elseif (in_array($nID, [1359, 2965])) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashSessGraph();
        } elseif ($nID == 1342) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashPercCompl();
        } elseif ($nID == 1361) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashTopStats();
        } elseif ($nID == 1349) {
            $this->initAdmDash();
            return $this->v["openDash"]->volunStatsDailyGraph();
        } elseif ($nID == 2100) {
            $this->initAdmDash();
            return $this->v["openDash"]->volunStatsTable();
            
        // Software Development Area
        } elseif (in_array($nID, [2690, 2703, 2297, 2759, 2317])) {
            return $this->printNavDevelopmentArea($nID);

        }
        return '';
    }

    /**
     * Overrides default Survloop behavior for responses to multiple-choice questions.
     *
     * @param  int $nID
     * @param  SLNode &$curr
     * @return SLNode
     */
    protected function customResponses($nID, &$curr)
    {
        return $curr;
    }

    /**
     * Overrides primary Survloop printing of individual nodes from 
     * surveys and site pages. This is one of the main routing hubs
     * for OpenPolice.org customizations beyond Survloop defaults.
     *
     * @param  TreeNodeSurv $curr
     * @param  string $var
     * @return string
     */
    protected function customNodePrintVertProgress(&$curr = null, $val = null)
    {
        if (in_array($curr->nID, [1700, 1697, 1698, 1699, 3176])) {
            return $this->printReportNoResponseTime($curr, $val);
        }
        return null;
    }

    /**
     * Initializes the admin dashboard side-class.
     *
     * @return boolean
     */
    protected function initAdmDash()
    {
        $this->v["isDash"] = true;
        if (!isset($this->v["openDash"])) {
            $this->v["openDash"] = new OpenDashAdmin;
        }
        return true;
    }
    
    /**
     * Overrides or disables the default printing of survey Back/Next buttons.
     *
     * @param  int $nID
     * @param  string $promptNotes
     * @return string
     */
    protected function customNodePrintButton($nID = -3, $promptNotes = '')
    { 
        if (in_array($nID, [270, 973])) {
            return '<!-- no buttons, all done! -->';
        }
        return '';
    }
    
    /**
     * Look up the person contact record and physical description record
     * for a civilian or officer.
     *
     * @return boolean
     */
    protected function chkPersonRecs()
    {
        // This should've been automated via the data table subset option
        // but for now, I'm replacing that complication with this check...
        $found = false;
        $types = [
            ['civilians', 'civ'],
            ['officers',  'off']
        ];
        foreach ($types as $type) {
            if (isset($this->sessData->dataSets[$type[0]]) 
                && sizeof($this->sessData->dataSets[$type[0]]) > 0) {
                foreach ($this->sessData->dataSets[$type[0]] as $i => $civ) {
                    if (!isset($civ->{ $type[1] . '_person_id' }) 
                        || intVal($civ->{ $type[1] . '_person_id' }) <= 0) {
                        $new = new OPPersonContact;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . '_person_id' => $new->getKey() 
                        ]);
                        $found = true;
                    }
                    if (!isset($civ->{ $type[1] . '_phys_desc_id' }) 
                        || intVal($civ->{ $type[1] . '_phys_desc_id' }) <= 0) {
                        $new = new OPPhysicalDesc;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . '_phys_desc_id' => $new->getKey()
                        ]);
                        $found = true;
                    }
                }
            }
        }
        if ($found) {
            $this->sessData->refreshDataSets();
        }
        // // // //
        return true;
    }
    
    /**
     * Look up the record linking fields which should be skipped
     * when auto-creating a new loop item's database record.
     *
     * @return array
     */
    protected function newLoopItemSkipLinks($tbl = '')
    {
        // Until this can be auto-inferred for 
        // outgoing linkages to data subsets
        if ($tbl == 'civilians') {
            return [ 'civ_person_id', 'civ_phys_desc_id' ];
        } elseif ($tbl == 'officers') {
            return [ 'off_person_id', 'off_phys_desc_id' ];
        }
        return [];
    }
    
    /**
     * Double-check behavior after a new item has been created for a data loop.
     *
     * @param  string $tbl
     * @param  int $itemID
     * @return boolean
     */
    protected function afterCreateNewDataLoopItem($tbl = '', $itemID = -3)
    {
        if (in_array($tbl, ['civilians', 'officers']) && $itemID > 0) {
            $this->chkPersonRecs();
        }
        return true;
    }
    
    /**
     * Print warning message for uploading tool.
     *
     * @param  int $nID
     * @return string
     */
    protected function uploadWarning($nID)
    {
        return 'WARNING: If documents show sensitive personal information, set this to "private." 
            This includes addresses, phone numbers, emails, or social security numbers.';
    }
    
}