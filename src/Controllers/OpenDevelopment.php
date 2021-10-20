<?php
/**
  * OpenDevelopment is a mid-level class which handles the
  * public area of the website for development documentation.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.1
  */
namespace FlexYourRights\OpenPolice\Controllers;

use FlexYourRights\OpenPolice\Controllers\OpenReportToolsAdmin;

class OpenDevelopment extends OpenReportToolsAdmin
{
    /**
     * Map and print the navigation for the software documentation are.
     *
     * @return string
     */
    protected function printNavDevelopmentArea($nID)
    {
        $docuNav = [
            [
                'About the Web App',
                [
                    [
                        '/web-app-workflows',
                        'Web App Workflows'
                    ]
                ]
            ],
            [
                'For Web Developers',
                [
                    [
                        '/how-to-install-local-openpolice',
                        'Install Local Copy of OpenPolice',
                        [
                            ['#n3072', 'Install Homestead'],
                            ['#n3071', 'Install Laravel'],
                            ['#n2694', 'Install OpenPolice']
                        ]
                    ],
                    [
                        '/web-app-technical-specs',
                        'General Technical Background'
                    ]
                ]
            ],
            [
                'Codebase Orientation',
                [
                    [
                        '/code-package-files-folders-and-classes',
                        'OpenPolice Package Files'
                    ],
                    [
                        'https://survloop.org/package-files-folders-classes',
                        'Survloop Package Files'
                    ]
                ]
            ]
        ];
        return view(
            'vendor.openpolice.inc-documentation-navigation',
            [
                "docuNav"  => $docuNav,
                "currPage" => $this->getCurrPage()
            ]
        )->render();
    }

}