<?php 
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-gen-seeder.blade.php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OpenPoliceSLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {

    DB::table('SL_Databases')->insert([
            'DbID' => 3,
            'DbUser' => '0',
            'DbPrefix' => 'SL_',
            'DbName' => 'SurvLoop',
            'DbDesc' => 'All The Data Are Belong',
            'DbMission' => 'Empower you to design your complex databases, collect data with an easy user experience, and create an API to share the data with the world!',
            'DbTables' => '38',
            'DbFields' => '292'
        ]);
    
    DB::table('SL_Tables')->insert([
            'TblID' => 3,
            'TblDatabase' => '3',
            'TblAbbr' => 'Tweak',
            'TblName' => 'DesignTweaks',
            'TblEng' => 'Design Tweaks',
            'TblDesc' => 'Represents one modification to the existing database and user experience designs in SurvLoop. Information stored here is important for tracking history of changes and success of A/B testing in the user experience. This is the Core Data Table of SurvLoop\'s naked installation.',
            'TblGroup' => 'Users',
            'TblOrd' => '17',
            'TblExtend' => '0',
            'TblNumFields' => '7',
            'TblNumForeignKeys' => '1',
            'TblNumForeignIn' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 4,
            'TblDatabase' => '3',
            'TblAbbr' => 'Db',
            'TblName' => 'Databases',
            'TblEng' => 'Databases',
            'TblDesc' => 'Each record represents one Database being designed in SurvLoop, a collection of Tables, Fields, Definitions, and Business Rules often owned by a User. Information stored here can define core system settings.',
            'TblGroup' => 'Databases',
            'TblExtend' => '0',
            'TblNumFields' => '8',
            'TblNumForeignKeys' => '1',
            'TblNumForeignIn' => '8'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 5,
            'TblDatabase' => '3',
            'TblAbbr' => 'Tree',
            'TblName' => 'Tree',
            'TblEng' => 'Experiences',
            'TblDesc' => 'Each User Experience represents one collection of Nodes which one specific survey  process. Nodes are connected to each other to form a branching Tree which define all the details of this Experience. Often owned by a User, information stored here can define core system settings. ',
            'TblGroup' => 'Experiences',
            'TblOrd' => '5',
            'TblExtend' => '0',
            'TblNumFields' => '11',
            'TblNumForeignKeys' => '6',
            'TblNumForeignIn' => '13'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 6,
            'TblDatabase' => '3',
            'TblAbbr' => 'Rule',
            'TblName' => 'BusRules',
            'TblEng' => 'Business Rules',
            'TblDesc' => 'Each record represents one Business Rule which specifies certain requirements to be maintained in the Database. Implementing the Rules defined here is important for maintaining data integrity.',
            'TblGroup' => 'Databases',
            'TblOrd' => '4',
            'TblExtend' => '0',
            'TblNumFields' => '12',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 7,
            'TblDatabase' => '3',
            'TblAbbr' => 'Def',
            'TblName' => 'Definitions',
            'TblEng' => 'Definitions',
            'TblDesc' => 'Includes lists of possible ranges of values for various data Fields throughout the database, and other system settings. This is important for providing dynamic storage for key system terminology which may infrequently change.',
            'TblGroup' => 'Databases',
            'TblOrd' => '3',
            'TblExtend' => '0',
            'TblNumFields' => '7',
            'TblNumForeignKeys' => '1',
            'TblNumForeignIn' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 9,
            'TblDatabase' => '3',
            'TblAbbr' => 'Tbl',
            'TblName' => 'Tables',
            'TblEng' => 'Database Tables',
            'TblDesc' => 'Each record represents one Table in the Database, which is a collection of any number of Fields.',
            'TblGroup' => 'Databases',
            'TblOrd' => '1',
            'TblExtend' => '0',
            'TblNumFields' => '14',
            'TblNumForeignKeys' => '1',
            'TblNumForeignIn' => '6'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 10,
            'TblDatabase' => '3',
            'TblAbbr' => 'Fld',
            'TblName' => 'Fields',
            'TblEng' => 'Database Fields',
            'TblDesc' => 'Each record represents one data Field where specific information will be stored in the completed Database.',
            'TblNotes' => 'Notes assigned to these Field specifications are used as helpful tool-tip resources power users.',
            'TblGroup' => 'Databases',
            'TblOrd' => '2',
            'TblExtend' => '0',
            'TblNumFields' => '39',
            'TblNumForeignKeys' => '4',
            'TblNumForeignIn' => '4'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 11,
            'TblDatabase' => '3',
            'TblAbbr' => 'Cond',
            'TblName' => 'Conditions',
            'TblEng' => 'Conditions',
            'TblDesc' => 'Each record represents one specific Condition used to filter Database submissions. These are important for determining which Nodes to include during a User\'s Experience, and which finished submissions to include in public queries.',
            'TblGroup' => 'Experiences',
            'TblOrd' => '8',
            'TblExtend' => '0',
            'TblNumFields' => '9',
            'TblNumForeignKeys' => '4',
            'TblNumForeignIn' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 12,
            'TblDatabase' => '3',
            'TblAbbr' => 'Article',
            'TblName' => 'ConditionsArticles',
            'TblEng' => 'Condition Articles',
            'TblDesc' => 'Each record represents one Article which provides more information regarding Experience submissions which meet a Condition\'s requirements. This is important for provident automated, educational information to Users and the public. ',
            'TblType' => 'Subset',
            'TblGroup' => 'Experiences',
            'TblOrd' => '11',
            'TblExtend' => '0',
            'TblNumFields' => '3',
            'TblNumForeignKeys' => '1',
            'TblNumForeignIn' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 13,
            'TblDatabase' => '3',
            'TblAbbr' => 'CondNode',
            'TblName' => 'ConditionsNodes',
            'TblEng' => 'Condition Nodes',
            'TblDesc' => 'Each record represents a linkage between one Node or one Data Loop within this Experience. ',
            'TblType' => 'Linking',
            'TblGroup' => 'Experiences',
            'TblOrd' => '10',
            'TblExtend' => '0',
            'TblNumFields' => '3',
            'TblNumForeignKeys' => '3',
            'TblNumForeignIn' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 14,
            'TblDatabase' => '3',
            'TblAbbr' => 'CondVal',
            'TblName' => 'ConditionsVals',
            'TblEng' => 'Condition Values',
            'TblDesc' => 'Each record represents one Value to be compared with a User\'s Response when testing a specific Condition. This is important for comparing among a range of values. ',
            'TblType' => 'Subset',
            'TblGroup' => 'Experiences',
            'TblOrd' => '9',
            'TblExtend' => '0',
            'TblNumFields' => '2',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 15,
            'TblDatabase' => '3',
            'TblAbbr' => 'Node',
            'TblName' => 'Node',
            'TblEng' => 'Experience Nodes',
            'TblDesc' => 'Each record represents one Node in the branching Tree, defining the critical details of each User\'s unique Experience. Each Node could represent the start of a new branch of the Tree, a new question asked of a User, or a new page which will ask multiple questions.',
            'TblGroup' => 'Experiences',
            'TblOrd' => '6',
            'TblExtend' => '0',
            'TblNumFields' => '17',
            'TblNumForeignKeys' => '2',
            'TblNumForeignIn' => '12'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 16,
            'TblDatabase' => '3',
            'TblAbbr' => 'NodeRes',
            'TblName' => 'NodeResponses',
            'TblEng' => 'Node Responses',
            'TblDesc' => 'Each record represents one Response presented to the User when prompted by a specific Node.',
            'TblType' => 'Subset',
            'TblGroup' => 'Experiences',
            'TblOrd' => '7',
            'TblExtend' => '0',
            'TblNumFields' => '6',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 17,
            'TblDatabase' => '3',
            'TblAbbr' => 'NodeSave',
            'TblName' => 'NodeSaves',
            'TblEng' => 'Node Saves',
            'TblDesc' => 'Each record represents one User\'s Response to a specific Node during a specific Session. Among other quality control, this is important for comparing the efficacy of various A/B testing in the user experience.',
            'TblGroup' => 'Users',
            'TblOrd' => '20',
            'TblExtend' => '0',
            'TblNumFields' => '6',
            'TblNumForeignKeys' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 18,
            'TblDatabase' => '3',
            'TblAbbr' => 'PageSave',
            'TblName' => 'NodeSavesPage',
            'TblEng' => 'Page Saves',
            'TblDesc' => 'Each record represents one timestamp of a User\'s submission of a specific Page Node during a specific Session. This is important for quickly checking or reviewing the progress of Users through an Experience.',
            'TblGroup' => 'Users',
            'TblOrd' => '21',
            'TblExtend' => '0',
            'TblNumFields' => '3',
            'TblNumForeignKeys' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 19,
            'TblDatabase' => '3',
            'TblAbbr' => 'Sess',
            'TblName' => 'Sess',
            'TblEng' => 'User Session',
            'TblDesc' => 'Each record represents one User\'s Session while going through an Experience. This is important for saving a User\'s progress if they have to complete an Experience over the course of multiple browser sessions.',
            'TblGroup' => 'Users',
            'TblOrd' => '18',
            'TblExtend' => '0',
            'TblNumFields' => '10',
            'TblNumForeignKeys' => '6',
            'TblNumForeignIn' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 20,
            'TblDatabase' => '3',
            'TblAbbr' => 'SessLoop',
            'TblName' => 'SessLoops',
            'TblEng' => 'Session Loops',
            'TblDesc' => 'Each record represents one User\'s current Data Loop Item which they are editing, as related to their current Session. This is important for saving a User\'s progress if they have to complete an Experience over the course of multiple browser sessions.',
            'TblType' => 'Subset',
            'TblGroup' => 'Users',
            'TblOrd' => '19',
            'TblExtend' => '0',
            'TblNumFields' => '3',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 21,
            'TblDatabase' => '3',
            'TblAbbr' => 'UserAct',
            'TblName' => 'UsersActivity',
            'TblEng' => 'User Activity',
            'TblDesc' => 'Each record represents one log entry of a User\'s Activity, important for tracking system usage.',
            'TblType' => 'Validation',
            'TblGroup' => 'Logs',
            'TblOrd' => '23',
            'TblExtend' => '0',
            'TblNumFields' => '3',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 22,
            'TblDatabase' => '3',
            'TblAbbr' => 'RoleUser',
            'TblName' => 'UsersRoles',
            'TblEng' => 'User Roles',
            'TblDesc' => 'Each record represents one linkage between a Laravel system User and a User Role as defined by it\'s unique ID in the Definitions table. ',
            'TblType' => 'Linking',
            'TblGroup' => 'Users',
            'TblOrd' => '22',
            'TblExtend' => '0',
            'TblNumFields' => '2',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 23,
            'TblDatabase' => '3',
            'TblAbbr' => 'Log',
            'TblName' => 'LogActions',
            'TblEng' => 'Log Actions',
            'TblDesc' => 'Each record represents one detailed Log entry of an Administrator\'s database design changes, important for keeping a history for undoing mistakes.',
            'TblType' => 'Validation',
            'TblGroup' => 'Logs',
            'TblOrd' => '24',
            'TblExtend' => '0',
            'TblNumFields' => '7',
            'TblNumForeignKeys' => '4'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 24,
            'TblDatabase' => '3',
            'TblAbbr' => 'DataLoop',
            'TblName' => 'DataLoop',
            'TblEng' => 'Data Loops',
            'TblDesc' => 'Each record represents one Data Loop\'s full details. This is important for automating data relations for related Experiences.',
            'TblGroup' => 'Experiences',
            'TblOrd' => '12',
            'TblExtend' => '0',
            'TblNumFields' => '12',
            'TblNumForeignKeys' => '2',
            'TblNumForeignIn' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 25,
            'TblDatabase' => '3',
            'TblAbbr' => 'DataLink',
            'TblName' => 'DataLinks',
            'TblEng' => 'Data Linkage Tables',
            'TblDesc' => 'Each record represents one linkage between one Database Table and a second Table which don\'t require a hierarchical relationship. This is important for automating data relations for related Experiences.',
            'TblType' => 'Linking',
            'TblGroup' => 'Experiences',
            'TblOrd' => '15',
            'TblExtend' => '0',
            'TblNumFields' => '2',
            'TblNumForeignKeys' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 26,
            'TblDatabase' => '3',
            'TblAbbr' => 'DataSub',
            'TblName' => 'DataSubsets',
            'TblEng' => 'Data Subset Tables',
            'TblDesc' => 'Each record represents one linkage between one Database Table and a Subset Table which pulls in one related secondary record of detailed information. This is important for automating data relations for related Experiences.',
            'TblType' => 'Linking',
            'TblGroup' => 'Experiences',
            'TblOrd' => '13',
            'TblExtend' => '0',
            'TblNumFields' => '6',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 27,
            'TblDatabase' => '3',
            'TblAbbr' => 'DataHelp',
            'TblName' => 'DataHelpers',
            'TblEng' => 'Data Helper Tables',
            'TblDesc' => 'Each record represents one linkage between one Database Table and a Helper Table which can store multiple checkbox responses or relate many secondary records to the primary. This is important for automating data relations for related Experiences.',
            'TblType' => 'Linking',
            'TblGroup' => 'Experiences',
            'TblOrd' => '14',
            'TblExtend' => '0',
            'TblNumFields' => '5',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 28,
            'TblDatabase' => '3',
            'TblAbbr' => 'User',
            'TblName' => 'Users',
            'TblEng' => 'Users',
            'TblDesc' => 'This represents the Laravel Users table, but will not actually be implemented by SurvLoop as part of the database installation.',
            'TblGroup' => 'Users',
            'TblOrd' => '16',
            'TblExtend' => '0',
            'TblNumForeignIn' => '12'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 189,
            'TblDatabase' => '3',
            'TblAbbr' => 'Email',
            'TblName' => 'Emails',
            'TblEng' => 'Email Templates',
            'TblDesc' => 'Each record represents one Email Template which can be used throughout SurvLoop.',
            'TblGroup' => 'Users',
            'TblOrd' => '31',
            'TblExtend' => '0',
            'TblNumFields' => '7',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 190,
            'TblDatabase' => '3',
            'TblAbbr' => 'Img',
            'TblName' => 'Images',
            'TblEng' => 'Gallery Images',
            'TblDesc' => 'Each record represents one item in the Media Galleries managed by SurvLoop.',
            'TblGroup' => 'Trees',
            'TblOrd' => '16',
            'TblExtend' => '0',
            'TblNumFields' => '13',
            'TblNumForeignKeys' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 194,
            'TblDatabase' => '3',
            'TblAbbr' => 'Emailed',
            'TblName' => 'Emailed',
            'TblEng' => 'Emailings',
            'TblDesc' => 'Each record represents one individual Email Template Mailing, including a copy of the auto-generated (then optionally customized) body of the sent email.',
            'TblGroup' => 'Users',
            'TblOrd' => '32',
            'TblExtend' => '0',
            'TblNumFields' => '9',
            'TblNumForeignKeys' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 166,
            'TblDatabase' => '3',
            'TblAbbr' => 'Zip',
            'TblName' => 'Zips',
            'TblEng' => 'U.S. Zip Codes',
            'TblDesc' => 'Each record represents one United States zip code (postal code). This is an important lookup for certain types of databases.',
            'TblGroup' => 'Data Libraries',
            'TblOrd' => '25',
            'TblExtend' => '0',
            'TblNumFields' => '7'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 193,
            'TblDatabase' => '3',
            'TblAbbr' => 'Cont',
            'TblName' => 'Contact',
            'TblEng' => 'Contact Form',
            'TblDesc' => 'Each record represents one complete submission of the default Contact Form.',
            'TblGroup' => 'Users',
            'TblOrd' => '30',
            'TblExtend' => '0',
            'TblNumFields' => '5'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 195,
            'TblDatabase' => '3',
            'TblAbbr' => 'SchRecDmp',
            'TblName' => 'SearchRecDump',
            'TblEng' => 'Searchable Record Dump',
            'TblDesc' => 'Each record stores a cache of all data from a specific complex record, including English translations for Definition IDs, etc. This is very useful for running text searches across all Experience submissions.',
            'TblType' => 'Validation',
            'TblGroup' => 'Trees',
            'TblOrd' => '18',
            'TblExtend' => '0',
            'TblNumFields' => '3',
            'TblNumForeignKeys' => '1'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 196,
            'TblDatabase' => '3',
            'TblAbbr' => 'SessEmo',
            'TblName' => 'SessEmojis',
            'TblEng' => 'Session Emojis',
            'TblDesc' => 'Each record stores one tag of one submission, by one user. This enables users to interact and provide feedback on completed Experience submissions, and to prevent users from tagging more than once.',
            'TblType' => 'Linking',
            'TblGroup' => 'Session Records',
            'TblOrd' => '24',
            'TblExtend' => '0',
            'TblNumFields' => '4',
            'TblNumForeignKeys' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 198,
            'TblDatabase' => '3',
            'TblAbbr' => 'Tok',
            'TblName' => 'Tokens',
            'TblEng' => 'Tokens',
            'TblDesc' => 'Each record one security token, of various types, which can be sent to a user to confirms some action or grant some access with greater security.',
            'TblGroup' => 'Users',
            'TblOrd' => '27',
            'TblExtend' => '0',
            'TblNumFields' => '5',
            'TblNumForeignKeys' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 191,
            'TblDatabase' => '3',
            'TblAbbr' => 'AdyGeo',
            'TblName' => 'AddyGeo',
            'TblEng' => 'Address Geolocate',
            'TblDesc' => 'Each record represents one Address (all in one line) to map it to its latitude and longitude coordinates. This is important to reducing the number of geocoding requests sent to third parties.',
            'TblGroup' => 'Lookups',
            'TblOrd' => '36',
            'TblExtend' => '0',
            'TblNumFields' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 197,
            'TblDatabase' => '3',
            'TblAbbr' => 'SessPage',
            'TblName' => 'SessPage',
            'TblEng' => 'Session Page Loads',
            'TblDesc' => 'Each record represents one page load, or server-side redirect, as related to their current Session. This is important for saving a User\'s path through the entire website.',
            'TblGroup' => 'Session Records',
            'TblOrd' => '26',
            'TblExtend' => '0',
            'TblNumFields' => '2'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 199,
            'TblDatabase' => '3',
            'TblAbbr' => 'SiteSess',
            'TblName' => 'SessSite',
            'TblEng' => 'Site Session',
            'TblDesc' => 'Each record represents one User\'s Site Session while browsing through the whole website, not just surveys. This is important for identifying any problems in the website use flow.',
            'TblGroup' => 'Session Records',
            'TblOrd' => '25',
            'TblExtend' => '0',
            'TblNumFields' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 200,
            'TblDatabase' => '3',
            'TblAbbr' => 'Up',
            'TblName' => 'Uploads',
            'TblEng' => 'Uploads',
            'TblDesc' => 'Each record stores details about a file uploaded by a user, especially with any survey process.',
            'TblGroup' => 'Trees',
            'TblOrd' => '17',
            'TblExtend' => '0',
            'TblNumFields' => '13',
            'TblNumForeignKeys' => '3'
        ]);
        DB::table('SL_Tables')->insert([
            'TblID' => 192,
            'TblDatabase' => '3',
            'TblAbbr' => 'Cach',
            'TblName' => 'Caches',
            'TblEng' => 'Caches',
            'TblDesc' => 'Each record represents one cache of content, whether HTML, JS, or CSS.',
            'TblGroup' => 'Optimization',
            'TblOrd' => '37',
            'TblExtend' => '0',
            'TblNumFields' => '7',
            'TblNumForeignKeys' => '1'
        ]);
    
    DB::table('SL_Fields')->insert([
            'FldID' => 1,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'User',
            'FldEng' => 'Database Owner User ID',
            'FldDesc' => 'Indicates the unique User ID number belonging to the owner of this Database.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 2,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Prefix',
            'FldEng' => 'Database Prefix',
            'FldDesc' => 'Indicates the short abbreviation which is used to start each Table Name within this Database. This is important for successfully creating and accessing all Database Tables.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '25',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 3,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Name',
            'FldEng' => 'Database Name',
            'FldDesc' => 'Indicates the main, casual, name of the Database in English.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 4,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Desc',
            'FldEng' => 'Database Description',
            'FldDesc' => 'Indicates the short tagline which is used to describe this Database. This is generally related to Search Engine Optimization.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 5,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Mission',
            'FldEng' => 'Database Mission',
            'FldDesc' => 'Indicates the desired goals to be accomplished with this Database. This is important for reminding Users why they are using this system.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 6,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Tables',
            'FldEng' => 'Total Tables',
            'FldDesc' => 'Indicates the cached total number of Tables which make up this Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 7,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Fields',
            'FldEng' => 'Total Fields',
            'FldDesc' => 'Indicates the cached total number of Fields which make up this Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 8,
            'FldDatabase' => '3',
            'FldTable' => '4',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Database Options',
            'FldDesc' => 'Indicates the multiple of various prime numbers which mark specific qualities of this Database.',
            'FldNotes' => '%3 = Simple Field Specifications',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 9,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Table Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this Table belongs to.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 10,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Eng',
            'FldEng' => 'Table Name (in English)',
            'FldDesc' => 'Indicates the main, casual, name of the Database Table in English.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 11,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Name',
            'FldEng' => 'Table Name (in Database)',
            'FldDesc' => 'Indicates the shorter Table Name without spaces or special characters which is used to technically identify this Table when accessing its records in the Database. During system use, the Database\'s Prefix will be added to the start of the Table Name in this record.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 12,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Abbr',
            'FldEng' => 'Table Prefix',
            'FldDesc' => 'Indicates the short abbreviation which is used to start each Field Name within this Table. This is important for successfully creating and accessing all Table Fields.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 13,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Desc',
            'FldEng' => 'Table Description',
            'FldDesc' => 'Indicates the full description of this Database Table. This should generally be a sentence or two describing what information is stored in a Table, why it is important, and perhaps why this information is being organized in the form of this Table. ',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 14,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Notes',
            'FldEng' => 'Table Internal Notes',
            'FldDesc' => 'Indicates the any important Notes related to this Database Table. This can be important for providing documentation or other internal comments about how or why this Table was designed in this way.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 15,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Group',
            'FldEng' => 'Table Group',
            'FldDesc' => 'Indicates which Group of Table this record belongs to. This can be important for visually organizing Tables, and works best if Tables in the same Table Group are all adjacent in the Table Order within the Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 16,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Table Type',
            'FldDesc' => 'Indicates whether this Database Table stores Data, Subset data, data Linkages, or data Validation. This is important for internal organization and documentation of the Database Design.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldValues' => 'Data;Subset;Linking;Validation',
            'FldDefault' => 'Data',
            'FldDataLength' => '25',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 17,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'Ord',
            'FldEng' => 'Table Sorting Order',
            'FldDesc' => 'Indicates the indexed order of this Table, relative to other Tables in the Database. This is only important for presenting the list of Tables to a Database Designer, and additional value can be added by using Table Groups.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 18,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Table Options',
            'FldDesc' => 'Indicates the multiple of various prime numbers which mark specific qualities of this Table.',
            'FldNotes' => '%3 = Table Has User-Defined Primary Key',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 19,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'Extend',
            'FldEng' => 'Is Active',
            'FldDesc' => 'Indicates whether or not this Database Table is currently active in the system. This is important for removing Tables from the system without deleting them, temporarily or permanently.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 20,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'NumFields',
            'FldEng' => 'Total Fields',
            'FldDesc' => 'Indicates the cached total number of Fields which make up this Table.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 21,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'NumForeignKeys',
            'FldEng' => 'Total Foreign Keys Outgoing',
            'FldDesc' => 'Indicates the cached total number of Foreign Fields in this Table which point to other Database Tables.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 22,
            'FldDatabase' => '3',
            'FldTable' => '9',
            'FldOrd' => '13',
            'FldSpecSource' => '0',
            'FldName' => 'NumForeignIn',
            'FldEng' => 'Total Foreign Keys Incoming',
            'FldDesc' => 'Indicates the cached total number of Foreign Fields in other Tables which point to this Database Table.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 23,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Field Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this Field belongs to. This is important to track for generic fields which are not related to a specific Database Table.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 24,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Table',
            'FldEng' => 'Field Table ID',
            'FldDesc' => 'Indicates the unique Table ID number which this Field belongs to.',
            'FldNotes' => 'The parent table where this field is used. Only Generic fields have no table assigned. (Hernandez p.279)',
            'FldForeignTable' => '9',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 25,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Ord',
            'FldEng' => 'Field Sorting Order in Table',
            'FldDesc' => 'Indicates the indexed order of this Field, relative to other Fields within their Database Table. This is only important for presenting the list of Fields to a Database Designer.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 26,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Eng',
            'FldEng' => 'Field Name (in English)',
            'FldDesc' => 'Indicates the main, casual, name of the Database Field in English.',
            'FldNotes' => 'This label is an alternate name by which you can identify the field within an end-user application interface. Something readable. (Hernandez p.279)',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 27,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Name',
            'FldEng' => 'Field Name (in Database)',
            'FldDesc' => 'Indicates the shorter Field Name without spaces or special characters which is used to technically identify this Field when accessing its stored values in the Database. During system use, the Table\'s Prefix will be added to the start of the Field Name in this record.',
            'FldNotes' => 'Absolute minimal words that uniquely identifies this field, following database requirements (like probably no spaces). The table abbreviation name will be tacked on to the beginning. (Hernandez p.277)',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 28,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'Desc',
            'FldEng' => 'Field Description',
            'FldDesc' => 'Indicates the full description of this Database Field. This should generally be a sentence or two describing what information is stored in this Field, and why it is important.',
            'FldNotes' => 'A complete interpretation of the field. Write a clear and succinct statement that accurately identifies the field and clearly states its purpose within the table. Avoid technical jargon, acronyms, abbreviations, and implementation-specific information. (Hernandez p.283)',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 29,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Notes',
            'FldEng' => 'Field Internal Notes',
            'FldDesc' => 'Indicates the any important Notes related to this Database Field. This can be important for providing documentation or other internal comments about how or why this Field was designed in this way, or where a list of potentially stored values came from.',
            'FldNotes' => 'Extra space for internal notes, often reserved for implementation-specific information, or planning in progress.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 30,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '18',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'MySQL Data Type',
            'FldDesc' => 'Indicates which basic MySQL type of data will be stored with this Database Field. This is important for determining how to export the database or otherwise install it to MySQL-based environments.',
            'FldNotes' => 'MySQL implementation-specific field type, needed for one-click database structure export. <i>(NOT Hernandez approved planning.)</i>',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => 'VARCHAR',
            'FldDataLength' => '25',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 31,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'ForeignTable',
            'FldEng' => 'Foreign Key Table ID',
            'FldDesc' => 'If this Field is a Foreign Key pointing to another Database Table, then this is where that unique Table ID is stored. This is important for tracking data linkages through the Database Design.',
            'FldForeignTable' => '9',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldDefault' => '-3',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 32,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '14',
            'FldSpecSource' => '0',
            'FldName' => 'Foreign2Max',
            'FldEng' => 'Degree of Participation: Incoming Max',
            'FldDesc' => 'If this Field record defines a Foreign Key, then this helps indicate its degree of participation, the maximum number of a Foreign Table\'s records which can be related to a single record of this Table. This is important for documenting and enforcing logical limitations designed with the Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '11',
            'FldCharSupport' => ',Numbers,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 33,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'ForeignMin',
            'FldEng' => 'Degree of Participation: Outgoing Min',
            'FldDesc' => 'If this Field record defines a Foreign Key, then this helps indicate its degree of participation, the minimum number of this Table\'s records which can be related to a single record of the Foreign Table. This is important for documenting and enforcing logical limitations designed with the Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '11',
            'FldCharSupport' => ',Numbers,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 34,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'ForeignMax',
            'FldEng' => 'Degree of Participation: Outgoing Max',
            'FldDesc' => 'If this Field record defines a Foreign Key, then this helps indicate its degree of participation, the maximum number of this Table\'s records which can be related to a single record of the Foreign Table. This is important for documenting and enforcing logical limitations designed with the Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '11',
            'FldCharSupport' => ',Numbers,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 35,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '13',
            'FldSpecSource' => '0',
            'FldName' => 'Foreign2Min',
            'FldEng' => 'Degree of Participation: Incoming Min',
            'FldDesc' => 'If this Field record defines a Foreign Key, then this helps indicate its degree of participation, the minimum number of a Foreign Table\'s records which can be related to a single record of this Table. This is important for documenting and enforcing logical limitations designed with the Database.',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDefault' => '1',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '11',
            'FldCharSupport' => ',Numbers,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 36,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '15',
            'FldSpecSource' => '0',
            'FldName' => 'Values',
            'FldEng' => 'Field Values',
            'FldDesc' => 'Storing information here indicates that this Database Field can only be set to a specified range of values, separated by semi-colons. (* This field needs to be replaced by a helper table which stores each value as its own record, or perhaps preferably using the Definitions table. *)',
            'FldNotes' => 'Specifies every possible valid value for a field. You can optionally select a pre-definined value set which is common among various fields. (Hernandez p.294)',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 37,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '17',
            'FldSpecSource' => '0',
            'FldName' => 'IsIndex',
            'FldEng' => 'Is Indexed',
            'FldDesc' => 'Indicates whether or not this Field is to be indexed by the Database for faster searching of this specific field. This is important for ideal Database creation.',
            'FldNotes' => 'When this project is finished, will this field be searched or sorted very very often? A database index is a data structure that improves the speed of data retrieval operations on a database table at the cost of additional writes and storage space to maintain the index data structure. <i>(NOT Hernandez approved planning.)</i>',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldValues' => '0;1',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 38,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '16',
            'FldSpecSource' => '0',
            'FldName' => 'Default',
            'FldEng' => 'Default Value',
            'FldDesc' => 'Indicates the default value for this specific Field, when a new Table record is first created.',
            'FldNotes' => 'A value that a user can into a field when a more appropriate value is not yet available, nulls not allowed. Use a default value ONLY if it is meaningful. (Hernandez p.294)',
            'FldForeignMin' => '0',
            'FldForeignMax' => '0',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldDataLength' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 39,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'SpecType',
            'FldEng' => 'Field Specification Type',
            'FldDesc' => 'Indicates whether this Database Field specification is Unique Field to this Table\'s instance, or is a Replica Field of a Generic Field shared by multiple Tables, or is itself a Generic Field. This can be important for documenting which Fields might need to maintain parallel changes in their specifications.',
            'FldNotes' => 'Unique - any kind of field that will appear only once within the entire database or a primary key; Generic - a field that serves as a template for other fields within the database; Replica - a field that you base on a given generic field or a foreign key; (Hernandez p.279)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Unique;Generic;Replica',
            'FldDefault' => 'Unique',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 40,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'SpecSource',
            'FldEng' => 'Field Spec Generic Source',
            'FldDesc' => 'If this record specification type indicates that it stores a Replica Field, then this information represents the unique Field ID of related the Generic Field.',
            'FldNotes' => 'If this field is a Replica, the Specification Source is set to the Generic field which it mimics. To turn this field into a Replica, select a Generic field from the dropdown and click the Load link. To save a copy of this field as a Generic for future reuse, click the Save As Generic link. (Hernandez p.281)',
            'FldForeignTable' => '10',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldDefault' => '-3',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 41,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Alias',
            'FldEng' => 'Field Alias(es)',
            'FldDesc' => 'Indicates any other names this specific Field might be known as.',
            'FldNotes' => 'A name (or set of names) that you use for the field in very rare circumstances. This must be used if there are two occurrences of this field in the same table. (Hernandez p.281)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 42,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '19',
            'FldSpecSource' => '0',
            'FldName' => 'DataType',
            'FldEng' => 'Basic Data Type',
            'FldDesc' => 'Indicates the most basic nature of the data being stored by this Field, whether .',
            'FldNotes' => 'The most basic nature of the data that this field stores (not implementation-specific). (Hernandez p.286)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Alphanumeric;Numeric;DateTime',
            'FldDefault' => 'Alphanumeric',
            'FldDataLength' => '20',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 43,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '20',
            'FldSpecSource' => '0',
            'FldName' => 'DataLength',
            'FldEng' => 'Data Length',
            'FldDesc' => 'Indicates the total number of characters or digits that a user can enter for any stored value in this Database Field. This is important for ideal Database creation.',
            'FldNotes' => 'The total number of characters or digits that a user can enter for any given value of this field. If otherwise unimportant, this can be set to the maximum length for the best implementation-specific data type. (Hernandez p.289)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 44,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '21',
            'FldSpecSource' => '0',
            'FldName' => 'DataDecimals',
            'FldEng' => 'Data Decimal Places',
            'FldDesc' => 'If this Field\'s basic data type is numeric, then this denotes the number of digits to the right of the decimal point in real numbers which can be stored in this Database Field.',
            'FldNotes' => 'If this field is Numeric, this denotes the number of digits to the right of the decimal point in a real number (eg. MySQL DOUBLE). (Hernandez p.289)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 45,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '22',
            'FldSpecSource' => '0',
            'FldName' => 'CharSupport',
            'FldEng' => 'Character Support',
            'FldDesc' => 'Indicates the type of characters that a user can enter as a value for this Database Field. (* Multiple character types are currently stored as comma separated strings, but this should probably be some cleaner method. *)',
            'FldNotes' => 'The type of characters that a user can enter as a value for this field. Setting and enforcing this helps you ensure that the user cannot introduce meaningless data into the field, enhancing field-level integrity. Letters include foreign language letters. (Hernandez p.289)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Letters;Numbers;Keyboard;Special',
            'FldDefault' => ',Letters,Numbers,Keyboard,Special,',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Keyboard,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 46,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '23',
            'FldSpecSource' => '0',
            'FldName' => 'InputMask',
            'FldEng' => 'Input Mask',
            'FldDesc' => 'Specifies the manner in which a user should enter data into this field. eg. YYYY-MM-DD',
            'FldNotes' => 'Specifies the manner in which a user should enter data into this field. eg. YYYY-MM-DD (Hernandez p.290)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 47,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '24',
            'FldSpecSource' => '0',
            'FldName' => 'DisplayFormat',
            'FldEng' => 'Display Format',
            'FldDesc' => 'Indicates the appearance of this Database Field\'s value when it is displayed on a screen or printed within a document.',
            'FldNotes' => 'The appearance of this field\'s value when it is displayed on a screen or printed within a document. (Hernandez p.291)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 48,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '25',
            'FldSpecSource' => '0',
            'FldName' => 'KeyType',
            'FldEng' => 'Key Type',
            'FldDesc' => 'Indicates whether or not this Field is some kind of Key in its parent Database Table, including Foreign Keys, Primary Keys, or Alternate Keys. By default, all SurvLoop Tables will automatically generate a Unique, Primary Key if no such Field is specified by the Database Designer. (* Multiple key types are currently stored as comma separated strings, but this should probably be some cleaner method. *)',
            'FldNotes' => 'Designates this field\'s role within a table. (Hernandez p.292)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Non;Foreign;Primary;Alternate',
            'FldDefault' => ',Non,',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Keyboard,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 49,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '26',
            'FldSpecSource' => '0',
            'FldName' => 'KeyStruct',
            'FldEng' => 'Key Structure',
            'FldDesc' => 'If this Database Field is a Primary Key, then this indicates whether it is acting as a simple (single-field) primary key or as part of a composite (multi-field) primary key.',
            'FldNotes' => 'Denotes whether this field designated as a primary key is acting as a simple (single-field) primary key or as part of a composite (multi-field) primary key. (Hernandez p.292)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Simple;Composite',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 50,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '27',
            'FldSpecSource' => '0',
            'FldName' => 'EditRule',
            'FldEng' => 'Edit Rule',
            'FldDesc' => 'Indicates whether or not a Field\'s data can or must be store at the Table record\'s creation, and whether or not it can it be edited by Users later.',
            'FldNotes' => 'Enter Now - user must enter a value when creating a new table record; Enter Later - user has the option of entering value when creating table record; Edits Allowed - user can edit the value at any time; Edits Not Allowed - after entering a value, user cannot edit it at any time; (Hernandez p.295)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'NowAllowed;LateAllow;NowNot;LateNot;NotDeterm',
            'FldDefault' => 'LateAllow',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 51,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '28',
            'FldSpecSource' => '0',
            'FldName' => 'Unique',
            'FldEng' => 'Has Unique Value',
            'FldDesc' => 'Indicates whether this Database Field\'s values are unique across all Table records.',
            'FldNotes' => 'Indicates whether this field\'s values are unique (always for primary keys, rarely for everything else). (Hernandez p.292)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 52,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '29',
            'FldSpecSource' => '0',
            'FldName' => 'NullSupport',
            'FldEng' => 'Has NULL Support',
            'FldDesc' => 'Indicates whether or not this Database Field accepts NULL values to be stored, which should represent missing or unknown information.',
            'FldNotes' => 'Whether this field accepts null values (not allowed for primary keys). Null values should represent missing or unknown values. (Hernandez p.293)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 53,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '30',
            'FldSpecSource' => '0',
            'FldName' => 'ValuesEnteredBy',
            'FldEng' => 'Values Entered By',
            'FldDesc' => 'Indicates whether a User or the System enter information into this Database Field.',
            'FldNotes' => 'The source of this field\'s value. Either a user will enter values into the field manually or a database application program will enter them automatically. (Hernandez p.293)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'User;System',
            'FldDefault' => 'User',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 54,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '31',
            'FldSpecSource' => '0',
            'FldName' => 'Required',
            'FldEng' => 'Is Required',
            'FldDesc' => 'Indicates whether or not a User is required to enter a value for this Database Field.',
            'FldNotes' => 'Whether a user is required to enter a value for this field. No for most non-primary-key fields. (Hernandez p.294)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 55,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '33',
            'FldSpecSource' => '0',
            'FldName' => 'CompareOther',
            'FldEng' => 'Compare Other Field Value',
            'FldDesc' => 'Indicates the types of comparisons can be applied to a given field value when retrieving information from the other Fields in other Table records. Currently stored as the multiple of various prime numbers which represent the specific comparisons allowed.',
            'FldNotes' => 'The types of comparisons a user/system can apply to a given field value when retrieving information from the field. (Hernandez p.296)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 56,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '32',
            'FldSpecSource' => '0',
            'FldName' => 'CompareSame',
            'FldEng' => 'Compare Same Field Value',
            'FldDesc' => 'Indicates the types of comparisons can be applied to a given field value when retrieving information from the same Field in other Table records. Currently stored as the multiple of various prime numbers which represent the specific comparisons allowed.',
            'FldNotes' => 'The types of comparisons a user/system can apply to a given field value when retrieving information from the field. (Hernandez p.296)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 57,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '34',
            'FldSpecSource' => '0',
            'FldName' => 'CompareValue',
            'FldEng' => 'Compare Other Values',
            'FldDesc' => 'Indicates the types of comparisons can be applied to a given field value when retrieving information from the other values generally. Currently stored as the multiple of various prime numbers which represent the specific comparisons allowed.',
            'FldNotes' => 'The types of comparisons a user/system can apply to a given field value when retrieving information from the field. (Hernandez p.296)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 58,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '35',
            'FldSpecSource' => '0',
            'FldName' => 'OperateSame',
            'FldEng' => 'Operate with Same Field Value',
            'FldDesc' => 'Indicates the types of operations which can be applied to a given Field value with the same Field in other Table records. Currently stored as the multiple of various prime numbers which represent the specific comparisons allowed.',
            'FldNotes' => 'Specifies the types of operations that a user can perform on the values. (Hernandez p.298)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 59,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '36',
            'FldSpecSource' => '0',
            'FldName' => 'OperateOther',
            'FldEng' => 'Operate with Other Field Value',
            'FldDesc' => 'Indicates the types of operations which can be applied to a given Field value with the other Fields in other Table records. Currently stored as the multiple of various prime numbers which represent the specific comparisons allowed.',
            'FldNotes' => 'Specifies the types of operations that a user can perform on the values. (Hernandez p.298)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 60,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '37',
            'FldSpecSource' => '0',
            'FldName' => 'OperateValue',
            'FldEng' => 'Operate with Other Value',
            'FldDesc' => 'Indicates the types of operations which can be applied to a given Field value with the other values generally. Currently stored as the multiple of various prime numbers which represent the specific comparisons allowed.',
            'FldNotes' => 'Specifies the types of operations that a user can perform on the values. (Hernandez p.298)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 61,
            'FldDatabase' => '3',
            'FldTable' => '10',
            'FldOrd' => '38',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Field Options',
            'FldDesc' => 'Indicates the multiple of various prime numbers which mark other specific qualities of this Field.',
            'FldNotes' => '%3 = Field Is Auto-Managed by SurvLoop; 
    %1 = XML Public Data; %7 = XML Private Data; %11 = XML Sensitive Data; %13 = XML Internal Use Data; ',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 62,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Set',
            'FldEng' => 'Set',
            'FldDesc' => 'This is a label for a set of system information which may need to be periodically changed by a system Administrator without the technical assistance of a programmer. Important for categorizing these dynamically stored system definitions.',
            'FldNotes' => 'Potential for multiple languages in the future?',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Def::SurvLoop Definition Types',
            'FldDefault' => 'Value Ranges',
            'FldDataLength' => '20',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 63,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Subset',
            'FldEng' => 'Subset',
            'FldDesc' => 'This is a label for a subset of system information which may need to be periodically changed by a system Administrator without the technical assistance of a programmer. Important for categorizing these dynamically stored system Definitions.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 64,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'IsActive',
            'FldEng' => 'Is Active',
            'FldDesc' => 'Indicates whether or not this Database Definition is currently active in the system. This is important for disabling Definitions from the system without deleting them, either temporarily or permanently.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 65,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Order',
            'FldEng' => 'Sorting Order in Subset',
            'FldDesc' => 'Indicates the indexed order of this Definition, relative to other Definitions within their Set and Subset of categorization. This is particularly important for providing order to the range of potential response values presented to Users.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 66,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Value',
            'FldEng' => 'Value',
            'FldDesc' => 'The dynamically stored text or value which Administrators can manage with a programmer. In the case of Value Ranges, this value is what a User will see, but the unique Definition ID will be stored as the User\'s response.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 67,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Description',
            'FldEng' => 'Description',
            'FldDesc' => 'The optional descriptive notes providing a deeper explanation of this specific Definition. For Value Ranges, this could also provide references and sources regarding the decision to include this Definition. Important for internal documentation and possibly User education.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 68,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Rule Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this Business Rule belongs to.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 69,
            'FldDatabase' => '3',
            'FldTable' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Definition Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this Definition belongs to.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 70,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Statement',
            'FldEng' => 'Rule Statement',
            'FldDesc' => 'Indicates the text of the business rule itself, clear, succinct, and convey the required constraints in simple English.',
            'FldNotes' => 'The text of the business rule itself, clear, succinct, and convey the required constraints. (Hernandez p.409)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 71,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Constraint',
            'FldEng' => 'Rule Constraint',
            'FldDesc' => 'Indicates how the constraint specifically applies to the Database Tables and Fields, intended for a more technical audience.',
            'FldNotes' => 'A brief explanation of how the constraint applies to the tables and fields. (Hernandez p.409)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 72,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'IsAppOrient',
            'FldEng' => 'Is Implemented In Application',
            'FldDesc' => 'Indicates whether or not this Business Rule can be directly implemented within the logical design of the database (Database-Oriented). Application-Oriented Rules will have to be implemented with custom coding, outside the scope of SurvLoop\'s automation.',
            'FldNotes' => 'Database-Oriented: impose constraints that you can establish within the logical design of the database; Application-Oriented: impose constraints that you cannot establish within the logical design of the database. (Hernandez p.397)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 73,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'IsRelation',
            'FldEng' => 'Is Relationship-Specific',
            'FldDesc' => 'Indicates whether or not this Business Rule provides additional specifications to a Database Field in isolation, or to a Relationship multiple Fields or Tables.',
            'FldNotes' => 'Field-Specific and Relationship-Specific rules can be established in field specifications, but more details may be provided here if needed. (Hernandez p.399)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 74,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'TestOn',
            'FldEng' => 'Test Rule On',
            'FldDesc' => 'Indicates when the imposed Rule should be tested, whether upon inserting a new Table record, deleting a record, or updating a Field\'s value.',
            'FldNotes' => 'The constraint imposed should be tested when you attempt to perform: Inserting a record into the table or an entry into a field, Deleting a record from the table or a value within a field, or Updating a field\'s value. (Hernandez p.408)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Insert;Update;Delete',
            'FldDefault' => 'Insert',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 75,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Tables',
            'FldEng' => 'Rule Tables',
            'FldDesc' => 'Indicates a comma-separated list of unique Table IDs which this Rule will affect. (* This should be moved to a different data structure. *)',
            'FldNotes' => 'Designate the name of the field(s) and table(s) the rule will affect. (Hernandez p.410)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => ',',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 76,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Fields',
            'FldEng' => 'Rule Fields',
            'FldDesc' => 'Indicates a comma-separated list of unique Table IDs which this Rule will affect. (* This should be moved to a different data structure. *)',
            'FldNotes' => 'Designate the name of the field(s) and table(s) the rule will affect. (Hernandez p.410)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => ',',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 77,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'Phys',
            'FldEng' => 'Physical Elements Affected',
            'FldDesc' => 'Indicates the multiple of various prime numbers which represent physical data specifications related to this Rule, including Data Type, Length, Decimal Places, Character Support, Input Mask, and Display Format.
    ',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 78,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Logic',
            'FldEng' => 'Logical Elements Affected',
            'FldDesc' => 'Indicates the multiple of various prime numbers which represent logical data specifications related to this Rule, including Key Type, Key Structure, Uniqueness, Null Support, Values Entered By, Required Value, Default Value, Range of Values, Comparisons Allowed, Operations Allowed, and Edit Rule.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 79,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'Rel',
            'FldEng' => 'Relationship Characteristics Affected',
            'FldDesc' => 'Indicates the multiple of various prime numbers which represent data relation specifications related to this Rule, including Deletion Rule, Type of Participation, Degree of Participation.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 80,
            'FldDatabase' => '3',
            'FldTable' => '6',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'Action',
            'FldEng' => 'Action Taken',
            'FldDesc' => 'Indicates what actions have been taken, or need to be taken, to implement and enforce this Business Rule.',
            'FldNotes' => 'Indicate the modifications made to field specifications, etc, as clearly as possible. (Hernandez p.)',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 81,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Primary Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this User Experience primarily belongs to.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 82,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'User',
            'FldEng' => 'Tree Owner User ID',
            'FldDesc' => 'Indicates the unique User ID number belonging to the owner of this User Experience or Tree.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1618,
            'FldDatabase' => '3',
            'FldTable' => '12',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Title',
            'FldEng' => 'Article Title',
            'FldDesc' => 'Indicates an appropriate title for an Article which provides more information for Users with completed submissions meeting this related Condition/Filter.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 83,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Tree Type',
            'FldDesc' => 'Indicates whether this Tree (collection of Nodes) indeed maps out a User Experience, or if it provides a map of the Database Design for the creating XML documents automatically-generated by SurvLoop.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Primary Public;Primary Public XML',
            'FldDefault' => 'Primary Public',
            'FldDataLength' => '30',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 84,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Name',
            'FldEng' => 'Experience Name',
            'FldDesc' => 'Indicates the most succinct and clear name internally used to refer to this specific User Experience.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 85,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Desc',
            'FldEng' => 'Experience Description',
            'FldDesc' => 'Documents a longer description of the purpose, use, and importance of this specific User Experience.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 87,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Root',
            'FldEng' => 'Root Node ID',
            'FldDesc' => 'Indicates the unique Experience Node ID of the first, Root Node of this entire User Experience. All other Nodes within this Tree are descendants of the Root Node.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 88,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'FirstPage',
            'FldEng' => 'First Page Node ID',
            'FldDesc' => 'Stores a cache of the unique Experience Node ID of this Tree\'s first Node which is a Page. This helps to quickly identify the URL of the first Page the user will experience.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 89,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'LastPage',
            'FldEng' => 'Last Page Node ID',
            'FldDesc' => 'Stores a cache of the unique Experience Node ID of this Tree\'s last Node which is a Page. This helps to quickly identify the URL of the final Page the user will experience.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 90,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'CoreTable',
            'FldEng' => 'Experience Core Data Table',
            'FldDesc' => 'Indicates the unique Table ID which acts as the backbone for all data collected throughout this entire User Experience.',
            'FldForeignTable' => '9',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 91,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Node Experience ID',
            'FldDesc' => 'Indicates the unique Experience ID number which this Tree Node belongs to.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 92,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'ParentID',
            'FldEng' => 'Parent Node ID',
            'FldDesc' => 'Indicates the unique Experience Node ID of this Node\'s parent Node. This linkage is the thread which connect all Nodes as branches of the entire Experience Tree.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldDefault' => '-3',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 93,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'ParentOrder',
            'FldEng' => 'Sibling Node Sorting Order',
            'FldDesc' => 'Indicates the indexed order of this Experience Node, relative to sibling Nodes which share the same Parent Node. This is how the branches of the entire Experience Tree stay sorted.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 94,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Node Type',
            'FldDesc' => 'Indicates whether this Experience Node represents either a branch (for navigation), a specific question to the User, a collection of questions as one page, a loop of many pages, or a specific manipulation of previously stored data. Question Nodes include Instructions, Radio, Checkbox, Drop Down, Text, Long Text, Text:Number, Email, Password, Date, Date Picker, Date Time, Time, Gender, Gender Not Sure, Feet Inches, U.S. States, Hidden Field, Spambot Honey Pot, Uploads, and Other/Custom. Nodes which are part of Trees which map XML output are of a separate XML Type.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Page;Branch Title;Loop Root;Data Manip: New;Data Manip: Update;Data Manip: Wrap;Instructions;Radio;Checkbox;Drop Down;Text;Long Text;Text:Number;Email;Password;Date;Date Picker;Date Time;Time;Gender;Gender Not Sure;Feet Inches;U.S. States;Hidden Field;Spambot Honey Pot;Uploads;Other/Custom;XML',
            'FldDataLength' => '25',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 95,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'PromptText',
            'FldEng' => 'Node Question/Prompt To User',
            'FldDesc' => 'Indicates the language used to prompt a User Response. For special Node Types like branches, pages, and loop roots, this field stores a title or name. For XML Nodes, this fields stores the name of the Database Table included in the mapped.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 96,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'PromptNotes',
            'FldEng' => 'Node Side-Notes To User',
            'FldDesc' => 'Indicates the language used to prompt a User Response. For special Node Types like branches, pages, and loop roots, this field stores the Node\'s URL slug. For XML Nodes, this fields stores the unique Table ID of the Database Table included in the mapped.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 97,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'PromptAfter',
            'FldEng' => 'Extra HTML After Node Response',
            'FldDesc' => 'Stores any extra HTML, CSS, or Javascript which a Database Designer wants to appear directly after the User\'s opportunity to Respond to a Node\'s Prompt.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 98,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'InternalNotes',
            'FldEng' => 'Internal Notes',
            'FldDesc' => 'Stores any internal notes which Experience Designers want to share.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 99,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'ResponseSet',
            'FldEng' => 'User Response Set',
            'FldDesc' => 'Indicates the range of options the User will be provided for Node which ask some kind of multiple-choice question. This includes "Definition::{DefSubset}", "LoopItems::{LoopName}", or "[Set:{TableName};;HideIf:1]", but most Question Nodes have Response Options stored in the Node Responses table.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 100,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Default',
            'FldEng' => 'Default Response Value',
            'FldDesc' => 'Indicates if any of the Response options should be pre-selected when this Node loads for the User.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 101,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'DataBranch',
            'FldEng' => 'Node Data Branch',
            'FldDesc' => 'Indicates the name of the Database Table as a point of reference for this Node\'s data interactions, and all of its descendants.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Table ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 102,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'DataStore',
            'FldEng' => 'Node Data Field',
            'FldDesc' => 'Indicates the Database Table and Field where the User\'s Response will actually be stored.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldInputMask' => '{TableName}:{TableAbbr}{FieldName}',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 103,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'TextSuggest',
            'FldEng' => 'Response Text Suggest',
            'FldDesc' => 'Indicates an optional setting to suggest a response or instruction within this Node\'s open-ended form field.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 104,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '13',
            'FldSpecSource' => '0',
            'FldName' => 'CharLimit',
            'FldEng' => 'Response Character Limit',
            'FldDesc' => 'Indicates the maximum number of characters a User may enter in Response to this Node\'s prompt.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 105,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '16',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Node Options',
            'FldDesc' => 'Indicates the multiple of various prime numbers which mark specific other qualities of this Node.',
            'FldNotes' => 'For XML Nodes... %5 = Include members with parent, without table wrap; %7 = Min 1 Record; %11 = Max 1 Record;',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 106,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '15',
            'FldSpecSource' => '0',
            'FldName' => 'Dislikes',
            'FldEng' => 'Node Dislikes',
            'FldDesc' => 'For some systems, this indicates how many Users responded to this Node with "a dislike".',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 107,
            'FldDatabase' => '3',
            'FldTable' => '15',
            'FldOrd' => '14',
            'FldSpecSource' => '0',
            'FldName' => 'Likes',
            'FldEng' => 'Node Likes',
            'FldDesc' => 'For some systems, this indicates how many Users responded to this Node with "a like".',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 108,
            'FldDatabase' => '3',
            'FldTable' => '16',
            'FldSpecSource' => '0',
            'FldName' => 'Node',
            'FldEng' => 'Response Node ID',
            'FldDesc' => 'Indicates the unique Experience Node ID number which this Node Response belongs to.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 109,
            'FldDatabase' => '3',
            'FldTable' => '16',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Ord',
            'FldEng' => 'Response Sorting Order',
            'FldDesc' => 'Indicates the indexed order of this Node Response, relative to other Response options for this Experience Node.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 110,
            'FldDatabase' => '3',
            'FldTable' => '16',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Eng',
            'FldEng' => 'Response Description',
            'FldDesc' => 'Describes this Node Response option clearly in English for the User.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 111,
            'FldDatabase' => '3',
            'FldTable' => '16',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Value',
            'FldEng' => 'Response Stored Value',
            'FldDesc' => 'Indicates how a User selection of this Node Response option should be stored in the Database.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 112,
            'FldDatabase' => '3',
            'FldTable' => '16',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'ShowKids',
            'FldEng' => 'Show Child Nodes If Selected?',
            'FldDesc' => 'Indicates whether or not child Nodes should be revealed to the User upon selecting this specific Node Response option.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 113,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Condition Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this Condition can be applied to.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 114,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Tag',
            'FldEng' => 'Condition Tag/Name',
            'FldDesc' => 'Indicates the short name, like a hashtag on social media, which is used both internally and publicly to easily identify this Condition/Filter.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldInputMask' => '#AAAAAAAAA',
            'FldDisplayFormat' => '#AAAAAAAAA',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 115,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Desc',
            'FldEng' => 'Condition Description',
            'FldDesc' => 'Describes this Condition/Filter in English, as accurately and succinctly as possible, for both internal and public use.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 116,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Operator',
            'FldEng' => 'Condition Operator',
            'FldDesc' => 'Indicates what type of testing to do as part of applying this Data Condition. This includes checking for User Responses within a specified range of values ( "{" ), outside a specified range of values ( "}" ), checking the number of records a Database Table has ( "EXISTS>" ), or "CUSTOM".',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '{',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 117,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'OperDeet',
            'FldEng' => 'Condition Operator Details',
            'FldDesc' => 'Indicates qualifying details to further specify this operator\'s requirements for this Condition. For "EXISTS>" Conditions, this stores the maximum number of Table records which can exist while passing this Condition as FALSE.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 118,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Field',
            'FldEng' => 'Database Field ID',
            'FldDesc' => 'Indicates the unique Field ID number for the data being tested in this Database Condition.',
            'FldForeignTable' => '10',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 119,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Table',
            'FldEng' => 'Database Table ID',
            'FldDesc' => 'Indicates the unique Table ID number for the data being tested in this Database Condition.',
            'FldForeignTable' => '9',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 120,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Loop',
            'FldEng' => 'Data Loop ID',
            'FldDesc' => 'Indicates the unique Data Loop ID number for the data being tested in this Database Condition.',
            'FldForeignTable' => '24',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 121,
            'FldDatabase' => '3',
            'FldTable' => '11',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Condition Options',
            'FldDesc' => 'Indicates the multiple of various prime numbers which mark any other specific qualities of this Condition.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 122,
            'FldDatabase' => '3',
            'FldTable' => '14',
            'FldSpecSource' => '0',
            'FldName' => 'CondID',
            'FldEng' => 'Value Condition ID',
            'FldDesc' => 'Indicates the unique Condition ID number which this Value option relates to.',
            'FldForeignTable' => '11',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 123,
            'FldDatabase' => '3',
            'FldTable' => '14',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Value',
            'FldEng' => 'Value Description',
            'FldDesc' => 'Indicates the actual User Response Value, which would be stored in the Database, associated with the  Condition related to this record.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 124,
            'FldDatabase' => '3',
            'FldTable' => '13',
            'FldSpecSource' => '0',
            'FldName' => 'CondID',
            'FldEng' => 'Condition ID',
            'FldDesc' => 'Indicates the unique Condition ID number of the Condition being applied to an Experience Node or a Data Loop.',
            'FldForeignTable' => '11',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 125,
            'FldDatabase' => '3',
            'FldTable' => '13',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'NodeID',
            'FldEng' => 'Experience Node ID',
            'FldDesc' => 'Indicates the unique Experience Node ID number which this Condition has been applied to.',
            'FldForeignTable' => '13',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 126,
            'FldDatabase' => '3',
            'FldTable' => '13',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'LoopID',
            'FldEng' => 'Data Loop ID',
            'FldDesc' => 'Indicates the unique Data Loop ID number which this Condition has been applied to.',
            'FldForeignTable' => '24',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 127,
            'FldDatabase' => '3',
            'FldTable' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'CondID',
            'FldEng' => 'Article Condition ID',
            'FldDesc' => 'Indicates the unique Condition ID number of the Condition being associated with complementary information.',
            'FldForeignTable' => '12',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 128,
            'FldDatabase' => '3',
            'FldTable' => '12',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'URL',
            'FldEng' => 'Article URL',
            'FldDesc' => 'Indicates the URL of an Article which provides more information for Users with completed submissions meeting this related Condition/Filter.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 129,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Data Loop Experience ID',
            'FldDesc' => 'Indicates the unique Experience ID number of the User Experience for which this Data Loop is required.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 130,
            'FldDatabase' => '3',
            'FldTable' => '26',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Data Link Experience ID',
            'FldDesc' => 'Indicates the unique Experience ID number of the User Experience for which this Data Subset is required.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 131,
            'FldDatabase' => '3',
            'FldTable' => '27',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Data Link Experience ID',
            'FldDesc' => 'Indicates the unique Experience ID number of the User Experience for which this Data Helper is required.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 132,
            'FldDatabase' => '3',
            'FldTable' => '25',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Data Link Experience ID',
            'FldDesc' => 'Indicates the unique Experience ID number of the User Experience for which this Data Link is required.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 133,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Root',
            'FldEng' => 'Root Node ID',
            'FldDesc' => 'Indicates the unique Node ID number of this Data Loop\'s Root Node. All of the descendants of this Data Loop\'s Root will be repeated until the User wants to continue past it, or Loop\'s constraints are exceeded.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 134,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Plural',
            'FldEng' => 'Plural Name For Items In Loop',
            'FldDesc' => 'Indicates the way to describe multiple Items in this Data Loop (eg. "Witnesses"). This is also the primary name for this Data Loop, so its value should be unique within each Database.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 135,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Singular',
            'FldEng' => 'Singular Name For Items In Loop',
            'FldDesc' => 'Indicates the way to describe multiple Items in this Data Loop (eg. "Witness").',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 136,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Table',
            'FldEng' => 'Data Loop Core Table',
            'FldDesc' => 'Indicates the name of the Database Table whose records will either be looped through, or created during this Data Loop.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Table ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 137,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'SortFld',
            'FldEng' => 'Data Sort Field',
            'FldDesc' => 'Indicates the full Database Field name ( "{TableAbbr}{FldName}" ) to be used for sorting Table records as Data Loop Items.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 138,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'DoneFld',
            'FldEng' => 'Loop Completed Field',
            'FldDesc' => 'Indicates the full Database Table and Field address ( "{TableName}:{TableAbbr}{FldName}" ) to be checked signifying that the User has successfully completed the Data Loop for a given Item.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 139,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'MaxLimit',
            'FldEng' => 'Maximum Loop Items',
            'FldDesc' => 'Indicates how many Data Loop Items/Records the User is allowed add.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 140,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'WarnLimit',
            'FldEng' => 'Warn Item Maximum',
            'FldDesc' => 'Indicates how many Data Loop Items/Records will trigger a warning to the User that they are approaching the maximum limit.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 141,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'MinLimit',
            'FldEng' => 'Minimum Loop Items',
            'FldDesc' => 'Indicates how many Data Loop Items/Records the User must add.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 142,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'IsStep',
            'FldEng' => 'Is Step-Loop',
            'FldDesc' => 'Indicates whether or not this Data Loop operates as a Step-Loop, meaning Table records are added to the Database before this Data Loops steps the User through adding information for one preexisting Loop Item at a time. Otherwise, Data Loops behave so that Users to can add Loop Items until the specified limits are reached.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 143,
            'FldDatabase' => '3',
            'FldTable' => '24',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'AutoGen',
            'FldEng' => 'Data Auto-Generates',
            'FldDesc' => 'If this is not a Step-Loop, this indicates whether or not this Data Loop should automatically create new Database Table records (and child data structures) when a User clicks to add another Loop Item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 144,
            'FldDatabase' => '3',
            'FldTable' => '26',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Tbl',
            'FldEng' => 'Primary Table',
            'FldDesc' => 'Indicates the name of the primary Database Table in this Data Subset relationship, included in this User Experience. This Table is upstream of the secondary Table when loading User Data.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Table ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 145,
            'FldDatabase' => '3',
            'FldTable' => '26',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'TblLnk',
            'FldEng' => 'Primary Table Linking Field',
            'FldDesc' => 'When appropriate, this indicates the full name ( "{TableAbbr}{FldName}" ) of the primary Table\'s Field to find related secondary Table records in the User\'s Data. This Field must be a Foreign Key pointing to the secondary Table.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 146,
            'FldDatabase' => '3',
            'FldTable' => '26',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'SubTbl',
            'FldEng' => 'Secondary Table',
            'FldDesc' => 'Indicates the name of the secondary Database Table in this Data Subset relationship, included in this User Experience. This Table is downstream of the primary Table when loading User Data.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Table ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 147,
            'FldDatabase' => '3',
            'FldTable' => '26',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'SubLnk',
            'FldEng' => 'Secondary Table Linking Field',
            'FldDesc' => 'When appropriate, this indicates the full name ( "{TableAbbr}{FldName}" ) of the secondary Table\'s Field to find related secondary Table records in the User\'s Data. This Field must be a Foreign Key pointing to the primary Table.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 148,
            'FldDatabase' => '3',
            'FldTable' => '26',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'AutoGen',
            'FldEng' => 'Secondary Records Auto-Generated',
            'FldDesc' => 'Indicates whether or not this Data Subset should enforce the auto-creation of secondary Table records, whenever the related primary Table record exists.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 149,
            'FldDatabase' => '3',
            'FldTable' => '27',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'ParentTable',
            'FldEng' => 'Primary Table',
            'FldDesc' => 'Indicates the name of the primary Database Table in this Data Helper relationship, included in this User Experience. This Table is upstream of the secondary Table when loading User Data.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Table ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 150,
            'FldDatabase' => '3',
            'FldTable' => '27',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Table',
            'FldEng' => 'Secondary Table',
            'FldDesc' => 'Indicates the name of the primary Database Table in this Data Helper relationship, included in this User Experience. This Table is downstream of the primary Table when loading User Data.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Table ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 151,
            'FldDatabase' => '3',
            'FldTable' => '27',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'KeyField',
            'FldEng' => 'Secondary Foreign Key',
            'FldDesc' => 'Indicates the full name ( "{TableAbbr}{FldName}" ) of the secondary Table\'s Field to find related secondary Table records in the User\'s Data. This Field must be a Foreign Key pointing to the secondary Table.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 152,
            'FldDatabase' => '3',
            'FldTable' => '27',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'ValueField',
            'FldEng' => 'Secondary Storage Field',
            'FldDesc' => 'When appropriate, this indicates the full name ( "{TableAbbr}{FldName}" ) of the secondary Table\'s Field which stores multiple checkbox Response Values from the User and associates them with the primary Database Table.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 153,
            'FldDatabase' => '3',
            'FldTable' => '25',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Table',
            'FldEng' => 'Database Linkage Table',
            'FldDesc' => 'Indicates the unique Table ID number for the many-to-many linkage table to be included when a User\'s Data is loaded for this User Experience.',
            'FldForeignTable' => '9',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 154,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'CoreID',
            'FldEng' => 'User\'s Core Table Record ID',
            'FldDesc' => 'Indicates the unique Table ID number for the User\'s record in this Experience\'s Core Data Table. This is important for saving update\'s in the User\'s progress.',
            'FldForeignTable' => '3',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 155,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldSpecSource' => '0',
            'FldName' => 'UserID',
            'FldEng' => 'Session User ID',
            'FldDesc' => 'Indicates the unique User ID for the Database User logged in and using this Session record.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 156,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Session Experience ID',
            'FldDesc' => 'Indicates the unique Experience ID number of the User Experience which this Session record is tracking.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 157,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'CurrNode',
            'FldEng' => 'Session Current Node ID',
            'FldDesc' => 'Indicates the unique Node ID number of the last Experience Node this User loaded.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 158,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'LoopRootJustLeft',
            'FldEng' => 'Just Left Loop Root ID',
            'FldDesc' => 'When appropriate, this Session data stores the unique Root Node ID for a Data Loop which has just been exited by the User.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 159,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'AfterJumpTo',
            'FldEng' => 'Jump From Node ID',
            'FldDesc' => 'When appropriate, this Session data stores the unique Node ID for an Experience Node the User has just used custom navigation to jump away from.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 161,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'IsMobile',
            'FldEng' => 'Using Mobile Device',
            'FldDesc' => 'Indicates whether or not the User is currently using a mobile device (1 if yes).',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 162,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'Browser',
            'FldEng' => 'Session Browser',
            'FldDesc' => 'Indicates the web browser used during this Session.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldCompareSame' => '6',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 163,
            'FldDatabase' => '3',
            'FldTable' => '20',
            'FldSpecSource' => '0',
            'FldName' => 'SessID',
            'FldEng' => 'User Session ID',
            'FldDesc' => 'Indicates the unique Session ID for the User Session whose Data Loop progress is stored in this record.',
            'FldForeignTable' => '19',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 164,
            'FldDatabase' => '3',
            'FldTable' => '20',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Name',
            'FldEng' => 'Data Loop Name',
            'FldDesc' => 'Indicates the name of the Data Loop with User progress to store in this record.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Data Loop ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '50',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ','
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 165,
            'FldDatabase' => '3',
            'FldTable' => '20',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'ItemID',
            'FldEng' => 'Data Loop Item ID',
            'FldDesc' => 'Indicates the unique ID number of the Data Loop Item which the User was last working on.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 166,
            'FldDatabase' => '3',
            'FldTable' => '17',
            'FldSpecSource' => '0',
            'FldName' => 'Session',
            'FldEng' => 'User Session ID',
            'FldDesc' => 'Indicates the unique Session ID for the User Session whose Node response is stored in this record.',
            'FldForeignTable' => '19',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 167,
            'FldDatabase' => '3',
            'FldTable' => '17',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Node',
            'FldEng' => 'Save Node ID',
            'FldDesc' => 'Indicates the unique Node ID for the Experience Node whose User response is stored in this record.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 168,
            'FldDatabase' => '3',
            'FldTable' => '17',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'VersionAB',
            'FldEng' => 'A/B Testing Version',
            'FldDesc' => 'Stores a complex string reflecting all A/B Testing variations in effect at the time of this User\'s Experience of this Node.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Keyboard,',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 169,
            'FldDatabase' => '3',
            'FldTable' => '17',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'TblFld',
            'FldEng' => 'Storage Data Field',
            'FldDesc' => 'Indicates the full Database Table and Field address ( "{TableName}:{TableAbbr}{FldName}" ) where this Node has decided to store the User\'s Response.',
            'FldNotes' => 'Yes, this should be changed to a Foreign Key to the Field ID.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 170,
            'FldDatabase' => '3',
            'FldTable' => '17',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'LoopItemID',
            'FldEng' => 'Data Loop Item ID',
            'FldDesc' => 'Indicates the unique ID number of the Data Loop Item which the User was working on for this Node Response.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 171,
            'FldDatabase' => '3',
            'FldTable' => '17',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'NewVal',
            'FldEng' => 'User Response',
            'FldDesc' => 'Indicates the stored User\'s Response Value, or a dump of related information.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 172,
            'FldDatabase' => '3',
            'FldTable' => '18',
            'FldSpecSource' => '0',
            'FldName' => 'Session',
            'FldEng' => 'User Session ID',
            'FldDesc' => 'Indicates the unique Session ID for the User Session whose Page Node progress is stored in this record.',
            'FldForeignTable' => '19',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 173,
            'FldDatabase' => '3',
            'FldTable' => '18',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Node',
            'FldEng' => 'Page Node ID',
            'FldDesc' => 'Indicates the unique Node ID for the Experience Page Node this Session has loaded.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 174,
            'FldDatabase' => '3',
            'FldTable' => '18',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'LoopItemID',
            'FldEng' => 'Data Loop Item ID',
            'FldDesc' => 'Indicates the unique ID number of the Data Loop Item which the User was working on for this Page Node.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 175,
            'FldDatabase' => '3',
            'FldTable' => '22',
            'FldSpecSource' => '0',
            'FldName' => 'UID',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID related to the User being granted permissions in this record.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 176,
            'FldDatabase' => '3',
            'FldTable' => '22',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'RID',
            'FldEng' => 'Role ID',
            'FldDesc' => 'Indicates the unique Definition ID related to a User Role being granted in this record.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 177,
            'FldDatabase' => '3',
            'FldTable' => '21',
            'FldSpecSource' => '0',
            'FldName' => 'User',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID related to the User whose actions are being logged in this record.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 178,
            'FldDatabase' => '3',
            'FldTable' => '21',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'CurrPage',
            'FldEng' => 'Current URL',
            'FldDesc' => 'Indicates the URL of the system page where the User is taking the logged action.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 179,
            'FldDatabase' => '3',
            'FldTable' => '21',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Val',
            'FldEng' => 'Action Value',
            'FldDesc' => 'Indicates any other value or dump of values which may need to be logged alongside the URL for adequate documentation.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 180,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Database',
            'FldEng' => 'Log Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which this Logged Action was taken on.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 181,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Table',
            'FldEng' => 'Log Table ID',
            'FldDesc' => 'Indicates the unique Table ID number which this Logged Action was taken on.',
            'FldForeignTable' => '9',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 182,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Field',
            'FldEng' => 'Log Field ID',
            'FldDesc' => 'Indicates the unique Field ID number which this Logged Action was taken on.',
            'FldForeignTable' => '10',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 183,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Action',
            'FldEng' => 'Type Of Action',
            'FldDesc' => 'Indicates what type of Action on the Database Design is being Logged.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'New;Edit',
            'FldDataLength' => '20',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldCompareSame' => '878800',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 184,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'OldName',
            'FldEng' => 'Old Name',
            'FldDesc' => 'Indicates the old name of this Database Table or Field, to maintain a history of such important changes.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 185,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'NewName',
            'FldEng' => 'New Name',
            'FldDesc' => 'Indicates the new name of this Database Table or Field, to maintain a history of such important changes.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 186,
            'FldDatabase' => '3',
            'FldTable' => '23',
            'FldSpecSource' => '0',
            'FldName' => 'User',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID related to the User whose actions are being logged in this record.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '13'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1383,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldSpecSource' => '0',
            'FldName' => 'Zip',
            'FldEng' => 'Zip Code',
            'FldDesc' => 'Indicates the unique United States postal code being detailed in this record.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1384,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Lat',
            'FldEng' => 'Latitude',
            'FldDesc' => 'Indicates the geographic latitude of this zip code\'s location.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1385,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Long',
            'FldEng' => 'Longitude',
            'FldDesc' => 'Indicates the geographic longitude of this zip code\'s location.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1386,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'City',
            'FldEng' => 'City',
            'FldDesc' => 'Indicates the city of this zip code\'s location.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1387,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'State',
            'FldEng' => 'State',
            'FldDesc' => 'Indicates the state of this zip code\'s location.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '5',
            'FldCharSupport' => ',Letters,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1388,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'County',
            'FldEng' => 'County',
            'FldDesc' => 'Indicates the county of this zip code\'s location.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1664,
            'FldDatabase' => '3',
            'FldTable' => '193',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Contact Type',
            'FldDesc' => 'Indicates which type of contact the site visitor is making with us.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1665,
            'FldDatabase' => '3',
            'FldTable' => '193',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Flag',
            'FldEng' => 'Flag',
            'FldDesc' => 'Indicates the current internal status of this Contact submission, important for organizing contact records which need attention by system administrators.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Unread;Read;Trash',
            'FldDefault' => 'Unread',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1666,
            'FldDatabase' => '3',
            'FldTable' => '193',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Email',
            'FldEng' => 'Email Address',
            'FldDesc' => 'Indicates the email address of the site visitor who completed the Contact Form, important for responding to them.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6',
            'FldCompareOther' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1667,
            'FldDatabase' => '3',
            'FldTable' => '193',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Subject',
            'FldEng' => 'Message Subject Line',
            'FldDesc' => 'Indicates the subject line (or title) of the site visitor\'s Contact Form submission. This is important for more quickly identifying and classifying the nature of the request.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1668,
            'FldDatabase' => '3',
            'FldTable' => '193',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Body',
            'FldEng' => 'Message Body',
            'FldDesc' => 'Indicates the main body of the site visitor\'s Contact Form submission, providing all the rest of the details of their request. This also includes a dump of any other fields which may have been built into the Contact Form.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1678,
            'FldDatabase' => '3',
            'FldTable' => '195',
            'FldSpecSource' => '0',
            'FldName' => 'TreeID',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID number which this record belongs to.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1679,
            'FldDatabase' => '3',
            'FldTable' => '195',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'RecID',
            'FldEng' => 'Submission Record ID',
            'FldDesc' => 'Indicates the unique record ID number whose data is being cached here.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1680,
            'FldDatabase' => '3',
            'FldTable' => '195',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Dump',
            'FldEng' => 'Full Record Dump',
            'FldDesc' => 'Stores a cache of all raw data from a specific submission record, including English translations for Definition IDs, etc.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1681,
            'FldDatabase' => '3',
            'FldTable' => '196',
            'FldSpecSource' => '0',
            'FldName' => 'UserID',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID number whose interactions are being logged here. Important for ensuring that one User cannot tag someone\'s completed submission more than once.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1682,
            'FldDatabase' => '3',
            'FldTable' => '196',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'TreeID',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID number that the record being tagged belongs to. Vital for knowing which table the record ID refers to.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1683,
            'FldDatabase' => '3',
            'FldTable' => '196',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'RecID',
            'FldEng' => 'Submission Record ID',
            'FldDesc' => 'Indicates the unique record ID number which is being tagged with this row.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1684,
            'FldDatabase' => '3',
            'FldTable' => '196',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'DefID',
            'FldEng' => 'Tag Definition ID',
            'FldDesc' => 'Indicates the unique Definition ID number belonging to the specific tag or interaction being logged in this row.',
            'FldForeignTable' => '7',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1690,
            'FldDatabase' => '3',
            'FldTable' => '198',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Token Type',
            'FldDesc' => 'Indicates which type of Token has been created and stored here. ',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'Confirm Email;Sensitive;MFA',
            'FldDataLength' => '20',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1691,
            'FldDatabase' => '3',
            'FldTable' => '198',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'UserID',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID number whose token is logged in this row.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1692,
            'FldDatabase' => '3',
            'FldTable' => '198',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'TreeID',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID number to which this token relates.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1693,
            'FldDatabase' => '3',
            'FldTable' => '198',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'CoreID',
            'FldEng' => 'Submission Record ID',
            'FldDesc' => 'Indicates the unique record ID number of the completed submission to which this Token relates.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1694,
            'FldDatabase' => '3',
            'FldTable' => '198',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'TokToken',
            'FldEng' => 'Generated Token',
            'FldDesc' => 'Indicates the randomly generated string which is stored as a Token for a User\'s specific interaction with the database.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '255',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1669,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID which sent this email.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1670,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'RecID',
            'FldEng' => 'Core Record ID',
            'FldDesc' => 'Indicates the unique Record ID of the core table of the Tree sending this email.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1671,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'EmailID',
            'FldEng' => 'Email Template ID',
            'FldDesc' => 'Indicates the unique Email Template ID which is being sent.',
            'FldForeignTable' => '36',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1672,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'To',
            'FldEng' => 'Recipient Email Address',
            'FldDesc' => 'The email address this message is being sent to.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1673,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'ToUser',
            'FldEng' => 'Recipient User ID',
            'FldDesc' => 'Indicates the unique User ID of the User this email is being sent to.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '20',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1674,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'FromUser',
            'FldEng' => 'Sender User ID',
            'FldDesc' => 'Indicates the unique User ID of the User initiating this emailing.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1675,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Subject',
            'FldEng' => 'Subject Line',
            'FldDesc' => 'Indicates the subject line of this specific email, even if it is different than the default, auto-generated version from the Email Template.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1676,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'Body',
            'FldEng' => 'Email Body',
            'FldDesc' => 'Indicates the body of this specific email, even if it is different than the default, auto-generated version from the Email Template.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldCharSupport' => ',',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1677,
            'FldDatabase' => '3',
            'FldTable' => '194',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Options',
            'FldDesc' => 'Indicates the options or flags stored with this emailing.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800',
            'FldOperateSame' => '52'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1619,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldSpecSource' => '0',
            'FldName' => 'Tree',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID related to the home Tree of this Email Template.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1620,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Type',
            'FldDesc' => 'Indicates the general type of Email Template being stored here.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1621,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Name',
            'FldEng' => 'Template Name',
            'FldDesc' => 'Indicates the name of this Email Template, as it will be referred to internally within the system.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1622,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Subject',
            'FldEng' => 'Default Subject Line',
            'FldDesc' => 'Indicates the default subject line of emails sent out using this template.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1623,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Body',
            'FldEng' => 'Default Email Body',
            'FldDesc' => 'Indicates the default main content of emails sent out using this template.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1624,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Options',
            'FldDesc' => 'Indicates any options necessary to accurately define the properties of this Email Template.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1625,
            'FldDatabase' => '3',
            'FldTable' => '189',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'TotSent',
            'FldEng' => 'Total Emails Sent',
            'FldDesc' => 'Indicates the total number of individual Emails sent using this Email Template.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1627,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldSpecSource' => '0',
            'FldName' => 'DatabaseID',
            'FldEng' => 'Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which owns this media item.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1640,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldSpecSource' => '0',
            'FldName' => 'DatabaseID',
            'FldEng' => 'Database ID',
            'FldDesc' => 'Indicates the unique Database ID number which owns this media item.',
            'FldForeignTable' => '4',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1628,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'UserID',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID number who uploaded or owns this media item.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1641,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'UserID',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID number who uploaded or owns this media item.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1629,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'FileOrig',
            'FldEng' => 'Original Filename',
            'FldDesc' => 'Indicates the original filename, as it was uploaded.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1642,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'FileOrig',
            'FldEng' => 'Original Filename',
            'FldDesc' => 'Indicates the original filename, as it was uploaded.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1630,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'FileLoc',
            'FldEng' => 'Stored Filename',
            'FldDesc' => 'Indicates the filename as it was actually stored here on the server.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1643,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'FileLoc',
            'FldEng' => 'Stored Filename',
            'FldDesc' => 'Indicates the filename as it was actually stored here on the server.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1631,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'FullFilename',
            'FldEng' => 'Full File Location',
            'FldDesc' => 'Indicates the full path where this media file is actually stored here on the server (relative to the system app\'s root).',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1644,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'FullFilename',
            'FldEng' => 'Full File Location',
            'FldDesc' => 'Indicates the full path where this media file is actually stored here on the server (relative to the system app\'s root).',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1632,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Title',
            'FldEng' => 'Media Title',
            'FldDesc' => 'Indicates the title of this uploaded media, or a concise description of it. This is important as a label used internally throughout the system.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1645,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Title',
            'FldEng' => 'Media Title',
            'FldDesc' => 'Indicates the title of this uploaded media, or a concise description of it. This is important as a label used internally throughout the system.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1633,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Credit',
            'FldEng' => 'Media Credit (Legal)',
            'FldDesc' => 'Indicates any attribution required or appropriate to be shared alongside this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1646,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Credit',
            'FldEng' => 'Media Credit (Legal)',
            'FldDesc' => 'Indicates any attribution required or appropriate to be shared alongside this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1634,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'CreditUrl',
            'FldEng' => 'Media Credit URL (Legal)',
            'FldDesc' => 'Indicates an optional URL link for any attribution required or appropriate to be shared alongside this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1647,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'CreditUrl',
            'FldEng' => 'Media Credit URL (Legal)',
            'FldDesc' => 'Indicates an optional URL link for any attribution required or appropriate to be shared alongside this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1635,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'NodeID',
            'FldEng' => 'Node ID',
            'FldDesc' => 'Indicates the unique Node ID number used to upload this media item.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1648,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'NodeID',
            'FldEng' => 'Node ID',
            'FldDesc' => 'Indicates the unique Node ID number used to upload this media item.',
            'FldForeignTable' => '15',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1636,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Media Type',
            'FldDesc' => 'Indicates which allowed type of media upload this record tracks.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'jpg;png;gif',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1649,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Media Type',
            'FldDesc' => 'Indicates which allowed type of media upload this record tracks.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => 'jpg;png;gif',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1637,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'FileSize',
            'FldEng' => 'Media File Size',
            'FldDesc' => 'Indicates this media file\'s size in bytes here on the server.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1650,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'FileSize',
            'FldEng' => 'Media File Size',
            'FldDesc' => 'Indicates this media file\'s size in bytes here on the server.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1638,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'Width',
            'FldEng' => 'Media Width',
            'FldDesc' => 'Indicates the default width of this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1651,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'Width',
            'FldEng' => 'Media Width',
            'FldDesc' => 'Indicates the default width of this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1639,
            'FldDatabase' => '3',
            'FldTable' => '37',
            'FldOrd' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'Height',
            'FldEng' => 'Media Height',
            'FldDesc' => 'Indicates the default height of this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1652,
            'FldDatabase' => '3',
            'FldTable' => '190',
            'FldOrd' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'Height',
            'FldEng' => 'Media Height',
            'FldDesc' => 'Indicates the default height of this media item.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1653,
            'FldDatabase' => '3',
            'FldTable' => '16',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'MutEx',
            'FldEng' => 'Response Is Mutually Exclusive',
            'FldDesc' => 'Indicates whether or not this response option is mutually exclusive with all the other responses to this question (Node).',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 10267,
            'FldDatabase' => '3',
            'FldTable' => '166',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Country',
            'FldEng' => 'Country',
            'FldDesc' => 'Indicates the country where this Zip Code is located.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '100',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1515,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'SubmissionProgress',
            'FldEng' => 'Experience Node Progress',
            'FldDesc' => 'Indicates the unique Node ID number of the last Experience Node loaded during this User\'s Experience.',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldCharSupport' => ',Numbers,',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1516,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'TreeVersion',
            'FldEng' => 'Tree Version Number',
            'FldDesc' => 'Stores the current version number of this User Experience, important for tracking bugs.',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1517,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'VersionAB',
            'FldEng' => 'A/B Testing Version',
            'FldDesc' => 'Stores a complex string reflecting all A/B Testing variations in effect at the time of this User\'s Experience of this Node.',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1518,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'UniqueStr',
            'FldEng' => 'Unique String For Record',
            'FldDesc' => 'This unique string is for cases when including the record ID number is not appropriate.',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1519,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'IPaddy',
            'FldEng' => 'IP Address',
            'FldDesc' => 'Encrypted IP address of the current user.',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1520,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'IsMobile',
            'FldEng' => 'Using Mobile Device',
            'FldDesc' => 'Indicates whether or not the current user is interacting via a mobile deviced.',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1654,
            'FldDatabase' => '3',
            'FldTable' => '191',
            'FldSpecSource' => '0',
            'FldName' => 'Address',
            'FldEng' => 'Full Address',
            'FldDesc' => 'Indicates the full address, in one line, which is being linked with latitude and longitude coordinates.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1655,
            'FldDatabase' => '3',
            'FldTable' => '191',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'Lat',
            'FldEng' => 'Latitude',
            'FldDesc' => 'Indicates the Address\'s latitude is a geographic coordinate that specifies the north–south position of a point on the Earth\'s surface.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1656,
            'FldDatabase' => '3',
            'FldTable' => '191',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Long',
            'FldEng' => 'Longitude',
            'FldDesc' => 'Indicates the Address\'s longitude is a geographic coordinate that specifies the east-west position of a point on the Earth\'s surface.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1559,
            'FldDatabase' => '3',
            'FldTable' => '3',
            'FldSpecType' => 'Replica',
            'FldName' => 'UserID',
            'FldEng' => 'User ID',
            'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Experience.',
            'FldForeignTable' => '28',
            'FldForeignMin' => '11',
            'FldForeignMax' => '11',
            'FldForeign2Min' => '11',
            'FldForeign2Max' => '11',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldOpts' => '39'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 10268,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'IsActive',
            'FldEng' => 'Is Active',
            'FldDesc' => 'Indicates whether or not this session is currently active, or editable. This allows us to avoid permanently deleting these records valuable for audits.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1685,
            'FldDatabase' => '3',
            'FldTable' => '197',
            'FldSpecSource' => '0',
            'FldName' => 'SessID',
            'FldEng' => 'Session ID',
            'FldDesc' => 'Indicates the unique Session ID for the User Session who loaded this page.',
            'FldForeignTable' => '42',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1686,
            'FldDatabase' => '3',
            'FldTable' => '197',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'URL',
            'FldEng' => 'URL Loaded',
            'FldDesc' => 'Indicates the URL which is currently being loaded, or redirected through on the server-side.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6',
            'FldCompareOther' => '6',
            'FldCompareValue' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1687,
            'FldDatabase' => '3',
            'FldTable' => '199',
            'FldSpecSource' => '0',
            'FldName' => 'UserID',
            'FldEng' => 'Session User ID',
            'FldDesc' => 'Indicates the unique User ID for the Database User logged in and using this Site Session record.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1688,
            'FldDatabase' => '3',
            'FldTable' => '199',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'Browser',
            'FldEng' => 'Session Browser',
            'FldDesc' => 'Indicates the web browser used during this Session.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '255',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1689,
            'FldDatabase' => '3',
            'FldTable' => '199',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'IsMobile',
            'FldEng' => 'Using Mobile Device',
            'FldDesc' => 'Indicates whether or not the User is currently using a mobile device (1 if yes).
',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldValues' => '0;1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '1',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 10266,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'IP',
            'FldEng' => 'IP Address',
            'FldDesc' => 'Encrypted IP address of the current user.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 309,
            'FldDatabase' => '3',
            'FldTable' => '43',
            'FldSpecSource' => '0',
            'FldName' => 'TreeID',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID number which this Upload belongs to. This is important to track the journey to this upload.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1695,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldSpecSource' => '0',
            'FldName' => 'TreeID',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID number which this Upload belongs to. This is important to track the journey to this upload.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1696,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'CoreID',
            'FldEng' => 'Core Record ID',
            'FldDesc' => 'Indicates the unique ID number associated the Core Record for whichever Tree this Upload belongs to. This is important to track the journey to this upload.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1697,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Upload Type',
            'FldDesc' => 'Indicates the type of upload as defined by the unique Definition ID number from the Definitions set \'Value Ranges\' and subset \'Upload Types\'.',
            'FldForeignTable' => '7',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1698,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Privacy',
            'FldEng' => 'Privacy Setting',
            'FldDesc' => 'Indicates whether or not the user wants this uploaded file to be publicly published or for administrators\' eyes only.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '10',
            'FldCharSupport' => ',Letters,Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1699,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Title',
            'FldEng' => 'Title of File',
            'FldDesc' => 'Indicates a brief name by which this file can be referenced in English.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1700,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Desc',
            'FldEng' => 'Description of File',
            'FldDesc' => 'Provides a longer description for this uploaded file, if needed.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1701,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '7',
            'FldSpecSource' => '0',
            'FldName' => 'UploadFile',
            'FldEng' => 'Filename as Uploaded',
            'FldDesc' => 'Indicates the original filename for this upload when it got to the server.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1702,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '8',
            'FldSpecSource' => '0',
            'FldName' => 'StoredFile',
            'FldEng' => 'Filename as Stored',
            'FldDesc' => 'Indicates the new filename for this upload as it is stored on the server.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1703,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '9',
            'FldSpecSource' => '0',
            'FldName' => 'VideoLink',
            'FldEng' => 'Video Link URL',
            'FldDesc' => 'Indicates the URL of a video being "uploaded". So far, only YouTube is supported for automatically embeded previews.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1704,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '10',
            'FldSpecSource' => '0',
            'FldName' => 'VideoDuration',
            'FldEng' => 'Video Duration',
            'FldDesc' => 'Indicates the duration of the video in seconds.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6',
            'FldOperateSame' => '137200',
            'FldOperateOther' => '137200'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1705,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'NodeID',
            'FldEng' => 'Tree Node ID',
            'FldDesc' => 'Indicates the unique Node ID number (within the Tree) from which this file was uploaded. This is potentially important for tracking exactly when the user uploaded this.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1706,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'LinkFldID',
            'FldEng' => 'Link Field ID',
            'FldDesc' => 'Indicates the unique Database Field ID which this uploaded file is related to.',
            'FldForeignTable' => '10',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1707,
            'FldDatabase' => '3',
            'FldTable' => '200',
            'FldOrd' => '12',
            'FldSpecSource' => '0',
            'FldName' => 'LinkRecID',
            'FldEng' => 'Link Record ID',
            'FldDesc' => 'Indicates the unique Record ID from the Database Table owning the associated Field, if applicable.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1657,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldSpecSource' => '0',
            'FldName' => 'Type',
            'FldEng' => 'Cache Type',
            'FldDesc' => 'Indicates which type of cache record this is.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '12',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1658,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldOrd' => '1',
            'FldSpecSource' => '0',
            'FldName' => 'TreeID',
            'FldEng' => 'Tree ID',
            'FldDesc' => 'Indicates the unique Tree ID number which generated this cache.',
            'FldForeignTable' => '5',
            'FldForeignMin' => '0',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => '0',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1659,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldOrd' => '2',
            'FldSpecSource' => '0',
            'FldName' => 'RecID',
            'FldEng' => 'Core Record ID',
            'FldDesc' => 'Indicates the unique Core Record ID number which generated this cache.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',Foreign,',
            'FldNullSupport' => '0',
            'FldCompareSame' => '878800'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1660,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldOrd' => '3',
            'FldSpecSource' => '0',
            'FldName' => 'Key',
            'FldEng' => 'Cache Key String',
            'FldDesc' => 'Indicates the unique key string used to locate this cached content.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1661,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldOrd' => '4',
            'FldSpecSource' => '0',
            'FldName' => 'Value',
            'FldEng' => 'Cache Content Value',
            'FldDesc' => 'Indicates the actual value of the cached content.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1662,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Css',
            'FldEng' => 'Cache Content CSS',
            'FldDesc' => 'Indicates CSS needed for the cached content.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1663,
            'FldDatabase' => '3',
            'FldTable' => '192',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'Js',
            'FldEng' => 'Cache Content JS',
            'FldDesc' => 'Indicates JS needed for the cached content.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldType' => 'TEXT',
            'FldDataLength' => '0',
            'FldKeyType' => ',',
            'FldNullSupport' => '0'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1616,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '5',
            'FldSpecSource' => '0',
            'FldName' => 'Slug',
            'FldEng' => 'Tree URL',
            'FldDesc' => 'Indicates the URL for the starting page of this Tree.',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Letters,Numbers,Keyboard,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '6'
        ]);
        DB::table('SL_Fields')->insert([
            'FldID' => 1617,
            'FldDatabase' => '3',
            'FldTable' => '5',
            'FldOrd' => '11',
            'FldSpecSource' => '0',
            'FldName' => 'Opts',
            'FldEng' => 'Tree Options',
            'FldDesc' => 'Indicates the multiple of various prime numbers which mark specific other qualities of this Node.',
            'FldNotes' => '%3 Admin-Only; %5 Tree That Is One Big SurvLoop; %7 Area Home Page (for Public, Admin, or Volun); %11 Record Edits Not Allowed (except by Admins); %13 Report for Survey (linked and shares core table); %17 Volunteers Access (& Admin); %19 Contact Form (Auto Page); %23 Page Is Skinny; %29 Page Not [Yet] Simple Enough To Be Cached; %31 Search Bar Results Page (for Public, Admin, or Volun); %37 Survey Navigation Menu Bottom; %41 Partners Member Access; %43 Staff Access; %47 Uses Public ID#; %53 Has Page Form; %59 Survey Navigation Menu Top; %61 Survey Progress Line',
            'FldForeignMin' => 'N',
            'FldForeignMax' => 'N',
            'FldForeign2Min' => 'N',
            'FldForeign2Max' => 'N',
            'FldDefault' => '1',
            'FldType' => 'INT',
            'FldDataType' => 'Numeric',
            'FldDataLength' => '0',
            'FldCharSupport' => ',Numbers,',
            'FldKeyType' => ',',
            'FldNullSupport' => '0',
            'FldCompareSame' => '20',
            'FldOperateValue' => '52'
        ]);
    
    DB::table('SL_Definitions')->insert([
            'DefID' => 35,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefValue' => 'New Database',
            'DefDescription' => 'This is part of the SurvLoop installation process, where a user creates a new Database and a primary/default Experience to go with it.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 36,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '5',
            'DefValue' => 'New Experience',
            'DefDescription' => 'Create a new, secondary Experience for an existing Database.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 37,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '3',
            'DefValue' => 'Add a Data Field',
            'DefDescription' => 'Create a new Field in the Database, without adding it as an Experience Node yet.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 38,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '7',
            'DefValue' => 'New Experience Question',
            'DefDescription' => 'Create a new Question Node in a User Experience. This might include a simplified version of adding a new Database Field.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 39,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '1',
            'DefValue' => 'Add a Data Table',
            'DefDescription' => 'Create a new Database Table to later fill with Fields.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 40,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '2',
            'DefValue' => 'Edit Database Table',
            'DefDescription' => 'Edit the basic properties of a Database Table.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 41,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '4',
            'DefValue' => 'Edit Database Field',
            'DefDescription' => 'Edit the basic or thorough properties of a Database Field.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 42,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '6',
            'DefValue' => 'Edit Experience',
            'DefDescription' => 'Edit the basic properties of a User Experience.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 43,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefOrder' => '8',
            'DefValue' => 'Edit Experience Question',
            'DefDescription' => 'Edit the basic properties of a Experience Question Node.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 44,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefValue' => 'Value Ranges',
            'DefDescription' => 'Each definition in a set of Value Ranges represents one response a user can choose when responding to some question/prompt.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 45,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '1',
            'DefValue' => 'System Settings',
            'DefDescription' => 'Each definition for System Settings represents one system-wide specification.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 46,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '2',
            'DefValue' => 'Style Settings',
            'DefDescription' => 'Each definition for Style Settings represents one color or other branding element needed system-wide.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 47,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '3',
            'DefValue' => 'Instructions',
            'DefDescription' => 'Each definition which is an Instruction represents one blurb, used somewhere in the system, which can be edited by system administrators.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 48,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '4',
            'DefValue' => 'Diagrams',
            'DefDescription' => 'Each definition which is a Diagram represents one document uploaded by system administrators.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 49,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '5',
            'DefValue' => 'Custom Settings',
            'DefDescription' => 'Each definition for Custom Settings represents one system-wide specification, which is established and defined by a client-specific installation of SurvLoop.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 50,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '6',
            'DefValue' => 'User Roles',
            'DefDescription' => 'Each definition for User Roles represents one system-wide type of user permissions.'
        ]);
    

    DB::table('SL_Tree')->insert([
            'TreeID' => 3,
            'TreeDatabase' => '3',
            'TreeUser' => '0',
            'TreeType' => 'Survey',
            'TreeName' => 'SurvLoop Database Designer',
            'TreeDesc' => 'SurvLoop users can add a new field to the database and/or user experience.',
            'TreeSlug' => 'survloop-design',
            'TreeRoot' => '7',
            'TreeFirstPage' => '8',
            'TreeLastPage' => '8',
            'TreeCoreTable' => '3'
        ]);
        DB::table('SL_Tree')->insert([
            'TreeID' => 4,
            'TreeDatabase' => '3',
            'TreeUser' => '0',
            'TreeType' => 'Survey XML',
            'TreeName' => 'SurvLoop Database Designer',
            'TreeSlug' => 'survloop-design',
            'TreeRoot' => '1171',
            'TreeCoreTable' => '3'
        ]);
    
    DB::table('SL_Node')->insert([
            'NodeID' => 7,
            'NodeTree' => '3',
            'NodeType' => 'Branch Title',
            'NodePromptText' => 'Database Designer',
            'NodePromptNotes' => 'welcome'
        ]);
        DB::table('SL_Node')->insert([
            'NodeID' => 9,
            'NodeTree' => '3',
            'NodeParentID' => '11',
            'NodeType' => 'Text',
            'NodePromptText' => '<h2 class="slBlueDark">Welcome</h2><br />What kind of cool data do you want to collect and share?'
        ]);
        DB::table('SL_Node')->insert([
            'NodeID' => 10,
            'NodeTree' => '4',
            'NodeType' => 'Text',
            'NodePromptText' => 'DesignTweaks',
            'NodePromptNotes' => '3'
        ]);
        DB::table('SL_Node')->insert([
            'NodeID' => 11,
            'NodeTree' => '3',
            'NodeParentID' => '7',
            'NodeType' => 'Page',
            'NodePromptText' => 'Welcome To Database Designer',
            'NodePromptNotes' => 'welcome',
            'NodePromptAfter' => 'Welcome To Database Designer - Your Complaint::M::::M::::M::'
        ]);
        DB::table('SL_Node')->insert([
            'NodeID' => 1171,
            'NodeTree' => '4',
            'NodeType' => 'XML',
            'NodePromptText' => 'DesignTweaks',
            'NodePromptNotes' => '3'
        ]);
    

    DB::table('SL_Conditions')->insert([
            'CondID' => 45,
            'CondDatabase' => '0',
            'CondTag' => '#NodeDisabled',
            'CondDesc' => 'This node is not active (for the public).',
            'CondOperator' => 'CUSTOM',
            'CondOperDeet' => '0',
            'CondField' => '0',
            'CondTable' => '0',
            'CondLoop' => '0'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 60,
            'CondDatabase' => '0',
            'CondTag' => '#IsAdmin',
            'CondDesc' => 'The user is currently logged in as an administrator.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 61,
            'CondDatabase' => '0',
            'CondTag' => '#IsNotAdmin',
            'CondDesc' => 'The user is not currently logged in as an administrator.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 63,
            'CondDatabase' => '0',
            'CondTag' => '#IsNotLoggedIn',
            'CondDesc' => 'Complainant is not currently logged into the system.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 66,
            'CondDatabase' => '0',
            'CondTag' => '#IsLoggedIn',
            'CondDesc' => 'Complainant is currently logged into the system.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 93,
            'CondDatabase' => '0',
            'CondTag' => '#EmailVerified',
            'CondDesc' => 'Current user\'s email address has been verified.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 111,
            'CondDatabase' => '0',
            'CondTag' => '#IsOwner',
            'CondDesc' => 'The user is currently logged is the owner of this record.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 112,
            'CondDatabase' => '0',
            'CondTag' => '#IsPrintable',
            'CondDesc' => 'The current page view is intended to be printable.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 114,
            'CondDatabase' => '0',
            'CondTag' => '#IsPrintInFrame',
            'CondDesc' => 'The current page view is printed into frame/ajax/widget.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 115,
            'CondDatabase' => '0',
            'CondTag' => '#IsDataPermPublic',
            'CondDesc' => 'The current data permissions are set to public.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 116,
            'CondDatabase' => '0',
            'CondTag' => '#IsDataPermPrivate',
            'CondDesc' => 'The current data permissions are set to private.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 117,
            'CondDatabase' => '0',
            'CondTag' => '#IsDataPermSensitive',
            'CondDesc' => 'The current data permissions are set to sensitive.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 118,
            'CondDatabase' => '0',
            'CondTag' => '#IsDataPermInternal',
            'CondDesc' => 'The current data permissions are set to internal.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 120,
            'CondDatabase' => '0',
            'CondTag' => '#HasTokenDialogue',
            'CondDesc' => 'Current page load includes an access token dialogue.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 122,
            'CondDatabase' => '0',
            'CondTag' => '#TestLink',
            'CondDesc' => 'Current page url parameters includes ?test=1.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 123,
            'CondDatabase' => '0',
            'CondTag' => '#NextButton',
            'CondDesc' => 'Current page load results from clicking the survey\'s next button.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 124,
            'CondDatabase' => '0',
            'CondTag' => '#IsProfileOwner',
            'CondDesc' => 'The user is currently logged in owns this user profile.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 130,
            'CondDatabase' => '0',
            'CondTag' => '#IsStaff',
            'CondDesc' => 'The user is currently logged in as a staff user.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 131,
            'CondDatabase' => '0',
            'CondTag' => '#IsPartner',
            'CondDesc' => 'The user is currently logged in as a partner.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 132,
            'CondDatabase' => '0',
            'CondTag' => '#IsVolunteer',
            'CondDesc' => 'The user is currently logged in as a volunteer.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 133,
            'CondDatabase' => '0',
            'CondTag' => '#IsBrancher',
            'CondDesc' => 'The user is currently logged in as a database manager.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 134,
            'CondDatabase' => '0',
            'CondTag' => '#IsStaffOrAdmin',
            'CondDesc' => 'The user is currently logged in as a staff or admin user.',
            'CondOperator' => 'CUSTOM'
        ]);
        DB::table('SL_Conditions')->insert([
            'CondID' => 143,
            'CondDatabase' => '0',
            'CondTag' => '#IsPartnerStaffOrAdmin',
            'CondDesc' => 'The user is currently logged in as a partner, staff, or admin user.',
            'CondOperator' => 'CUSTOM'
        ]);
    

    DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 163,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '491'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 164,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '501'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 165,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '507'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 166,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '513'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 229,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '381'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 233,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '485'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 242,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '873'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 262,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '776'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 263,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '780'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 264,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 265,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '978'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 266,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 267,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '979'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 268,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '977'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 269,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '771'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 270,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '773'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 271,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '980'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 272,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '981'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 273,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '982'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 274,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '454'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 292,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '611'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 305,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '232'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 318,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1195'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 320,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '792'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 371,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1011'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 372,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1435'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 374,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '486'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 375,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '494'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 376,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '496'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 377,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '497'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 387,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '1675'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 388,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '1703'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 389,
            'CondNodeCondID' => '111',
            'CondNodeNodeID' => '1704'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 391,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1705',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 394,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '1711',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 396,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '1704',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 397,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '1705',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 398,
            'CondNodeCondID' => '115',
            'CondNodeNodeID' => '1719'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 408,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1752',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 409,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '1752',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 410,
            'CondNodeCondID' => '120',
            'CondNodeNodeID' => '1758'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 413,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '849'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 422,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '40'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 424,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1004'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 428,
            'CondNodeCondID' => '124',
            'CondNodeNodeID' => '1437',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 429,
            'CondNodeCondID' => '124',
            'CondNodeNodeID' => '1893'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 434,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '1996'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 435,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '1997'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 436,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '1995',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 438,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '138'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 439,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2017'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 441,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '484'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 465,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '1348',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 466,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '1003'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 477,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '2066',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 478,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '2067'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 479,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '2068'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 489,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '483'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 506,
            'CondNodeCondID' => '130',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 510,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '2074'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 511,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '2118',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 528,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '1937'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 529,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1754'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 530,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '2163',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 532,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '2163'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 535,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '1955'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 536,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '2116'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 538,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '1956',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 543,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2242'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 544,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2244'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 545,
            'CondNodeCondID' => '122',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 546,
            'CondNodeCondID' => '122',
            'CondNodeNodeID' => '2255'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 547,
            'CondNodeCondID' => '122',
            'CondNodeNodeID' => '2256'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 553,
            'CondNodeCondID' => '122',
            'CondNodeNodeID' => '2260'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 557,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2265'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 564,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1977'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 580,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1794',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 581,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '2324',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 584,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '2334'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 585,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1675',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 586,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '2336',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 588,
            'CondNodeCondID' => '134',
            'CondNodeNodeID' => '2324'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 589,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1703',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 590,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1711',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 591,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1704',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 592,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '2163',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 593,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '2333'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 594,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1569'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 597,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2323'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 603,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2324'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 604,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 605,
            'CondNodeCondID' => '114',
            'CondNodeNodeID' => '2379'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 606,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1347'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 608,
            'CondNodeCondID' => '112',
            'CondNodeNodeID' => '1467',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 609,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2339'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 610,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1125'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 633,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2143'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 637,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2639'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 638,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 639,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2641'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 640,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2642'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 641,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1884'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 645,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2010',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 646,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2646'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 647,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1191'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 649,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '-3',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 650,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2648',
            'CondNodeLoopID' => '-1'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 652,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2651'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 653,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2652'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 654,
            'CondNodeCondID' => '60',
            'CondNodeNodeID' => '2653'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 660,
            'CondNodeCondID' => '63',
            'CondNodeNodeID' => '2666'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 661,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 662,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '2667'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 663,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '-3'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 664,
            'CondNodeCondID' => '66',
            'CondNodeNodeID' => '2668'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 667,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '2370'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 671,
            'CondNodeCondID' => '45',
            'CondNodeNodeID' => '1860'
        ]);
        DB::table('SL_ConditionsNodes')->insert([
            'CondNodeID' => 688,
            'CondNodeCondID' => '122',
            'CondNodeNodeID' => '2719'
        ]);
          
    }
}