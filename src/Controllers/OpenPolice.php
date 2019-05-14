<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\OPLinksComplaintDept;
use App\Models\OPPersonContact;
use App\Models\OPPhysicalDesc;
use App\Models\OPzVolunUserInfo;
use OpenPolice\Controllers\OpenDashAdmin;
use OpenPolice\Controllers\OpenInitExtras;

class OpenPolice extends OpenInitExtras
{
    protected function customNodePrint($nID = -3, $tmpSubTier = [], $nIDtxt = '', $nSffx = '', $currVisib = 1)
    {
        // Main Complaint Survey
        if (in_array($nID, [145, 920])) {
            return $this->printDeptSearch($nID);
        } elseif ($nID == 203) {
            $this->initBlnkAllegsSilv();
        } elseif (in_array($nID, [270, 973])) {
            return $this->printEndOfComplaintRedirect($nID);
            
        // Home Page
        } elseif ($nID == 1876) {
            return view('vendor.openpolice.nodes.1876-home-page-hero-credit')->render();
        //} elseif ($nID == 1848) {
        //    return view('vendor.openpolice.nodes.1848-home-page-disclaimer-bar')->render();
                
        // FAQ
        } elseif ($nID == 1884) {
            $GLOBALS["SL"]->addBodyParams('onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"');
            
        // Public Departments Accessibility Overview
        } elseif ($nID == 1968) {
            return $this->printDeptAccScoreTitleDesc($nID);
        } elseif ($nID == 1816) {
            return $this->printDeptAccScoreBars($nID);
        } elseif (in_array($nID, [1863, 1858]) || $nID == 2013) {
            return $this->publicDeptAccessMap($nID);
            
        } elseif ($nID == 1907) { // Donate Social Media Buttons
            return view('vendor.openpolice.nodes.1907-donate-share-social')->render();
        } elseif (in_array($nID, [859, 1454])) {
            return $this->printDeptOverPublic($nID);
                
        // How We Rate Departments Page
        } elseif ($nID == 1127) {
            foreach ([1827, 1825, 1829, 1831, 1833, 1837, 1806, 1835, 1, 2, 3, 4, 5, 6, 7] as $n) {
                $GLOBALS["SL"]->addHshoo('/how-we-rate-departments#n' . $n);
            }
            
        // Department Profile
        } elseif ($nID == 1779) {
            return $this->printDeptComplaints($nID);
        } elseif ($nID == 1099) {
            return $this->printDeptPage1099($nID);
            
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
               
        // User Profile
        } elseif ($nID == 1893) {
            return $this->printProfileMyComplaints($nID);
            
        // Complaint Report
        } elseif (in_array($nID, [1374, 1729])) {
            return $this->reportAllegsWhy($nID);
        } elseif ($nID == 1373) {
            return $this->reportStory($nID);
        } elseif (in_array($nID, [2330, 2332])) {
            return $this->chkGetReportDept($this->sessData->getLatestDataBranchID());
        } elseif (in_array($nID, [1382, 1734])) {
            return $this->getReportDept($this->sessData->getLatestDataBranchID());
        } elseif (in_array($nID, [1690, 1747])) {
            return $this->getReportByLine();
        } elseif (in_array($nID, [1687, 1731])) {
            return $this->getReportWhenLine();
        } elseif (in_array($nID, [1688, 1732])) {
            return $this->getReportWhereLine($nID);
        } elseif (in_array($nID, [1467, 1733])) {
            return ['Privacy Setting', $this->getReportPrivacy($nID)];
        } elseif ($nID == 1468) {
            return $this->getCivReportNameHeader($nID);
        } elseif ($nID == 1476) {
            return $this->getOffReportNameHeader($nID);
        } elseif (in_array($nID, [1795, 2266, 2335])) {
            $uploads = $this->getUploadsMultNodes($this->cmplntUpNodes, $this->v["isAdmin"], $this->v["isOwner"]);
            return '<h3 class="mT0 slBlueDark">' . (($uploads && sizeof($uploads) > 1) ? 'Uploads' : 'Upload') . '</h3>'
                . view('vendor.survloop.reports.inc-uploads', [ "uploads" => $uploads ])->render();
        } elseif ($nID == 1478) {
            return [ $this->getCivSnstvFldsNotPrinted($this->sessData->getLatestDataBranchID()) ];
        } elseif ($nID == 1511) {
            return $this->reportCivAddy($nID);
        } elseif ($nID == 1519) {
            return [ $this->getOffSnstvFldsNotPrinted($this->sessData->getLatestDataBranchID()) ];
        } elseif ($nID == 1566) {
            return $this->getOffProfan();
        } elseif ($nID == 1567) {
            return $this->getCivProfan();
        } elseif ($nID == 1891) {
            return $this->getReportOffAge($nID);
        } elseif ($nID == 1574) {
            return $this->reportEventTitle($this->sessData->getLatestDataBranchID());
        } elseif ($nID == 1710) {
            return $this->printReportShare();
        } elseif ($nID == 1707) {
            return $this->printGlossary();
        } elseif ($nID == 1708) {
            return $this->printFlexArts();
        } elseif ($nID == 1753) {
            return $this->printFlexVids();
        } elseif ($nID == 1712) {
            return $this->printComplaintAdmin();
        } elseif ($nID == 1713) {
            return $this->printComplaintOversight();
        } elseif ($nID == 1714) {
            return $this->printComplaintOwner();
        } elseif ($nID == 1780) {
            return $this->printMfaInstruct();
        } elseif ($nID == 2164) {
            return $this->printComplaintSessPath();
            
        // Staff Area Nodes
        } elseif ($nID == 1418) {
            return $this->printComplaintListing();
        } elseif ($nID == 1420) {
            return $this->printComplaintListing('incomplete');
        } elseif ($nID == 1939) {
            return $this->printPartnersOverview();
        } elseif ($nID == 2169) {
            return $this->printPartnerCapabilitiesOverview();
        } elseif ($nID == 2166) {
            return $this->printManageAttorneys();
        } elseif ($nID == 2171) {
            return $this->printManageAttorneys('Organization');
        } elseif ($nID == 1924) {
            return $this->initPartnerCaseTypes($nID);
        } elseif ($nID == 2181) {
            if ($this->sessData->dataSets["Partners"][0]->PartType 
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
            return view('vendor.openpolice.nodes.1261-volun-dept-edit-wiki-stats', $this->v)->render();
        } elseif ($nID == 1809) {
            return view('vendor.openpolice.nodes.1809-volun-dept-edit-how-investigate', $this->v)->render();
        } elseif ($nID == 1227) {
            return view('vendor.openpolice.nodes.1227-volun-dept-edit-search-complaint', $this->v)->render();
        } elseif ($nID == 1231) {
            return view('vendor.openpolice.volun.volun-dept-edit-history', $this->v)->render();
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
        } elseif ($nID == 1359) {
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
        } elseif (in_array($nID, [2297])) {
            return $this->printNavDevelopmentArea($nID);
            
        }
        return '';
    }
    
    protected function customResponses($nID, $curr)
    {
        if ($nID == 2126) {
            if (isset($curr->responses[0]) && isset($curr->responses[0]->NodeResEng) 
                && isset($this->sessData->dataSets["Complaints"]) 
                && isset($this->sessData->dataSets["Complaints"][0]->ComPrivacy)) {
                switch ($this->sessData->dataSets["Complaints"][0]->ComPrivacy) {
                    case $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'):
                        $curr->responses[0]->NodeResEng = 'Yes, I agree to publish my complaint data on this website 
                            with <b>Full Transparency</b>.<div class="pL20 mL5 mT10">
                            We will publish your FULL complaint on OpenPolice.org. This includes your written story, 
                            the names of civilians and police officers, and all survey answers.
                            </div>';
                        break;
                    case $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public'):
                        $curr->responses[0]->NodeResEng = 'Yes, I agree to publish my complaint data on this website 
                            with <b>No Names Public</b>.<div class="pL20 mL5 mT10">
                            Only your multiple-choice answers will be published on OpenPolice.org. 
                            This will NOT include your written story or police officers\' names and badge numbers.
                            </div>';
                        break;
                    case $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous'):
                    case $GLOBALS["SL"]->def->getID('Privacy Types', 'Anonymized'):
                        $curr->responses[0]->NodeResEng = 'Yes, I agree to publish my <b>Anonymized</b> complaint data 
                            on this website.<div class="pL20 mL5 mT10">
                            Only your multiple-choice answers will be published on OpenPolice.org. 
                            This will NOT include your written story or police officers\' names and badge numbers.
                            </div>';
                        break;
                }
            }
        }
        return $curr;
    }
    
    protected function initAdmDash()
    {
        $this->v["isDash"] = true;
        if (!isset($this->v["openDash"])) {
            $this->v["openDash"] = new OpenDashAdmin;
        }
        return true;
    }
            
    protected function customNodePrintButton($nID = -3, $promptNotes = '')
    { 
        if (in_array($nID, [270, 973])) {
            return '<!-- no buttons, all done! -->';
        }
        return '';
    }
    

    
    protected function chkPersonRecs()
    {
        // This should've been automated via the data table subset option
        // but for now, I'm replacing that complication with this check...
        $found = false;
        foreach ([ ['Civilians', 'Civ'], ['Officers', 'Off'] ] as $type) {
            if (isset($this->sessData->dataSets[$type[0]]) && sizeof($this->sessData->dataSets[$type[0]]) > 0) {
                foreach ($this->sessData->dataSets[$type[0]] as $i => $civ) {
                    if (!isset($civ->{ $type[1] . 'PersonID' }) || intVal($civ->{ $type[1] . 'PersonID' }) <= 0) {
                        $new = new OPPersonContact;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . 'PersonID' => $new->getKey() ]);
                        $found = true;
                    }
                    if (!isset($civ->{ $type[1] . 'PhysDescID' }) 
                        || intVal($civ->{ $type[1] . 'PhysDescID' }) <= 0) {
                        $new = new OPPhysicalDesc;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . 'PhysDescID' => $new->getKey() ]);
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
    
    protected function afterCreateNewDataLoopItem($tbl = '', $itemID = -3)
    {
        if (in_array($tbl, ['Civilians', 'Officers']) && $itemID > 0) {
            $this->chkPersonRecs();
        }
        return true;
    }
    
    protected function uploadWarning($nID)
    {
        return 'WARNING: If documents show sensitive personal information, set this to "private." 
            This includes addresses, phone numbers, emails, or social security numbers.';
    }
    
}