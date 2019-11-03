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
            'DbTables' => '26',
            'DbFields' => '200'
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
            'TblNumForeignIn' => '7'
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
            'TblNumForeignIn' => '6'
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
            'TblNumForeignKeys' => '1'
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
            'TblNumForeignIn' => '3'
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
            'TblNumFields' => '2',
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
            'TblNumForeignIn' => '11'
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
            'TblNumFields' => '5',
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
            'TblNumFields' => '9',
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
            'TblNumForeignIn' => '7'
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
            'TblNumFields' => '6'
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
            'FldID' => 160,
            'FldDatabase' => '3',
            'FldTable' => '19',
            'FldOrd' => '6',
            'FldSpecSource' => '0',
            'FldName' => 'ZoomPref',
            'FldEng' => 'Zoom Preference',
            'FldDesc' => 'Indicates the User\'s current preference of visual zoom of the page.',
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
            'DefID' => 256,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefIsActive' => '0',
            'DefValue' => 'Fatal injuries',
            'DefDescription' => 'LEVEL 1 (most serious) Injury list from https://www.sanjoseca.gov/DocumentCenter/View/55841'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 1,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'cust-abbr',
            'DefDescription' => 'OpenPolice'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 257,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Major bone broken',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 2,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'app-url',
            'DefDescription' => 'http://openpolice.local'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 258,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Compound fracture',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 3,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'logo-url',
            'DefDescription' => 'http://openpolice.local'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 259,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'In-patient hospital stay required',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 4,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'site-name',
            'DefDescription' => 'OpenPolice.org'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 260,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Blood loss requiring transfusion',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 5,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'meta-title',
            'DefDescription' => 'OpenPolice.org: Share Your Story'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 261,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Major concussion',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 6,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'meta-desc',
            'DefDescription' => 'OpenPolice.org helps you prepare, file, and track reports of police conduct. Whether a complaint or compliment, your story is too important to be ignored.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 262,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Longer than brief loss of consciousness',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 7,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'meta-keywords',
            'DefDescription' => 'Open Police, OpenPolice.org, Open Police Complaints, Police Departments, Cops, Police, Officers, file complaint, submit, victims, accountability, oversight, open source, transparency, #BlackLivesMatter, #BLM'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 263,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Debilitating chronic pain',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 8,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'meta-img',
            'DefDescription' => '/openpolice/uploads/meta-img-openpoliceorg.jpg'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 264,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Damage to organ (other than skin)',
            'DefDescription' => 'LEVEL 1 (most serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 9,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'logo-img-lrg',
            'DefDescription' => '/openpolice/uploads/Flex_OpenPolice.org-sm.jpg'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 265,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Taser Wounds',
            'DefDescription' => 'LEVEL 1 (most serious) was "Effective Tasings"'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 10,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'logo-img-md',
            'DefDescription' => '/openpolice/uploads/Flex_OpenPolice.org-sm.jpg'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 266,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Minor bone broken',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 11,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'logo-img-sm',
            'DefDescription' => '/openpolice/Flex_Open_1LineBox_v3_short-top.png'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 267,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '11',
            'DefIsActive' => '0',
            'DefValue' => 'Major laceration requiring stitches',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 12,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'shortcut-icon',
            'DefDescription' => '/openpolice/logo-ico.png'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 268,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '12',
            'DefIsActive' => '0',
            'DefValue' => 'Minor concussion',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 269,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '13',
            'DefIsActive' => '0',
            'DefValue' => 'Brief loss of consciousness',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 14,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'tree-1-upload-types',
            'DefDescription' => 'Evidence Types'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 270,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '14',
            'DefIsActive' => '0',
            'DefValue' => 'Chipped or lost tooth',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 15,
            'DefDatabase' => '1',
            'DefSet' => 'User Roles',
            'DefSubset' => 'administrator',
            'DefValue' => 'Administrator',
            'DefDescription' => 'Highest system administrative privileges, can add, remove, and change permissions of other users'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 271,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '15',
            'DefIsActive' => '0',
            'DefValue' => 'Major abrasion',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 16,
            'DefDatabase' => '1',
            'DefSet' => 'User Roles',
            'DefSubset' => 'staff',
            'DefOrder' => '2',
            'DefValue' => 'Staff/Analyst',
            'DefDescription' => 'Full staff priveleges, can view but not edit technical specs'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 272,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '16',
            'DefIsActive' => '0',
            'DefValue' => 'Sprain',
            'DefDescription' => 'LEVEL 2 (serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 17,
            'DefDatabase' => '1',
            'DefSet' => 'User Roles',
            'DefSubset' => 'volunteer',
            'DefOrder' => '4',
            'DefValue' => 'Volunteer',
            'DefDescription' => 'Basic permission to pages and tools just for volunteers'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 273,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '17',
            'DefIsActive' => '0',
            'DefValue' => 'Bruising',
            'DefDescription' => 'LEVEL 3 (least serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 18,
            'DefDatabase' => '1',
            'DefSet' => 'User Roles',
            'DefSubset' => 'databaser',
            'DefOrder' => '1',
            'DefValue' => 'Database Designer',
            'DefDescription' => 'Permissions to make edits in the database designing tools'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 274,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '18',
            'DefIsActive' => '0',
            'DefValue' => 'Minor laceration',
            'DefDescription' => 'LEVEL 3 (least serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 19,
            'DefDatabase' => '1',
            'DefSet' => 'User Roles',
            'DefSubset' => 'oversight',
            'DefOrder' => '3',
            'DefValue' => 'Police Oversight',
            'DefDescription' => 'Access to sensitive data in complaints and compliment submitted to their department.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 275,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '19',
            'DefIsActive' => '0',
            'DefValue' => 'Minor abrasion',
            'DefDescription' => 'LEVEL 3 (least serious)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 20,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'signup-instruct',
            'DefDescription' => '<p>Here you can finish, review, or update your complaint. Volunteers also login here.</p>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 276,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidating Displays Of Weapon',
            'DefIsActive' => '0',
            'DefValue' => 'No'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 21,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-off',
            'DefOrder' => '8',
            'DefDescription' => '#4C656D'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 277,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidating Displays Of Weapon',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Displayed Weapon'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 22,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-on',
            'DefOrder' => '7',
            'DefDescription' => '#2B3493'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 278,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidating Displays Of Weapon',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Drew Weapon'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 23,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-faint',
            'DefOrder' => '9',
            'DefDescription' => '#F5FBFF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 279,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidating Displays Of Weapon',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Pointed Weapon'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 24,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-danger-off',
            'DefOrder' => '12',
            'DefDescription' => '#F38C5F'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 280,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidating Displays Of Weapon',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Fired or Discharged Weapon'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 25,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-danger-on',
            'DefOrder' => '11',
            'DefDescription' => '#EC2327'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 281,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidating Displays Of Weapon',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Not sure'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 26,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-success-off',
            'DefOrder' => '14',
            'DefDescription' => '#29B76F'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 282,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidation Weapons',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Baton'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 27,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-success-on',
            'DefOrder' => '13',
            'DefDescription' => '#29B76F'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 283,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidation Weapons',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Taser'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 28,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'show-logo-title',
            'DefDescription' => '0'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 284,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidation Weapons',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Gun'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 29,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-logo',
            'DefOrder' => '20',
            'DefDescription' => '#63C6FF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 285,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidation Weapons',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Mace or Pepper Spray'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 30,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'users-create-db',
            'DefDescription' => '0'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 286,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidation Weapons',
            'DefIsActive' => '0',
            'DefValue' => 'K9 (Dog)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 287,
            'DefDatabase' => '1',
            'DefSubset' => 'Intimidation Weapons',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 32,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'app-license',
            'DefDescription' => 'Creative Commons Attribution-ShareAlike License'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 288,
            'DefDatabase' => '1',
            'DefSubset' => 'No Charges Filed',
            'DefIsActive' => '0',
            'DefValue' => 'N/A'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 33,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'app-license-url',
            'DefDescription' => 'http://creativecommons.org/licenses/by-sa/3.0/'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 289,
            'DefDatabase' => '1',
            'DefSubset' => 'No Charges Filed',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'ALL Charges Were Dropped Before Release'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 34,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'app-license-img',
            'DefDescription' => '/survloop/uploads/creative-commons-by-sa-88x31.png'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 290,
            'DefDatabase' => '1',
            'DefSubset' => 'No Charges Filed',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'No Charges Were Ever Filed'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 35,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Design Tweak Types',
            'DefValue' => 'New Database',
            'DefDescription' => 'This is part of the SurvLoop installation process, where a user creates a new Database and a primary/default Experience to go with it.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 291,
            'DefDatabase' => '1',
            'DefSubset' => 'Officer Discipline Types',
            'DefIsActive' => '0',
            'DefValue' => 'Training and/or Counseling'
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
            'DefID' => 292,
            'DefDatabase' => '1',
            'DefSubset' => 'Officer Discipline Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Letter of Reprimand'
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
            'DefID' => 293,
            'DefDatabase' => '1',
            'DefSubset' => 'Officer Discipline Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Suspension'
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
            'DefID' => 294,
            'DefDatabase' => '1',
            'DefSubset' => 'Officer Discipline Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Termination'
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
            'DefID' => 295,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefIsActive' => '0',
            'DefValue' => 'Unreviewed'
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
            'DefID' => 296,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Police Complaint'
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
            'DefID' => 297,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Not About Police'
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
            'DefID' => 298,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Abuse'
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
            'DefID' => 299,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Spam'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 44,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefValue' => 'Value Ranges',
            'DefDescription' => 'Each definition in a set of Value Ranges represents one response a user can choose when responding to some question/prompt.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 300,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Test'
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
            'DefID' => 301,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Type',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Not Sure'
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
            'DefID' => 302,
            'DefDatabase' => '1',
            'DefSubset' => 'Investigative Agency Types',
            'DefIsActive' => '0',
            'DefValue' => 'Civilian Oversight'
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
            'DefID' => 303,
            'DefDatabase' => '1',
            'DefSubset' => 'Investigative Agency Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Internal Affairs'
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
            'DefID' => 304,
            'DefDatabase' => '1',
            'DefSubset' => 'Privacy Types',
            'DefIsActive' => '0',
            'DefValue' => 'Submit Publicly'
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
            'DefID' => 305,
            'DefDatabase' => '1',
            'DefSubset' => 'Privacy Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Names Visible to Police but not Public'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 50,
            'DefDatabase' => '3',
            'DefSubset' => 'SurvLoop Definition Types',
            'DefOrder' => '6',
            'DefValue' => 'User Roles',
            'DefDescription' => 'Each definition for User Roles represents one system-wide type of user permissions.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 306,
            'DefDatabase' => '1',
            'DefSubset' => 'Privacy Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Completely Anonymous',
            'DefDescription' => 'Anonymous complaints would have no public-facing details for civilians or officers. This is an option the user has early in the complaint process.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 307,
            'DefDatabase' => '1',
            'DefSubset' => 'Privacy Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Anonymized',
            'DefDescription' => 'Anonymized complaints would have all open-ended questions scrubbed, only leaving aggregate-friendly data. This is an action take by an analyst or the complainant some time after the complaint was submitted.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 308,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefIsActive' => '0',
            'DefValue' => 'Cash'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 309,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Phone'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 310,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'TV'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 311,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Vehicle'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 312,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Real Estate'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 313,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Bank Account'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 314,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Boat'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 315,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Firearms'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 316,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Identity Documents'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 317,
            'DefDatabase' => '1',
            'DefSubset' => 'Property Seized Types',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Other Items'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 318,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefIsActive' => '0',
            'DefValue' => 'Asian'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 319,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Black/African/Caribbean'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 320,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Hispanic/Latinx'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 321,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Native American'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 322,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Pacific Islander'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 323,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'White/Caucasian'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 324,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 325,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Decline or Unknown'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 326,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefIsActive' => '0',
            'DefValue' => 'Walking Violation',
            'DefDescription' => 'e.g. Jay Walking'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 327,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Disturbing the Peace'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 328,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Public Intoxication'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 329,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Illegal Drug Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 330,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Suspected of Something Else'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 331,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Matched a Description of Someone'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 332,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Investigating Someone Else'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 333,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Providing Assistance/Responding to Call'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 334,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Other Reason'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 335,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Officer Did Not Give Reason For Stop',
            'DefDescription' => 'e.g. NYC Stop & Frisk'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 591,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'user-name-req',
            'DefDescription' => '0'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 336,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefIsActive' => '0',
            'DefValue' => 'Speeding'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 337,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Vehicle Defect'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 338,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Expired Registration'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 339,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'License Plate Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 340,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Illegal Turn or Lane Change'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 341,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Seatbelt or Cell Phone Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 342,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Stop Sign/Light Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 343,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Driving While Impaired',
            'DefDescription' => 'The officer stated this as the reason for stop, but this was not at a designated "sobriety checkpoint" (that option is below).'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 344,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Sobriety Checkpoint'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 345,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Border Checkpoint'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 346,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '11',
            'DefIsActive' => '0',
            'DefValue' => 'Other Reason'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 347,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '12',
            'DefIsActive' => '0',
            'DefValue' => 'Officer Did Not Give Reason For Stop'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 349,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Home or private residence (includes just outside the residence)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 350,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Workplace'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 351,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Airport'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 352,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefIsActive' => '0',
            'DefValue' => 'Outdoor public space (includes roads, sidewalks, parks, etc.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 353,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Indoor public space'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 101,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefIsActive' => '0',
            'DefValue' => '0-15'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 102,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => '16-24'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 103,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => '25-34'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 104,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => '35-44'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 360,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefValue' => 'Car'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 105,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => '45-54'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 361,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefOrder' => '2',
            'DefValue' => 'Van'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 106,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => '55-64'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 362,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefOrder' => '3',
            'DefValue' => 'Motorcycle'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 107,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => '65-74'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 363,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefOrder' => '4',
            'DefValue' => 'Bicycle'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 108,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => '75-84'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 364,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefOrder' => '5',
            'DefValue' => 'Boat'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 109,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Over 84'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 365,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefOrder' => '6',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 110,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Findings',
            'DefIsActive' => '0',
            'DefValue' => 'Exonerated',
            'DefDescription' => '"Exonerated" means that the conduct at issue occurred but is not a violation of department rules.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 366,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Types',
            'DefOrder' => '3',
            'DefValue' => 'Federal Law Enforcement'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 111,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Findings',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Unfounded',
            'DefDescription' => 'An "unfounded" adjudication means that the allegations are not true.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 367,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Types',
            'DefOrder' => '2',
            'DefValue' => 'State Police'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 112,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Findings',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Not Sustained',
            'DefDescription' => 'A "not sustained" or "not resolved" or "unresolved" adjudication means that there is insufficient evidence to prove or disprove the allegations by a preponderance of evidence.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 368,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Types',
            'DefValue' => 'Local Police'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 113,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Findings',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Sustained',
            'DefDescription' => 'A "founded" or "sustained" adjudication means that allegations are true by a preponderance of the evidence and that the conduct at issue is a violation of department rules.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 369,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Types',
            'DefOrder' => '1',
            'DefValue' => 'Sheriffs\' Offices'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 114,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Findings',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Other',
            'DefDescription' => 'This "other" category might include withdrawn complaints, complaints lacking sufficient information to complete an investigation, or complaints against officers not employed or no longer employed by the department.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 370,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Types',
            'DefOrder' => '4',
            'DefValue' => 'Other Police Departments'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 115,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefValue' => 'Unreasonable Force',
            'DefDescription' => '"Reasonable force" is the amount of effort required by police to compel an unwilling person to comply. So "unreasonable force" is any unnecessary or excessive force beyond what\'s required to do that.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 371,
            'DefDatabase' => '1',
            'DefSubset' => 'User Types',
            'DefIsActive' => '0',
            'DefValue' => 'Admin'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 116,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '1',
            'DefValue' => 'Wrongful Arrest',
            'DefDescription' => 'Police took someone into custody without a warrant or probable cause.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 372,
            'DefDatabase' => '1',
            'DefSubset' => 'User Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Investigative Agency'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 117,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '2',
            'DefValue' => 'Wrongful Detention',
            'DefDescription' => 'Police pulled over a vehicle or stopped someone without reasonable suspicion.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 373,
            'DefDatabase' => '1',
            'DefSubset' => 'User Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Customer'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 118,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '3',
            'DefValue' => 'Wrongful Entry',
            'DefDescription' => 'Police entered private property without a warrant or consent.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 374,
            'DefDatabase' => '1',
            'DefSubset' => 'User Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Civilian'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 119,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '4',
            'DefValue' => 'Wrongful Search',
            'DefDescription' => 'Police conducted a search without a warrant, probable cause, or consent.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 375,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefIsActive' => '0',
            'DefValue' => 'Threats'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 120,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '5',
            'DefValue' => 'Wrongful Property Seizure',
            'DefDescription' => 'Property seizure which violated the protections provided by the 4th Amendment of the United States Constitution.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 376,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Shouting'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 121,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '7',
            'DefValue' => 'Bias-Based Policing',
            'DefDescription' => 'An officer\'s conduct was based on a person\'s race, gender, appearance, nationality, religion, age, gender, sexual orientation, residence, disability, etc.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 377,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Cursing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 122,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '8',
            'DefValue' => 'Excessive Arrest Charges',
            'DefDescription' => 'Police filed arrest charges to punish someone beyond what\'s legal or appropriate.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 378,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Racism'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 379,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Sexism'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 124,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '9',
            'DefValue' => 'Excessive Citation',
            'DefDescription' => 'Police filed citations to punish someone beyond what’s legal or appropriate.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 380,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Homophobia'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 125,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '10',
            'DefValue' => 'Intimidating Display Of Weapon',
            'DefDescription' => 'Police pointed or flaunted a weapon to intimidate someone who isn\'t a threat.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 381,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Lies'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 126,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '12',
            'DefValue' => 'Sexual Assault',
            'DefDescription' => 'Any sexual act that a person is forced to engage in against their will. This includes any non-consensual sexual touching.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 382,
            'DefDatabase' => '1',
            'DefSubset' => 'Verbal Abuse Types',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Disrespect'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 127,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '14',
            'DefValue' => 'Policy or Procedure Violation',
            'DefDescription' => 'An officer took actions which did not follow appropriate policy, procedure, or guidelines.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 383,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefIsActive' => '0',
            'DefValue' => 'Female'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 128,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '15',
            'DefValue' => 'Conduct Unbecoming an Officer',
            'DefDescription' => 'A reasonable person would find the officer\'s on or off duty conduct to be unbecoming a police officer, and such conduct reflected adversely on the department.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 384,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Male'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 129,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '16',
            'DefValue' => 'Discourtesy',
            'DefDescription' => 'This includes the use of profanity, offensive language, loss of temper, verbal threats, impatience, or any discourteous behavior.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 385,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Female to male transgender'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 130,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '17',
            'DefValue' => 'Officer Refused To Provide ID',
            'DefDescription' => 'Example: An officer allegedly failed to provide the complainant with his name and badge number after the identification was requested.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 386,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Male to female transgender'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 131,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '18',
            'DefValue' => 'Miranda Rights',
            'DefDescription' => 'Officers failed to read an arresttee their Miranda rights. This is not necessarily against law or policy.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 387,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Not sure'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 132,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '19',
            'DefValue' => 'Neglect of Duty',
            'DefDescription' => 'The officer\'s inaction did not follow appropriate policy, procedure, or guidelines. These are things an officer should have done, but didn\'t do.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 388,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Other',
            'DefDescription' => 'http://www.hrc.org/resources/collecting-transgender-inclusive-gender-data-in-workplace-and-other-surveys'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 133,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefIsActive' => '0',
            'DefValue' => 'Assault on an Officer'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 389,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Intersex'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 134,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Disorderly Conduct'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 390,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Genderqueer/Androgynous'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 135,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Disturbing the Peace'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 391,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Cross-dresser'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 136,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Failure to Obey/Comply'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 392,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Transsexual'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 137,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Obstructing/Interfering'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 393,
            'DefDatabase' => '1',
            'DefSubset' => 'Gender Identity',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Transgender'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 138,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Resisting Arrest'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 394,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'ACLU (American Civil Liberties Union)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 139,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Public Intoxication'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 395,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'NAACP (National Association for the Advancement of Colored People)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 140,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Marijuana Possession'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 396,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'CopWatch'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 141,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Illegal Drugs Other Than Marijuana'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 397,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'NACOLE (National Association for Civilian Oversight of Law Enforcement)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 142,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefIsActive' => '0',
            'DefValue' => 'Race'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 398,
            'DefDatabase' => '1',
            'DefSubset' => 'Unresolved Charges Actions',
            'DefIsActive' => '0',
            'DefValue' => 'Full complaint to print or save'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 143,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Color'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 399,
            'DefDatabase' => '1',
            'DefSubset' => 'Unresolved Charges Actions',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Anonymous complaint data only'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 144,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Ethnicity'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 400,
            'DefDatabase' => '1',
            'DefSubset' => 'Unresolved Charges Actions',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Logout'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 145,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'National Origin'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 401,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '3',
            'DefValue' => 'Loitering/Trespassing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 146,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Religion'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 402,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '8',
            'DefValue' => 'Loitering/Trespassing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 147,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Age'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 403,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '5',
            'DefValue' => 'Loitering/Trespassing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 148,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Gender'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 404,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '3',
            'DefValue' => 'School'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 149,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Gender Identity'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 405,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Pedestrian Stop',
            'DefOrder' => '2',
            'DefValue' => 'Loitering/Trespassing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 150,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Sexual Orientation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 406,
            'DefDatabase' => '1',
            'DefSubset' => 'Reason for Vehicle Stop',
            'DefOrder' => '10',
            'DefValue' => 'Loitering/Trespassing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 151,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Family Responsibilities'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 152,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Disability'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 408,
            'DefDatabase' => '1',
            'DefSubset' => 'Chase Types',
            'DefValue' => 'On Foot'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 153,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '11',
            'DefIsActive' => '0',
            'DefValue' => 'Educational Level'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 409,
            'DefDatabase' => '1',
            'DefSubset' => 'Chase Types',
            'DefOrder' => '1',
            'DefValue' => 'In Vehicles'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 154,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '12',
            'DefIsActive' => '0',
            'DefValue' => 'Political Affiliations'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 410,
            'DefDatabase' => '1',
            'DefSubset' => 'Chase Types',
            'DefOrder' => '2',
            'DefValue' => 'Both'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 155,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '13',
            'DefIsActive' => '0',
            'DefValue' => 'Source of Income'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 411,
            'DefDatabase' => '1',
            'DefSubset' => 'Arrest Charges',
            'DefOrder' => '10',
            'DefValue' => 'Weapons Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 156,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '14',
            'DefIsActive' => '0',
            'DefValue' => 'Place'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 412,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '7',
            'DefValue' => 'Weapons Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 157,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '15',
            'DefIsActive' => '0',
            'DefValue' => 'Residence'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 413,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '12',
            'DefValue' => 'Weapons Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 158,
            'DefDatabase' => '1',
            'DefSubset' => 'Bias Type',
            'DefOrder' => '16',
            'DefIsActive' => '0',
            'DefValue' => 'Business'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 414,
            'DefDatabase' => '1',
            'DefSubset' => 'Chase Types',
            'DefOrder' => '3',
            'DefValue' => 'Not sure'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 159,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefIsActive' => '0',
            'DefValue' => 'Head'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 415,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Weapons',
            'DefOrder' => '1',
            'DefValue' => 'Gun'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 160,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Neck'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 416,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Weapons',
            'DefOrder' => '2',
            'DefValue' => 'Knife'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 161,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Torso'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 417,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Weapons',
            'DefOrder' => '3',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 162,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Hand'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 163,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Elbow'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 164,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Arm'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 165,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Foot'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 166,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Knee'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 167,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Leg'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 168,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Crotch'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 169,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Part',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Unknown'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 170,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Types',
            'DefIsActive' => '0',
            'DefValue' => 'Slim/Slender'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 171,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Medium/Average'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 172,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Athletic/Muscular'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 173,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Large/Fat'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 174,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefIsActive' => '0',
            'DefValue' => 'Speeding'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 175,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Vehicle Defect'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 176,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Records Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 177,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Illegal Turn or Lane Change'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 178,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Seatbelt or Cell Phone Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 179,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Stop Sign/Light Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 180,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Disorderly Conduct'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 181,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Disturbing the Peace'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 182,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Failure to Obey/Comply'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 183,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Public Intoxication'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 184,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges',
            'DefOrder' => '11',
            'DefIsActive' => '0',
            'DefValue' => 'Marijuana Possession'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 185,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefIsActive' => '0',
            'DefValue' => 'Walking Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 441,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'tree-1-core-record-singular',
            'DefDescription' => 'Complaint'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 186,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Disorderly Conduct'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 442,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'tree-1-core-record-plural',
            'DefDescription' => 'Complaints'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 187,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Disturbing the Peace'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 188,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Failure to Obey/Comply'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 444,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'parent-company',
            'DefDescription' => 'Flex Your Rights'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 189,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Public Intoxication'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 445,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'parent-website',
            'DefDescription' => 'http://FlexYourRights.org'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 190,
            'DefDatabase' => '1',
            'DefSubset' => 'Citation Charges Pedestrian',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Marijuana Possession'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 446,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'tree-1-example',
            'DefDescription' => '31'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 191,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Oversight Models',
            'DefIsActive' => '0',
            'DefValue' => 'Investigative'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 447,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'login-instruct',
            'DefDescription' => '<p>Here you can finish, review, or update your complaint. Volunteers also login here.</p>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 192,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Oversight Models',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Review'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 193,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Oversight Models',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Audit'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 194,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefIsActive' => '0',
            'DefValue' => 'Incomplete',
            'DefDescription' => 'Not published. Complainant did not complete the survey.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 195,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Hold',
            'DefDescription' => 'Not published. Used to denote complaints which have been reviewed, but need more staff to help determine the next steps.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 196,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'New',
            'DefDescription' => 'Not published. Complainants completed the survey process, but no staff have reviewed this complaint yet.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 197,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Reviewed',
            'DefDescription' => 'Not Published. This complaint has been reviewed by [at least] one staff member, and it is on-track for processing. The next step is likely department research.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 453,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-text',
            'DefOrder' => '1',
            'DefDescription' => '#333'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 198,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Pending Attorney',
            'DefDescription' => 'Not published. This Complaint has NOT been submitted to any Investigative Agencies, because it has been determined to be a good prospect for an Attorney.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 454,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'font-main',
            'DefDescription' => 'Roboto,Helvetica,Arial,sans-serif'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 199,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Attorney\'d',
            'DefDescription' => 'Not published. This complainant has an attorney.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 455,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-grey',
            'DefOrder' => '10',
            'DefDescription' => '#888'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 200,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'Submitted to Oversight',
            'DefDescription' => 'Published. Either staff filed complaint via email OR user confirms they officially filed complaint.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 456,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-bg',
            'DefOrder' => '6',
            'DefDescription' => '#FFF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 201,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Received by Oversight',
            'DefDescription' => 'Published. Confirmed by Staff or Complainant.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 457,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-nav-bg',
            'DefOrder' => '5',
            'DefDescription' => '#000'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 202,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'OK to Submit to Oversight',
            'DefDescription' => 'Not Published. Waiting for user to officially file complaint.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 458,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-nav-text',
            'DefOrder' => '4',
            'DefDescription' => '#EDF8FF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 203,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Declined To Investigate (Closed)',
            'DefDescription' => 'Published. Department acknowledges complaint, but did not initiate any investigation process.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 459,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-info-on',
            'DefOrder' => '15',
            'DefDescription' => '#63C6FF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 204,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '11',
            'DefIsActive' => '0',
            'DefValue' => 'Investigated (Closed)',
            'DefDescription' => 'Published. Investigative agency completed an investigation into this police complaint. Ideally, the official report can be uploaded alongside the public complaint.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 460,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-info-off',
            'DefOrder' => '16',
            'DefDescription' => '#2AABD2'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 205,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '12',
            'DefIsActive' => '0',
            'DefValue' => 'Closed',
            'DefDescription' => 'Not published. Should only be used for Complaint Types: [Fantastical,] Abuse, Spam, Test, Not Sure.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 461,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-warn-on',
            'DefOrder' => '17',
            'DefDescription' => '#F0AD4E'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 206,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefIsActive' => '0',
            'DefValue' => 'Incomplete',
            'DefDescription' => 'Not published.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 462,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-warn-off',
            'DefOrder' => '18',
            'DefDescription' => '#EB9316'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 207,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Hold',
            'DefDescription' => 'Not published.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 463,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-line-hr',
            'DefOrder' => '19',
            'DefDescription' => '#999'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 208,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'New',
            'DefDescription' => 'Not published.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 464,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-field-bg',
            'DefOrder' => '21',
            'DefDescription' => '#FFF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 209,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Reviewed',
            'DefDescription' => 'Published.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 465,
            'DefDatabase' => '1',
            'DefSet' => 'Style CSS',
            'DefSubset' => 'main',
            'DefDescription' => '/* cairo-regular - latin-ext_arabic_latin */
@font-face {
  font-family: \'Cairo\';
  font-style: normal;
  font-weight: 400;
  src: url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-regular.eot\'); /* IE9 Compat Modes */
  src: local(\'Cairo\'), local(\'Cairo-Regular\'),
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-regular.eot?#iefix\') format(\'embedded-opentype\'), /* IE6-IE8 */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-regular.woff2\') format(\'woff2\'), /* Super Modern Browsers */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-regular.woff\') format(\'woff\'), /* Modern Browsers */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-regular.ttf\') format(\'truetype\'), /* Safari, Android, iOS */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-regular.svg#Cairo\') format(\'svg\'); /* Legacy iOS */
}

/* cairo-700 - latin-ext_arabic_latin */
@font-face {
  font-family: \'Cairo Bold\';
  font-style: normal;
  font-weight: 700;
  src: url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-700.eot\'); /* IE9 Compat Modes */
  src: local(\'Cairo Bold\'), local(\'Cairo-Bold\'),
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-700.eot?#iefix\') format(\'embedded-opentype\'), /* IE6-IE8 */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-700.woff2\') format(\'woff2\'), /* Super Modern Browsers */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-700.woff\') format(\'woff\'), /* Modern Browsers */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-700.ttf\') format(\'truetype\'), /* Safari, Android, iOS */
       url(\'/openpolice/font/cairo-v6-latin-ext_arabic_latin-700.svg#Cairo\') format(\'svg\'); /* Legacy iOS */
}


input, textarea, select, .nFld input, .nFld textarea, .nFld select, .nFld input.form-control, .nFld input.form-control-lg, .nFld input.form-control.form-control-lg, .nFld textarea.form-control, .nFld textarea.form-control-lg, .nFld textarea.form-control.form-control-lg, .nFld select.form-control, .nFld select.form-control-lg, .nFld select.form-control.form-control-lg { font-family: Georgia, serif; }
input.btn, input.btn.btn-primary, input.btn.btn-secondary, input.btn.btn-primary.btn-xl {
    font-family: \'Cairo\', sans-serif; 
}


b {
    font-family: \'Cairo Bold\', \'Cairo\', sans-serif; 
    font-weight: 700;
}

body, p, div, table tr td, table tr th, input, textarea, select {
    font-family: \'Cairo\', sans-serif;
}
b, h1, h2, h3, h4, h5, h6 {
    font-family: \'Cairo Bold\', \'Cairo\', sans-serif; 
}
a:link, a:visited, a:active, a:hover {
    color: #416CBD;
}

.slCard {
    box-shadow: 0px 1px 4px #CCC;
}

#slLogoImg { height: 38px; margin-top: 0px; }
#slLogoImgSm { height: 32px; margin-top: 4px; }

.btn-primary-simple, .btn-primary, a.btn-primary:link, a.btn-primary:active, a.btn-primary:visited, a.btn-primary:hover,
a.btn-primary.btn-xl:link, a.btn-primary.btn-xl:active, a.btn-primary.btn-xl:visited, a.btn-primary.btn-xl:hover,
a.btn-primary.btn-lg:link, a.btn-primary.btn-lg:active, a.btn-primary.btn-lg:visited, a.btn-primary.btn-lg:hover,
a.btn-primary.btn-sm:link, a.btn-primary.btn-sm:active, a.btn-primary.btn-sm:visited, a.btn-primary.btn-sm:hover,
input[type="submit"].btn-primary, input[type="submit"].btn-primary.btn-xl, input[type="submit"].btn-primary.btn-lg, input[type="submit"].btn-primary.btn-sm, a.btn-primary:not([href]):not([tabindex]) {
    color: #FFF;
    background-image: none;
    background: #2b3493;
    border: 1px #FFF solid;
    text-shadow: none;
    font-family: \'Cairo\', sans-serif;
}
a.btn-primary:hover, a.btn-primary.btn-xl:hover, a.btn-primary.btn-lg:hover, a.btn-primary.btn-sm:hover,
input[type="submit"].btn-primary:hover, input[type="submit"].btn-primary.btn-xl:hover, input[type="submit"].btn-primary.btn-lg:hover, input[type="submit"].btn-primary.btn-sm:hover {
    color: #2b3493;
    background: #FFF;
    border: 1px #2b3493 solid;
}

.btn-info-simple, .btn-info, a.btn-info:link, a.btn-info:active, a.btn-info:visited, a.btn-info:hover,
a.btn-info.btn-xl:link, a.btn-info.btn-xl:active, a.btn-info.btn-xl:visited, a.btn-info.btn-xl:hover,
a.btn-info.btn-lg:link, a.btn-info.btn-lg:active, a.btn-info.btn-lg:visited, a.btn-info.btn-lg:hover,
a.btn-info.btn-sm:link, a.btn-info.btn-sm:active, a.btn-info.btn-sm:visited, a.btn-info.btn-sm:hover,
input[type="submit"].btn-info, input[type="submit"].btn-info.btn-xl, input[type="submit"].btn-info.btn-lg, input[type="submit"].btn-info.btn-sm {
    color: #333;
    background-image: none;
    background: #EDF8FF;
    border: 1px #333 solid;
    text-shadow: none;
    font-family: \'Cairo\', sans-serif;
}
a.btn-info:hover, a.btn-info.btn-xl:hover, a.btn-info.btn-lg:hover, a.btn-info.btn-sm:hover,
input[type="submit"].btn-info:hover, input[type="submit"].btn-info.btn-xl:hover, input[type="submit"].btn-info.btn-lg:hover, input[type="submit"].btn-info.btn-sm:hover {
    color: #EDF8FF;
    background: #333;
    border: 1px #EDF8FF solid;
}

.btn-secondary, a.btn-secondary:link, a.btn-secondary:active, a.btn-secondary:visited, a.btn-secondary:hover,
a.btn-secondary.btn-xl:link, a.btn-secondary.btn-xl:active, a.btn-secondary.btn-xl:visited, a.btn-secondary.btn-xl:hover,
a.btn-secondary.btn-lg:link, a.btn-secondary.btn-lg:active, a.btn-secondary.btn-lg:visited, a.btn-secondary.btn-lg:hover,
a.btn-secondary.btn-sm:link, a.btn-secondary.btn-sm:active, a.btn-secondary.btn-sm:visited, a.btn-secondary.btn-sm:hover,
input[type="submit"].btn-secondary, input[type="submit"].btn-secondary.btn-xl, input[type="submit"].btn-secondary.btn-lg, input[type="submit"].btn-secondary.btn-sm {
    color: #2B3493;
    background-image: none;
    background: #EDF8FF;
    border: 1px #2B3493 solid;
    text-shadow: none;
    font-family: \'Cairo\', sans-serif;
}
a.btn-secondary:hover, a.btn-secondary.btn-xl:hover, a.btn-secondary.btn-lg:hover, a.btn-secondary.btn-sm:hover,
input[type="submit"].btn-secondary:hover, input[type="submit"].btn-secondary.btn-xl:hover, input[type="submit"].btn-secondary.btn-lg:hover, input[type="submit"].btn-secondary.btn-sm:hover {
    color: #EDF8FF;
    background: #2B3493;
    border: 1px #EDF8FF solid;
}

.btn-danger-simple, .btn-danger, a.btn-danger:link, a.btn-danger:active, a.btn-danger:visited, a.btn-danger:hover,
a.btn-danger.btn-xl:link, a.btn-danger.btn-xl:active, a.btn-danger.btn-xl:visited, a.btn-danger.btn-xl:hover,
a.btn-danger.btn-lg:link, a.btn-danger.btn-lg:active, a.btn-danger.btn-lg:visited, a.btn-danger.btn-lg:hover,
a.btn-danger.btn-sm:link, a.btn-danger.btn-sm:active, a.btn-danger.btn-sm:visited, a.btn-danger.btn-sm:hover,
input[type="submit"].btn-danger, input[type="submit"].btn-danger.btn-xl, input[type="submit"].btn-danger.btn-lg, input[type="submit"].btn-danger.btn-sm {
    color: #FFF;
    background-image: none;
    background: #EC2327;
    border: 1px #FFF solid;
    text-shadow: none;
    font-family: \'Cairo\', sans-serif;
}
a.btn-danger:hover, a.btn-danger.btn-xl:hover, a.btn-danger.btn-lg:hover, a.btn-danger.btn-sm:hover,
input[type="submit"].btn-danger:hover, input[type="submit"].btn-danger.btn-xl:hover, input[type="submit"].btn-danger.btn-lg:hover, input[type="submit"].btn-danger.btn-sm:hover {
    color: #EC2327;
    background: #FFF;
    border: 1px #EC2327 solid;
}


input.fingerTxt, input.form-control.fingerTxt, 
textarea.fingerTxt, textarea.form-control.fingerTxt, 
select.fingerTxt, select.form-control.fingerTxt {
    color: #000;
}
label.finger, label.fingerAct, .finger:hover, input.finger:hover+label {
    color: #000;
}
label.fingerAct, label.fingerAct:active, input.fingerAct:active+label, 
label.fingerAct:hover, input.fingerAct:hover+label, label.finger:active, input.finger:active+label {
    color: #2b3493;
}
label.finger, label.fingerAct {
    border: 1px #DDD solid;
}
.finger:hover, input.finger:hover+label,
label.fingerAct, label.fingerAct:active, input.fingerAct:active+label, 
label.fingerAct:hover, input.fingerAct:hover+label, label.finger:active, input.finger:active+label {
    border: 1px #2b3493 solid;
}

.allegation, h1.allegation, h2.allegation, h3.allegation, h4.allegation, 
a.allegation:link, a.allegation:visited, a.allegation:active, a.allegation:hover {
    color: #ec2327;
    font-size: bold;
}

.goldLevel {
    color: #fccb4f;
    font-weight: bold;
    text-shadow: 1px 1px 0px #000;
}

.volunChecklistWrap {
    padding: 20px;
    border: 1px #63c6ff dotted;
    -moz-border-radius: 20px; border-radius: 20px;
    font-size: 16pt;
    line-height: 28px;
}
ol.volunChecklist {
    
}
ol.volunChecklist li {
    position: relative;
    font-size: 16pt;
    line-height: 28px;
    padding-bottom: 25px;
}
ol.volunChecklist li b, .volunChecklistWrap b {
    font-size: 110%;
}
ol.volunChecklist input {
    position: absolute;
    top: 3px;
    left: -40px;
}


table.complaintTbl {
    width: 100%;
}
table.complaintTbl tr th {
    font-weight: bold;
    border-bottom: 1px #63c6ff solid;
}
table.complaintTbl tr th, table.complaintTbl tr td {
    padding: 5px;
    text-align: left;
    vertical-align: top;
}
table.complaintTbl tr td.botBrd {
    border-bottom: 1px #bbe6ff dotted;
}


.oversightWaysBlock {
-moz-border-radius: 10px; border-radius: 10px;
border: 1px #63C6FF solid;
padding: 10px;
}

.navbar, .navbar-inverse { border-bottom: 0px none; }

#footerLinks {
width: 100%;
max-width: 100%;
padding: 50px 15px;
background: #000;
color: #EEE;
}
#footerLinks a:link, #footerLinks a:active, #footerLinks a:visited, #footerLinks a:hover {
color: #FFF;
}
#footerSocialCol { text-align: left; }
@media screen and (max-width: 992px) {
#footerSocialCol { margin-top: 10px; margin-left: -5px; }
}

@media screen and (max-width: 480px) {
#mce-EMAIL { width: 100%; }
}

.deptRgtCol { width: 116px; }
.scoreRowOn, .scoreRowOff { padding: 10px; }
.scoreRowOn { color: #2B3493; }
.scoreRowOff { color: #CCC; }

#dontWorry { margin-top: 25px; }'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 210,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Submitted to Oversight',
            'DefDescription' => 'Published.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 466,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-form-text',
            'DefOrder' => '3',
            'DefDescription' => '#333'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 211,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Received by Oversight',
            'DefDescription' => 'Published.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 467,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-emojis',
            'DefValue' => 'Flag;Flags',
            'DefDescription' => '<i class="fa fa-flag-o" aria-hidden="true"></i>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 212,
            'DefDatabase' => '1',
            'DefSubset' => 'Compliment Status',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Closed'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 468,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-emojis',
            'DefOrder' => '1',
            'DefValue' => 'Like;Likes',
            'DefDescription' => '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 213,
            'DefDatabase' => '1',
            'DefSubset' => 'Contraband Types',
            'DefIsActive' => '0',
            'DefValue' => 'Marijuana'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 214,
            'DefDatabase' => '1',
            'DefSubset' => 'Contraband Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Other Illegal Drugs (i.e. cocaine, MDMA, etc.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 215,
            'DefDatabase' => '1',
            'DefSubset' => 'Contraband Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Illegal Gun'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 216,
            'DefDatabase' => '1',
            'DefSubset' => 'Contraband Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Illegal Knife'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 217,
            'DefDatabase' => '1',
            'DefSubset' => 'Contraband Types',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Stolen Goods'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 473,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-footer',
            'DefDescription' => '<br /><style> #topNavSearchBtn { display: none; } </style>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 218,
            'DefDatabase' => '1',
            'DefSubset' => 'Contraband Types',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 219,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefIsActive' => '0',
            'DefValue' => 'Attorney'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 220,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Academic'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 221,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Journalist'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 222,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Researcher'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 223,
            'DefDatabase' => '1',
            'DefSubset' => 'Evidence Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Photograph'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 479,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'log-css-reload',
            'DefDescription' => '1572195585'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 224,
            'DefDatabase' => '1',
            'DefSubset' => 'Evidence Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Sketch/Diagram'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 225,
            'DefDatabase' => '1',
            'DefSubset' => 'Evidence Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Document'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 481,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'tree-5-upload-types',
            'DefDescription' => 'Evidence Types'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 226,
            'DefDatabase' => '1',
            'DefSubset' => 'Evidence Types',
            'DefIsActive' => '0',
            'DefValue' => 'Video'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 482,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-5-footer',
            'DefDescription' => '<br /><style> #topNavSearchBtn { display: none; } </style>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 227,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefIsActive' => '0',
            'DefValue' => 'Grabbing or Control Hold',
            'DefDescription' => 'An officer\'s use of his/her limbs, torso or body weight, to move or restrain a person or to constrict a person\'s movements.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 483,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-link',
            'DefOrder' => '2',
            'DefDescription' => '#416CBD'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 228,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Body Weapons (Punch, Kick, Elbow, etc)',
            'DefDescription' => 'An officer\'s use of his/her limbs in a manner similar to an impact weapon. Body Weapons include Fist (Closed Hand), Slap (Open Hand), Kick (Feet), Knee, Elbow, Head.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 484,
            'DefDatabase' => '1',
            'DefSet' => 'Style CSS',
            'DefSubset' => 'email',
            'DefDescription' => '.oversightWaysBlock {
-moz-border-radius: 10px; border-radius: 10px;
border: 1px #63C6FF solid;
padding: 10px;
}'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 229,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Takedown',
            'DefDescription' => 'An officer\'s use of his/her limbs, torso or body weight to force a person against an immovable object (such as a car or a wall) or to force a person to the ground.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 230,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Baton Strike'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 231,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Taser'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 232,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '6',
            'DefIsActive' => '0',
            'DefValue' => 'Gun',
            'DefDescription' => 'i.e. Firearm'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 233,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '7',
            'DefIsActive' => '0',
            'DefValue' => 'Mace or Pepper Spray',
            'DefDescription' => 'i.e. Chemical Agent'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 234,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '8',
            'DefIsActive' => '0',
            'DefValue' => 'K9 (Dog) Bite'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 235,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '9',
            'DefIsActive' => '0',
            'DefValue' => 'Vehicle'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 236,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '10',
            'DefIsActive' => '0',
            'DefValue' => 'Other',
            'DefDescription' => 'e.g. Rough Ride, Car Impact, Flashlight Strike, Lifting Up Cuffs...'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 237,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Body Weapons',
            'DefIsActive' => '0',
            'DefValue' => 'Fist (Closed Hand)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 238,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Body Weapons',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Slap (Open Hand)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 239,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Body Weapons',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Kick (Feet)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 240,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Body Weapons',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Knee'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 241,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Body Weapons',
            'DefOrder' => '4',
            'DefIsActive' => '0',
            'DefValue' => 'Elbow'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 497,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'css-extra-files'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 242,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Body Weapons',
            'DefOrder' => '5',
            'DefIsActive' => '0',
            'DefValue' => 'Head'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 498,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-uploads-public',
            'DefDescription' => '1'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 243,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Control Hold',
            'DefIsActive' => '0',
            'DefValue' => 'Hands/Arms'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 499,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-5-uploads-public',
            'DefDescription' => '1'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 244,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Control Hold',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Body Weight'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 500,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'spinner-code',
            'DefDescription' => '<img src="/openpolice/logo-ico-loading-anim.gif" border=0 class="round10 mT20 mB20" style="width: 75px;" >'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 245,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Control Hold',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Knees'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 246,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Control Hold',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Feet/Legs'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 247,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Takedown',
            'DefIsActive' => '0',
            'DefValue' => 'Hands'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 248,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Takedown',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Tackle'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 249,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type - Takedown',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Leg Sweep'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 250,
            'DefDatabase' => '1',
            'DefSubset' => 'Gun Ammo Types',
            'DefIsActive' => '0',
            'DefValue' => 'Lethal Ammo'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 251,
            'DefDatabase' => '1',
            'DefSubset' => 'Gun Ammo Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Less-Lethal Ammo (e.g. rubber bullets, bean bag rounds)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 252,
            'DefDatabase' => '1',
            'DefSubset' => 'Incident Event Types',
            'DefIsActive' => '0',
            'DefValue' => 'Stop'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 253,
            'DefDatabase' => '1',
            'DefSubset' => 'Incident Event Types',
            'DefOrder' => '1',
            'DefIsActive' => '0',
            'DefValue' => 'Search'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 254,
            'DefDatabase' => '1',
            'DefSubset' => 'Incident Event Types',
            'DefOrder' => '2',
            'DefIsActive' => '0',
            'DefValue' => 'Force'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 255,
            'DefDatabase' => '1',
            'DefSubset' => 'Incident Event Types',
            'DefOrder' => '3',
            'DefIsActive' => '0',
            'DefValue' => 'Arrest'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 448,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Volunteer Checklist',
            'DefDescription' => '<br>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 449,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Phone Script: Department',
            'DefDescription' => 'Hi, my name is [Your First Name], and I\'m doing some research for an online directory of police department information. Do you mind if I ask you some quick questions? This will just take about two minutes.<br /><br />
<span class="gry9"><i>Note: If they ask what this is for, you can say something like:</i></span>
<div class="pL10 pB20 gry6">I\'m volunteering with a non-profit group called OpenPolice.org. They provide information about how people can submit police complaints. We\'re making these calls for all 18,000 police departments in the United States, so I really appreciate your help!</div>
<ul>
<li class="pB20"><span class="gry9"><i>If needed:</i></span> What\'s the main address for your department?</li>
<li class="pB20"><span class="gry9"><i>If needed:</i></span> Do you have a website and email address? Are you on Facebook?</li>
<li class="pB20"><span class="gry9"><i>If needed:</i></span> How many people work in your department?</li>
<li class="pB20"><span class="gry9"><i>If needed:</i></span> Do you know how many people live in your department\'s jurisdiction?</li>
<li class="pB20">What\'s the phone number for your internal affairs office?</li>
</ul>
I really appreciate your time. Thanks so much for your help!'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 450,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Phone Script: Internal Affairs',
            'DefDescription' => '<p>Hi, my name is [Your First Name], and I\'m doing some research for an online directory of internal affairs departments. Do you mind if I ask you some quick questions? This will just take about five minutes.</p>
<p class="slGrey"><i>Note: If they ask what this is for, you can say something like:</i></p>
<p class="pL10 pB20 slGrey">I\'m volunteering with a non-profit group called OpenPolice.org. They provide information about how people can submit police complaints. We\'re making these calls for all 18,000 police departments in the United States, so I really appreciate your help!</p>
<table class="table" border=0 >
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA1" class="mR10" type="checkbox"> 1)</nobr></td>
    <td class="w100"><label for="phoneIA1">
        What\'s the mailing address for the internal affairs office?
    </label></td></tr>
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA2" class="mR10" type="checkbox"> 2)</nobr></td>
    <td class="w100"><label for="phoneIA2">
        What\'s the best direct email address to reach your office?
    </label></td></tr>
</table>
<div class="mB20">
    I have some questions about how people can submit complaints about an officer.
</div>
<table class="table" border=0 >
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA3" class="mR10" type="checkbox"> 3)</nobr></td>
    <td class="w100"><label for="phoneIA3">
        Does the department require complaints to be submitted on an official form <u>in order to be investigated</u>?
    </label></td></tr>
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA4" class="mR10" type="checkbox"> 4)</nobr></td>
    <td class="w100"><label for="phoneIA4">
        <span class="slGrey"><i>If you didn\'t find it yet:</i></span> Is a copy of the complaint form available on your website?
    </label></td></tr>
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA5" class="mR10" type="checkbox"> 5)</nobr></td>
    <td class="w100"><label for="phoneIA5">
        Do people have to submit complaints in person? Or can they also be mailed in?
    </label></td></tr>
</table>
<p class="mB10 slGrey"><i>If an official form is required, <nobr>skip to #8:</nobr></i></p>
<table class="table" border=0 >
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA6" class="mR10" type="checkbox"> 6)</nobr></td>
    <td class="w100"><label for="phoneIA6">
        Can people provide a verbal complaint over the phone?
    </label></td></tr>
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA7" class="mR10" type="checkbox"> 7)</nobr></td>
    <td class="w100"><label for="phoneIA7">
        Is there any way to submit a complaint via email or online?
    </label></td></tr>
    <tr><td class="slGrey"><nobr><input name="phoneIA[]" id="phoneIA8" class="mR10" type="checkbox"> 8)</nobr></td>
    <td class="w100"><label for="phoneIA8">
        How many days after an incident can a civilian submit a complaint for it to be investigated?
    </label></td></tr>
</table>
<p>I really appreciate your time. Thanks so much for your help!</p>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 419,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefValue' => 'Valor',
            'DefDescription' => 'The officer showed extraordinary courage in the face of danger.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 420,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '1',
            'DefValue' => 'Lifesaving',
            'DefDescription' => 'The officer applied medical aid exceeding the normal call of duty.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 421,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '2',
            'DefValue' => 'De-escalation',
            'DefDescription' => 'The officer skillfully calmed down a tense situation, using minimal or no force.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 422,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '3',
            'DefValue' => 'Professionalism',
            'DefDescription' => 'The officer behaved in a courteous, respectful, and straightforward manner.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 423,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '4',
            'DefValue' => 'Fairness',
            'DefDescription' => 'The officer’s use of power was reasonable, appropriate, and free from bias.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 424,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '5',
            'DefValue' => 'Constitutional Policing',
            'DefDescription' => 'The officer’s words and actions showed respect for the Bill of Rights.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 425,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '6',
            'DefValue' => 'Compassion',
            'DefDescription' => 'The officer displayed empathy and generosity beyond the call of duty.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 426,
            'DefDatabase' => '1',
            'DefSubset' => 'Commendation Type',
            'DefOrder' => '7',
            'DefValue' => 'Community Service',
            'DefDescription' => 'The officer engaged the community to build bonds of trust.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 427,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Types',
            'DefOrder' => '5',
            'DefValue' => 'Private Security Company'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 428,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefValue' => 'Flex Your Rights'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 429,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '5',
            'DefValue' => 'A Friend'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 430,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '7',
            'DefValue' => 'Facebook'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 431,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '8',
            'DefValue' => 'Twitter'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 432,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '6',
            'DefValue' => 'News / Media Coverage'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 433,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '9',
            'DefValue' => 'A Police Department'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 434,
            'DefDatabase' => '1',
            'DefSubset' => 'How Did You Hear',
            'DefOrder' => '10',
            'DefValue' => 'A Police Investigative Agency'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 435,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges Officers',
            'DefValue' => 'Under 25'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 436,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges Officers',
            'DefOrder' => '1',
            'DefValue' => '25-34'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 437,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges Officers',
            'DefOrder' => '2',
            'DefValue' => '35-44'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 438,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges Officers',
            'DefOrder' => '3',
            'DefValue' => '45-54'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 439,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges Officers',
            'DefOrder' => '4',
            'DefValue' => '55-64'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 440,
            'DefDatabase' => '1',
            'DefSubset' => 'Age Ranges Officers',
            'DefOrder' => '5',
            'DefValue' => 'Over 64'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 443,
            'DefDatabase' => '1',
            'DefSubset' => 'Force Type',
            'DefOrder' => '1',
            'DefValue' => 'Pushing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 452,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Terms & Conditions',
            'DefDescription' => '...<br>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 469,
            'DefDatabase' => '1',
            'DefSubset' => 'Races',
            'DefOrder' => '3',
            'DefValue' => 'Middle Eastern'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 470,
            'DefDatabase' => '1',
            'DefSubset' => 'Body Types',
            'DefOrder' => '4',
            'DefValue' => 'Not sure'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 471,
            'DefDatabase' => '1',
            'DefSubset' => 'Injury Types',
            'DefOrder' => '20',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 474,
            'DefDatabase' => '1',
            'DefSubset' => 'Contact Reasons',
            'DefValue' => 'General Feedback'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 475,
            'DefDatabase' => '1',
            'DefSubset' => 'Contact Reasons',
            'DefOrder' => '1',
            'DefValue' => 'Website Problems'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 476,
            'DefDatabase' => '1',
            'DefSubset' => 'Contact Reasons',
            'DefOrder' => '2',
            'DefValue' => 'Networking Opportunities'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 477,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Footer',
            'DefIsActive' => '3',
            'DefDescription' => '<style>
#hidivBtnSearchOpts, #hidivSearchOpts { display: none; }
</style>
<div id="footerLinks" style="text-align: center;"><div class="container"><div class="row">
    <div class="col-md-4 taL">
      <a href="/" class="slBlueDark"><img src="/openpolice/uploads/Flex_OpenPolice.org-sm.jpg" border=0 style="width: 100%; max-width: 320px; margin-left: -5px;"></a>
            <table border=0 class="w100 mT10" ><tr><td class="taC" style="width: 70px;">
<a href="https://FlexYourRights.org" target="_blank"><img src="/openpolice/uploads/flex-arm-white-sm.png" border=0 style="height: 50px; margin: 5px 10px 0px 0px;"></a>
</td><td class="taL vaT">
<div class="slGrey mT10 mBn5">a project of</div>
<a href="https://FlexYourRights.org" target="_blank"><a href="https://FlexYourRights.org" class="slBlueDark" style="font-size: 26px;" target="_blank">Flex Your Rights</a></td></tr></table>
<br><br>
    </div>
    <div class="col-md-3 taL">
      <a href="/">Home</a><br>
<!--      <a href="/search">Search Complaints</a><br> -->
      <a href="/about">About Us</a><br>
      <a href="/frequently-asked-questions">FAQs</a><br>
      <a href="/department-accessibility">Department Scores</a><br>
    </div>
    <div class="col-md-3 taL">
      <a href="/contact">Contact Us</a><br>
      <a href="/donate" target="_blank">Donate</a><br>
      <a href="/privacy-policy">Terms, Policies, & Rules</a><br>
      <a href="/site-map">Site Map</a><br>
<br><br>
    </div>
    <div class="col-md-2" id="footerSocialCol">
        <div class="footerSocial" style="margin: -5px 0px 0px 0px;">
          <a href="https://www.facebook.com/FlexYourRights/" target="_blank"><img src="/survloop/uploads/facebook-logo.png" border="0"></a>
          <a href="https://twitter.com/opencomplaints" target="_blank"><img src="/survloop/uploads/twitter-logo.png" border="0"></a>
        </div>
    <a href="https://github.com/flexyourrights/openpolice" target="_blank"><div class="disBlo mR5 mL5 mBn10" style="font-size: 42px;"><i class="fa fa-github" aria-hidden="true"></i></div>View our code on GitHub</a>
      <div><a href="http://creativecommons.org/licenses/by-sa/3.0/" target="_blank"><span class="slGrey fPerc80"><nobr>Creative Commons</nobr> <nobr><i class="fa fa-creative-commons mR3" aria-hidden="true"></i> 2015-2019</nobr>
</span></a></div>
    </div>
</div></div></div>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 478,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Newsletter Sign Up',
            'DefIsActive' => '3',
            'DefDescription' => '<form action="https://flexyourrights.us5.list-manage.com/subscribe/post?u=6b424f1b6d7c45a5906cd7579&id=31f97a79b7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank"> 
    <div style="position: absolute; left: -5000px;" aria-hidden="true">
      <input name="b_6b424f1b6d7c45a5906cd7579_31f97a79b7" tabindex="-1" value="" type="text">
    </div>
    <p><input name="EMAIL" class="form-control required email" id="mce-EMAIL" placeholder="Your email address" type="email" style="width: 200px;"></p>
    <p><input value="Sign me up!" name="subscribe" id="mc-embedded-subscribe" class="btn btn-primary btn-lg" type="submit"></p>
</form>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 480,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '11',
            'DefValue' => 'Sexual Harassment',
            'DefDescription' => 'This includes the use of unwanted sexual advances or obscene remarks.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 485,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefValue' => 'My Profile',
            'DefDescription' => '/my-profile'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 487,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '2',
            'DefValue' => 'Dept. Scores',
            'DefDescription' => '/department-accessibility'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 488,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '3',
            'DefValue' => 'FAQs',
            'DefDescription' => '/frequently-asked-questions'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 489,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '4',
            'DefValue' => 'Logout',
            'DefDescription' => '/logout'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 490,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '5'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 491,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '6'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 492,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '7',
            'DefValue' => 'Police Station'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 493,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '8',
            'DefValue' => 'Telephone Call'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 494,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '9',
            'DefValue' => 'Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 495,
            'DefDatabase' => '1',
            'DefSubset' => 'Scene Type',
            'DefOrder' => '2',
            'DefValue' => 'College Dorm'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 496,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '6',
            'DefValue' => 'Wrongful Property Damage',
            'DefDescription' => 'Property damage which violated the protections provided by the 4th Amendment of the United States Constitution.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 501,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'cust-package',
            'DefDescription' => 'flexyourrights/openpolice'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 502,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'twitter',
            'DefDescription' => '@OpenPoliceApp'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 503,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'header-code',
            'DefDescription' => '<!--- --->'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 504,
            'DefDatabase' => '1',
            'DefSet' => 'System Checks',
            'DefSubset' => 'system-updates',
            'DefDescription' => '2018-03-27;;OPC-2018-02-08'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 505,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'has-volunteers',
            'DefDescription' => '1'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 506,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Status',
            'DefValue' => 'Active Department'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 507,
            'DefDatabase' => '1',
            'DefSubset' => 'Department Status',
            'DefOrder' => '1',
            'DefValue' => 'Inactive Department'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 508,
            'DefDatabase' => '1',
            'DefSet' => 'Style Settings',
            'DefSubset' => 'color-main-faintr',
            'DefDescription' => '#FCFEFF'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 509,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'has-partners',
            'DefDescription' => '1'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 511,
            'DefDatabase' => '1',
            'DefSet' => 'User Roles',
            'DefSubset' => 'partner',
            'DefOrder' => '3',
            'DefValue' => 'Partner Member',
            'DefDescription' => 'Basic permission to pages and tools just for partners'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 512,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'has-canada',
            'DefDescription' => '0'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 516,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefOrder' => '4',
            'DefValue' => 'Police Department'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 517,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefOrder' => '5',
            'DefValue' => 'Investigative Agency'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 518,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefValue' => 'Other Witnesses'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 519,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '1',
            'DefValue' => 'Unresolved Charges'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 520,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '2',
            'DefValue' => 'Arrested'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 521,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '3',
            'DefValue' => 'Use of Force'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 522,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '4',
            'DefValue' => 'Injury'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 523,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '5',
            'DefValue' => 'Fatality'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 524,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '6',
            'DefValue' => 'Has Evidence'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 525,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '7',
            'DefValue' => 'Video of Incident'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 526,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '8',
            'DefValue' => 'Allegation: Unreasonable Force'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 527,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '9',
            'DefValue' => 'Allegation: Wrongful Arrest'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 528,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '10',
            'DefValue' => 'Allegation: Wrongful Detention'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 529,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '11',
            'DefValue' => 'Allegation: Wrongful Entry'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 530,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '12',
            'DefValue' => 'Allegation: Wrongful Search'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 531,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '14',
            'DefValue' => 'Allegation: Wrongful Property Seizure'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 532,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '15',
            'DefValue' => 'Allegation: Bias-Based Policing'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 533,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '16',
            'DefValue' => 'Allegation: Excessive Arrest Charges'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 534,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '17',
            'DefValue' => 'Allegation: Excessive Citation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 535,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '18',
            'DefValue' => 'Allegation: Intimidating Display Of Weapon'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 536,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '19',
            'DefValue' => 'Allegation: Sexual Harassment'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 537,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '20',
            'DefValue' => 'Allegation: Sexual Assault'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 538,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '21',
            'DefValue' => 'Allegation: Policy or Procedure Violation'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 539,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '22',
            'DefValue' => 'Allegation: Conduct Unbecoming an Officer'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 540,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '23',
            'DefValue' => 'Allegation: Discourtesy'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 541,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '24',
            'DefValue' => 'Allegation: Officer Refused To Provide ID'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 542,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '25',
            'DefValue' => 'Allegation: Miranda Rights'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 543,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '26',
            'DefValue' => 'Allegation: Neglect of Duty'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 544,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '13',
            'DefValue' => 'Allegation: Wrongful Property Damage'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 545,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '27',
            'DefValue' => 'Gender: Male'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 546,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '28',
            'DefValue' => 'Gender: Female'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 547,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '29',
            'DefValue' => 'Gender: Trans & Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 548,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '30',
            'DefValue' => 'Race: Asian'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 549,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '31',
            'DefValue' => 'Race: Black/African/Caribbean'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 550,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '32',
            'DefValue' => 'Race: Hispanic/Latinx'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 551,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '33',
            'DefValue' => 'Race: Middle Eastern'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 552,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '34',
            'DefValue' => 'Race: Native American'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 553,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '35',
            'DefValue' => 'Race: Pacific Islander'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 554,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '36',
            'DefValue' => 'Race: White/Caucasian'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 555,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Complaint Filters',
            'DefOrder' => '37',
            'DefValue' => 'Race: Other'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 556,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Referral Alerts',
            'DefValue' => 'Never email, log in to review matches'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 557,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Referral Alerts',
            'DefOrder' => '1',
            'DefValue' => 'Email each referral match immediately'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 558,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Referral Alerts',
            'DefOrder' => '2',
            'DefValue' => 'Email daily list of matches, if any'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 559,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Referral Alerts',
            'DefOrder' => '3',
            'DefValue' => 'Email matches, if any, every three days'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 560,
            'DefDatabase' => '1',
            'DefSubset' => 'Attorney Referral Alerts',
            'DefOrder' => '4',
            'DefValue' => 'Email matches, if any, every week'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 561,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '1',
            'DefValue' => 'Dashboard',
            'DefDescription' => '/dashboard'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 562,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '7'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 563,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '8'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 564,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '9'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 565,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '10'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 566,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '11'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 567,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '12'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 568,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '13'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 569,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '14'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 570,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '15'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 571,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '16'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 572,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '17'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 573,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '18'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 574,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '19'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 575,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '20'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 576,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '21'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 577,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '22'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 578,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '23'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 579,
            'DefDatabase' => '1',
            'DefSet' => 'Menu Settings',
            'DefSubset' => 'main-navigation',
            'DefOrder' => '24'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 582,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Weapons',
            'DefValue' => 'No weapon'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 583,
            'DefDatabase' => '1',
            'DefSubset' => 'Civilian Weapons',
            'DefOrder' => '4',
            'DefValue' => 'Not sure'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 584,
            'DefDatabase' => '1',
            'DefSubset' => 'Partner Types',
            'DefOrder' => '6',
            'DefValue' => 'Organization'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 585,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '2',
            'DefValue' => 'Services or Support for Police Brutality Victims'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 586,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '8',
            'DefValue' => 'Political or Legislative Action'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 587,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '3',
            'DefValue' => 'Education About Police or Corrections'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 588,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefValue' => 'Assist Complainants Using OpenPolice.org'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 589,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '6',
            'DefValue' => 'Legal Assistance for Criminal Cases'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 590,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '7',
            'DefValue' => 'Legal Representation for Criminal Cases'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 592,
            'DefDatabase' => '1',
            'DefSubset' => 'Transportation',
            'DefOrder' => '1',
            'DefValue' => 'Truck'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 593,
            'DefDatabase' => '1',
            'DefSubset' => 'Vehicle Roles',
            'DefValue' => 'Driver'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 594,
            'DefDatabase' => '1',
            'DefSubset' => 'Vehicle Roles',
            'DefOrder' => '1',
            'DefValue' => 'Passenger'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 595,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefDescription' => 'Focus on one incident for now.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 596,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '1',
            'DefDescription' => 'Be as professional as possible.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 597,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '2',
            'DefDescription' => 'We are saving your progress.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 598,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '3',
            'DefDescription' => 'Your story matters.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 599,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '4',
            'DefDescription' => 'You can log out and log in anytime.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 600,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '5',
            'DefDescription' => 'Your progress through each section is below.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 601,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '6',
            'DefDescription' => 'Most questions are optional.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 602,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '7',
            'DefDescription' => 'You are brave.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 603,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '8',
            'DefDescription' => 'Answer every question you can.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 604,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '9',
            'DefDescription' => 'Jump around the survey with the buttons at the bottom.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 605,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '10',
            'DefDescription' => 'Be truthful.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 606,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '9',
            'DefValue' => 'Accepts Legal Referrals through OpenPolice.org',
            'DefDescription' => 'Partners with this capacity — by definition — are registered to be matched with complainants searching for legal help.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 607,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'app-license-snc',
            'DefDescription' => '2015'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 608,
            'DefDatabase' => '1',
            'DefSubset' => 'Allegation Type',
            'DefOrder' => '13',
            'DefValue' => 'Repeat Harassment',
            'DefDescription' => 'Circumstances where a department member has had repeated or continued contact with a person without lawful police justification.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 609,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '5',
            'DefValue' => 'Legal Representation for Civil Cases'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 610,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '4',
            'DefValue' => 'Legal Assistance for Civil Cases'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 611,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '11',
            'DefDescription' => 'You are not alone.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 612,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '12',
            'DefDescription' => 'Reload the page if it freezes.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 613,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 614,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '1'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 615,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '2'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 616,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '3'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 617,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '4'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 618,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '5'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 619,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '6'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 620,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '7'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 621,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '8'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 622,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '9'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 623,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '10'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 624,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '11'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 625,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '12'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 626,
            'DefDatabase' => '1',
            'DefSet' => 'Blurbs',
            'DefSubset' => 'Tree Map Header: Complaint',
            'DefIsActive' => '3',
            'DefDescription' => '<div class="mT20 mL15 mR15"><p>
Our team developed OpenPolice.org in partnership with police oversight professionals. We designed these tools to serve the needs of police accountability activists, investigators, attorneys, police chiefs, and others working to advance police transparency and accountability through open data.
<br />
<a href="/complaint-xml-schema" target="_blank">XML Schema</a>,   
<a href="/complaint/read-18/xml" target="_blank">XML Complaint Example</a>
<br />
<nobr><a href="#licenseInfo"><i class="fa fa-creative-commons mR3" aria-hidden="true"></i> 2015-2019</a></nobr>

</p></div>
<div class="mL15 mR15">[[TreeStats]]</div>'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 627,
            'DefDatabase' => '1',
            'DefSubset' => 'Complaint Status',
            'DefOrder' => '4',
            'DefValue' => 'Needs More Work',
            'DefDescription' => 'Not published. Staff has decided that the complainant needs to work on their submission more before it is ready to file with an investigative agency.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 628,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefValue' => 'Agent'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 629,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '1',
            'DefValue' => 'Assistant Chief of Police'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 630,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '2',
            'DefValue' => 'Assistant Commissioner'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 631,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '3',
            'DefValue' => 'Assistant Sheriff'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 632,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '4',
            'DefValue' => 'Assistant Superintendent'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 633,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '5',
            'DefValue' => 'Captain (Capt.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 634,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '6',
            'DefValue' => 'Chief Deputy'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 635,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '7',
            'DefValue' => 'Chief of Police'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 636,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '8',
            'DefValue' => 'Colonel (Col.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 637,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '9',
            'DefValue' => 'Commander (Cdr.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 638,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '10',
            'DefValue' => 'Commissioner'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 639,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '11',
            'DefValue' => 'Corporal (Cpl.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 640,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '12',
            'DefValue' => 'Deputy'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 641,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '13',
            'DefValue' => 'Deputy Chief of Police'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 642,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '14',
            'DefValue' => 'Deputy Commissioner'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 643,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '15',
            'DefValue' => 'Deputy Superintendent'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 644,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '16',
            'DefValue' => 'Detective (Det.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 645,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '17',
            'DefValue' => 'Director (Dir.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 646,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '18',
            'DefValue' => 'Inspector (Insp.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 647,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '19',
            'DefValue' => 'Investigator'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 648,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '20',
            'DefValue' => 'Lieutenant (Lt.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 649,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '21',
            'DefValue' => 'Lieutenant Colonel (Lt. Col.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 650,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '22',
            'DefValue' => 'Major (Maj.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 651,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '23',
            'DefValue' => 'Officer (Ofc.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 652,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '24',
            'DefValue' => 'Patrol Officer'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 653,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '25',
            'DefValue' => 'Police Officer'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 654,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '26',
            'DefValue' => 'Police Commissioner'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 655,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '27',
            'DefValue' => 'Sergeant (Sgt.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 656,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '28',
            'DefValue' => 'Sheriff'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 657,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '29',
            'DefValue' => 'Superintendent (Supt.)'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 658,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '30',
            'DefValue' => 'Trooper'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 659,
            'DefDatabase' => '1',
            'DefSubset' => 'Police Officer Ranks',
            'DefOrder' => '31',
            'DefValue' => 'Undersheriff'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 660,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'has-avatars',
            'DefDescription' => '/openpolice/uploads/avatar-shell.png'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 661,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protip',
            'DefOrder' => '13',
            'DefDescription' => 'You are part of the solution.'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 662,
            'DefDatabase' => '1',
            'DefSet' => 'Tree Settings',
            'DefSubset' => 'tree-1-protipimg',
            'DefOrder' => '13'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 667,
            'DefDatabase' => '1',
            'DefSubset' => 'Organization Capabilities',
            'DefOrder' => '1',
            'DefValue' => 'Hosts Clinics for Using OpenPolice.org'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 668,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'sys-cust-js',
            'DefDescription' => '/* */'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 669,
            'DefDatabase' => '1',
            'DefSet' => 'System Settings',
            'DefSubset' => 'sys-cust-ajax',
            'DefDescription' => '/* */'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 670,
            'DefDatabase' => '1',
            'DefSubset' => 'Verified Officer Status',
            'DefValue' => 'Department Employment Not Verified'
        ]);
        DB::table('SL_Definitions')->insert([
            'DefID' => 671,
            'DefDatabase' => '1',
            'DefSubset' => 'Verified Officer Status',
            'DefOrder' => '1',
            'DefValue' => 'Department Employment Verified'
        ]);
    

    DB::table('SL_Tree')->insert([
            'TreeID' => 3,
            'TreeDatabase' => '3',
            'TreeUser' => '0',
            'TreeOpts' => '1',
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
            'TreeOpts' => '1',
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
    

    }
}