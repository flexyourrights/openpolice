<?php
/**
  * OpenPoliceAdminMenu is responsible for building the menu inside the dashboard area for all user types.
  *
  * Open Police Complaints
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since 0.0
  */
namespace OpenPolice\Controllers;

use SurvLoop\Controllers\AdminMenu;

class OpenPoliceAdminMenu extends AdminMenu
{
    public function loadAdmMenu($currUser = null, $currPage = '')
    {
        $this->currUser = $currUser;
        $this->currPage = $currPage;
        $treeMenu = [];
        if (isset($this->currUser)) {
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
            if ($this->currUser->hasRole('administrator|staff|databaser|brancher')) {
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Complaints', '<i class="fa fa-star"></i>', 1, [
                    $this->admMenuLnk('/dash/all-complete-complaints', 'Complete Complaints'), 
                    $this->admMenuLnk('/dash/all-incomplete-complaints', 'Incomplete Complaints'), 
                    $this->admMenuLnk('/dash/volunteer', 'Department List'),
                    $this->admMenuLnk('/dash/manage-partners', 'Manage Partners'),
                    $this->admMenuLnk('/dash/team-resources',   'Team Resources'),
                    $this->admMenuLnk('/dash/volunteer-edits-history', 'Volunteer History')
                    ]);
            } elseif ($this->currUser->hasRole('partner')) {
                $treeMenu[] = $this->admMenuLnk('javascript:;', 'Complaints', '<i class="fa fa-star"></i>', 1, [
                    $this->admMenuLnk('/dash/all-complete-complaints', 'Complete Complaints'), 
                    $this->admMenuLnk('/dash/all-incomplete-complaints', 'Incomplete Complaints'), 
                    $this->admMenuLnk('/dash/volunteer', 'Department List'),
                    $this->admMenuLnk('/dash/team-resources',   'Team Resources')
                    ]);
            } elseif ($this->currUser->hasRole('volunteer')) {
                $treeMenu[] = $this->admMenuLnk('/dash/volunteer', 'Police Departments List');
                $treeMenu[] = $this->admMenuLnk('/dash/verify-next-department', 'Verify A Dept.');
                if (isset($GLOBALS["SL"]->x["yourUserInfo"]) 
                    && isset($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars)) {
                    $stars = '<div class="mT10 mB5"><div class="disIn mL5"><nobr>';
                    for ($s = 0; $s < $GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars; $s++) {
                        if ($s > 0 && $s%5 == 0) {
                            $stars .= '</nobr></div>' . (($s > 0 && $s%20 == 0) ? '</div><div>' : '') 
                                . '<div class="mL10 disIn"><nobr>';
                        }
                        $stars .= '<img src="/openpolice/star1.png" border=0 height=15 class="mLn10" >';
                    }
                    $stars .= '</nobr></div></div>';
                    $treeMenu[] = $this->admMenuLnk('/dash/volunteer-stars', 
                        $stars . 'You Have ' . number_format($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoStars) . ' Stars');
                }
            }
        }
        //$treeMenu = $this->addAdmMenuHome();
        //return $treeMenu;
        return $this->addAdmMenuBasics($treeMenu);
    }
    
}