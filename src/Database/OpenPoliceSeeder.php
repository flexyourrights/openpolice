<?php 
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-gen-seeder.blade.php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {

        DB::table('SL_Tables')->insert([
			'TblID' => 102,
			'TblDatabase' => '1',
			'TblAbbr' => 'Civ',
			'TblName' => 'Civilians',
			'TblEng' => 'Civilians',
			'TblDesc' => 'Individuals who are directly impacted by, observed, or are reporting on an police incident. This information allows us to identify and contact individuals who\'ve been impacted by alleged incident.',
			'TblGroup' => 'People Involved In Complaint',
			'TblOrd' => '26',
			'TblExtend' => '0',
			'TblNumFields' => '24',
			'TblNumForeignKeys' => '4',
			'TblNumForeignIn' => '6'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 104,
			'TblDatabase' => '1',
			'TblAbbr' => 'Off',
			'TblName' => 'Officers',
			'TblEng' => 'Officers',
			'TblDesc' => 'Includes law enforcement personnel who engaged in alleged misconduct or who witnessed alleged misconduct mentioned in a Complaint. This information is vital for verifying the identity of named and unnamed officers who were involved in alleged police misconduct.',
			'TblGroup' => 'People Involved In Complaint',
			'TblOrd' => '27',
			'TblExtend' => '0',
			'TblNumFields' => '21',
			'TblNumForeignKeys' => '4',
			'TblNumForeignIn' => '5'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 106,
			'TblDatabase' => '1',
			'TblAbbr' => 'Over',
			'TblName' => 'Oversight',
			'TblEng' => 'Oversight Agencies',
			'TblDesc' => 'These are the organizations tasked with receiving and investigating Civilian Complaints against Officers. This information helps us keep track of who is investigating Complaints submitted through OPC.',
			'TblNotes' => 'There are 2 types of Oversight Agencies: Internal Affairs (IA) & Citizen Oversight Agencies (COAs). Every police department has an Internal Affairs contact â?? but many medium and larger municipalities also have a COA tasked with receiving and investigating citizen complaints. COAs are generally more responsive than IAs. THEREFORE, if a police department has a NACOLE-approved COA, the table data must ONLY contain COA contact information â?? but no IA contact information.',
			'TblGroup' => 'People & Groups Involved In Complaint Process',
			'TblOrd' => '33',
			'TblExtend' => '0',
			'TblNumFields' => '38',
			'TblNumForeignKeys' => '2',
			'TblNumForeignIn' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 108,
			'TblDatabase' => '1',
			'TblAbbr' => 'Adm',
			'TblName' => 'Administrators',
			'TblEng' => 'Administrators',
			'TblDesc' => 'OPC staff and volunteers with special access permission to review and manage Complaints. This information allows OPC management to measure the performance of individual Administrators.',
			'TblGroup' => 'People & Groups Involved In Complaint Process',
			'TblOrd' => '35',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2',
			'TblNumForeignIn' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 110,
			'TblDatabase' => '1',
			'TblAbbr' => 'Part',
			'TblName' => 'Partners',
			'TblEng' => 'Partners',
			'TblDesc' => 'People and organizations who access our detailed complaints data to assist their police accountability efforts and research. Information in this table is vital to the organization, because it enables us to communicate with Partners about our collaboration and service offerings.',
			'TblNotes' => 'Partners may also include prospects.',
			'TblGroup' => 'People & Groups Involved In Complaint Process',
			'TblOrd' => '34',
			'TblExtend' => '0',
			'TblNumFields' => '6',
			'TblNumForeignKeys' => '2',
			'TblNumForeignIn' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 111,
			'TblDatabase' => '1',
			'TblAbbr' => 'Dept',
			'TblName' => 'Departments',
			'TblEng' => 'Police Departments',
			'TblDesc' => 'Includes Local, State, and Federal law enforcement agencies and their jurisdictional boundaries. This table information allows the organization to identify and track Departments associated with Complaints.',
			'TblNotes' => 'Data obtained thanks to Prof. Hickman.',
			'TblGroup' => 'People & Groups Involved In Complaint Process',
			'TblOrd' => '32',
			'TblExtend' => '0',
			'TblNumFields' => '24',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '5'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 112,
			'TblDatabase' => '1',
			'TblAbbr' => 'Com',
			'TblName' => 'Complaints',
			'TblEng' => 'Complaints',
			'TblDesc' => 'Reports of dissatisfaction that contain one or more Allegations of Officer misconduct. Complaints are vital, because they provide the essential store of information for communicating what happened during a police encounter. Complaints data also helps the organization keep track of how various police accountability professionals are responding to Complaint Allegations.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOpts' => '11',
			'TblExtend' => '0',
			'TblNumFields' => '36',
			'TblNumForeignKeys' => '5',
			'TblNumForeignIn' => '11'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 113,
			'TblDatabase' => '1',
			'TblAbbr' => 'Alle',
			'TblName' => 'Allegations',
			'TblEng' => 'Allegations',
			'TblDesc' => 'Represent specific misconduct accusations against Officers, vital to identifying the most serious Complaints. This information also helps OPC provide useful next-steps advice for Complainants who generate Complaints. This table provides storage for the descriptions of why each Allegation has been made, and a record to link with Officers and/or Victims.',
			'TblNotes' => 'San Jose IPA has model categories

http://www.sanjoseca.gov/DocumentCenter/View/29599',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '5',
			'TblExtend' => '0',
			'TblNumFields' => '5',
			'TblNumForeignKeys' => '2',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 114,
			'TblDatabase' => '1',
			'TblAbbr' => 'Inc',
			'TblName' => 'Incidents',
			'TblEng' => 'Incidents',
			'TblDesc' => 'Represent individual, uninterrupted police events occurring at a specific time and place. Incident information is vital for identifying and associating data from multiple Complaints with each other.',
			'TblNotes' => 'Generally, we have a goal of one-complaint-per-incident. But in cases where Complaint data is inconsistent, Incident data allows us to simply associate them with each other in a less invasive way.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '2',
			'TblExtend' => '0',
			'TblNumFields' => '13',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 115,
			'TblDatabase' => '1',
			'TblAbbr' => 'Scn',
			'TblName' => 'Scenes',
			'TblEng' => 'Scenes',
			'TblDesc' => 'Scene information provides descriptive details about the setting where an Incident occurred. Beyond mere time and location, this adds an important contextual backdrop behind specific Incident events. Scene information also helps provide a more objective evaluation of the Incident and Allegations.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '7',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 116,
			'TblDatabase' => '1',
			'TblAbbr' => 'For',
			'TblName' => 'Force',
			'TblEng' => 'Force',
			'TblDesc' => 'Includes all Allegations that feature use of violent contact up to and including deadly Force by an Officer. Because excessive Force Allegations deserve the greatest scrutiny, this information is vital to substantiating such Allegations if they are truthful.',
			'TblNotes' => 'Subset data of Event Sequences. ',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '14',
			'TblExtend' => '0',
			'TblNumFields' => '13',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '3'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 117,
			'TblDatabase' => '1',
			'TblAbbr' => 'Inj',
			'TblName' => 'Injuries',
			'TblEng' => 'Injuries',
			'TblDesc' => 'Includes all instances of bodily harm sustained as a result of alleged Force initiated by Officers. Detailed Injury information is vital to substantiating such Allegations if they are truthful.',
			'TblNotes' => 'There can be multiple Injury records for each victim (Civilian).',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '20',
			'TblExtend' => '0',
			'TblNumFields' => '5',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 120,
			'TblDatabase' => '1',
			'TblAbbr' => 'Ord',
			'TblName' => 'Orders',
			'TblEng' => 'Orders',
			'TblDesc' => 'Includes all instances of requests and commands stated by Officers. This information is vital to evaluating the legal legitimacy of Officer actions and helps put Complaint events in chronological order.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '18',
			'TblExtend' => '0',
			'TblNumFields' => '4',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 121,
			'TblDatabase' => '1',
			'TblAbbr' => 'Stop',
			'TblName' => 'Stops',
			'TblEng' => 'Stops',
			'TblDesc' => 'Includes all instances where Officers pull over a vehicle, detain a pedestrian, or otherwise question a Civilian at a residence. This table provides important information about Officers\' behavior and Stop practices within particular Departments.',
			'TblNotes' => 'Subset data of Event Sequences. This table could also be called Detentions, because both traffic stops and stop-and-frisk fall under the purview Terry v. Ohio. Provides link to article on topic.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '8',
			'TblExtend' => '0',
			'TblNumFields' => '31',
			'TblNumForeignKeys' => '2',
			'TblNumForeignIn' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 122,
			'TblDatabase' => '1',
			'TblAbbr' => 'Srch',
			'TblName' => 'Searches',
			'TblEng' => 'Searches',
			'TblDesc' => 'Includes all instances of vehicle searches, home, or other property searches. Detailed Search information is vital to evaluating the legitimacy of the search itself as well as identifying general Search practices within particular Departments.',
			'TblNotes' => 'Subset data of Event Sequences. Provides link to article on topic. 
If there is a Property record associated with a Search record, then property was searched.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '10',
			'TblExtend' => '0',
			'TblNumFields' => '22',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 123,
			'TblDatabase' => '1',
			'TblAbbr' => 'Arst',
			'TblName' => 'Arrests',
			'TblEng' => 'Arrests',
			'TblDesc' => 'Includes all instances where individuals are placed under arrest. This table information helps provide richer detail about the nature of the Arrest and the individual burden of certain low-level discretionary or retaliatory Arrests.',
			'TblNotes' => 'Subset data of Event Sequences. We want to highlight all arrests that appear to be retaliatory in nature. Provides link to article on topic.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '13',
			'TblExtend' => '0',
			'TblNumFields' => '12',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 125,
			'TblDatabase' => '1',
			'TblAbbr' => 'Priv',
			'TblName' => 'PrivilegeProfiles',
			'TblEng' => 'Privileges Profiles',
			'TblDesc' => 'Beyond default privileges for various types of system users, this table includes explicit privileges to enable access to specific system data. This is vital in allowing User IDs (which are associated with different user types) to be granted access to specific complaints or sets of complaints.',
			'TblType' => 'Linking',
			'TblGroup' => 'Internal Infrastructure',
			'TblOrd' => '48',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '4',
			'TblNumForeignKeys' => '3'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 126,
			'TblDatabase' => '1',
			'TblAbbr' => 'AdmAct',
			'TblName' => 'AdminActions',
			'TblEng' => 'Administrator Actions',
			'TblDesc' => 'Includes administrator edits to all user data and incident reports. This information provides a complete history of data changes so any human, system, or security errors can be investigated and reversed if necessary.',
			'TblNotes' => '(*This is just how Morgan might implement this need, but the details of its implementation should not effect the core OPC data structures*)',
			'TblType' => 'Validation',
			'TblGroup' => 'Logging Data Tables',
			'TblOrd' => '49',
			'TblExtend' => '0',
			'TblNumFields' => '6',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 128,
			'TblDatabase' => '1',
			'TblAbbr' => 'Surv',
			'TblName' => 'Surveys',
			'TblEng' => 'Surveys',
			'TblDesc' => 'Includes feedback after the entire process has been completed. This is primarily provided by Complainants, but may also be expanded to other types of system Users. This is vital for continuing to improve the quality of the system, both in general and in specific detail.',
			'TblNotes' => 'Maybe even feedback from Subjects and/or Officers, in addition to established User types? 

Question list and fields coming soon...',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '24',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 136,
			'TblDatabase' => '1',
			'TblAbbr' => 'Note',
			'TblName' => 'ComplaintNotes',
			'TblEng' => 'Complaint Notes',
			'TblDesc' => 'Information which must be appended to Complaints any time after they are submitted. This is important for recording updates from system Administrators or even allowing Complainants to potentially upload evidence obtained after initially submitting their report.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '25',
			'TblExtend' => '0',
			'TblNumFields' => '4',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 137,
			'TblDatabase' => '1',
			'TblAbbr' => 'Eve',
			'TblName' => 'EventSequence',
			'TblEng' => 'Event Sequence',
			'TblDesc' => 'Represent key discrete events occurring during a police Incident which require details like Stops, Searches, uses of Force, and Arrests. Event Sequence information is vital for identifying the chronological order of these events. This table also acts as a common reference to associate Orders, Evidence, and People-Event Links with all four of these types of events.',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '7',
			'TblExtend' => '0',
			'TblNumFields' => '4',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '9'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 138,
			'TblDatabase' => '1',
			'TblAbbr' => 'Chrg',
			'TblName' => 'Charges',
			'TblEng' => 'Charges',
			'TblDesc' => 'Includes all instances where individuals are charged without being arrested. This table information helps provide richer detail, especially about the use of excessive/unnecessary citations.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '19',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 144,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkComDept',
			'TblName' => 'LinksComplaintDept',
			'TblEng' => 'Complaint-Department Links',
			'TblDesc' => 'Includes linkages between Police Departments and Complaints. This allows a single Complaint to be associated with more than one Police Department.',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '36',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 145,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkComOver',
			'TblName' => 'LinksComplaintOversight',
			'TblEng' => 'Complaint-Oversight Links',
			'TblDesc' => 'Includes linkages between Oversight Agencies involved in a Complaint. This allows us to track the progress of Complaints being investigated by more than one Oversight Agency.',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '37',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '9',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 146,
			'TblDatabase' => '1',
			'TblAbbr' => 'InjCare',
			'TblName' => 'InjuryCare',
			'TblEng' => 'Injury Care',
			'TblDesc' => 'Information from medical institutions who treated a Civilian\'s Injuries. Important for investigating use of Force Allegations.',
			'TblNotes' => 'There can be only one Injury Care record for each victim (Civilian), but this record should only exist if the victim did have Injuries.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '21',
			'TblExtend' => '0',
			'TblNumFields' => '17',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 147,
			'TblDatabase' => '1',
			'TblAbbr' => 'StopReas',
			'TblName' => 'StopReasons',
			'TblEng' => 'Stop Reasons',
			'TblDesc' => 'The table stores the stated or non-stated Officer explanations to Civilians for a Stop or detention. This table is important for associating multiple Stop Reasons given for a single Stop.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '9',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 148,
			'TblDatabase' => '1',
			'TblAbbr' => 'ForceSub',
			'TblName' => 'ForceSubType',
			'TblEng' => 'Force Sub-Type',
			'TblDesc' => 'This subset table stores secondary types of Use of Force. This table specifically includes greater detail for Use of Force records that include any of these types of force: 1) Control Hold, 2) Body Weapon, and 3) Takedown. This data is important for linking multiple force type details with a single Use of Force.',
			'TblNotes' => 'Many-to-1 relationship with Use of Force',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '15',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 149,
			'TblDatabase' => '1',
			'TblAbbr' => 'Body',
			'TblName' => 'BodyParts',
			'TblEng' => 'Body Parts',
			'TblDesc' => 'This subset table stores which areas of a Civilian\'s body were impacted by Force or Injury. This is important for providing investigators documentation regarding Civilian\'s Injuries.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '17',
			'TblExtend' => '0',
			'TblNumFields' => '3',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 150,
			'TblDatabase' => '1',
			'TblAbbr' => 'SrchCon',
			'TblName' => 'SearchContra',
			'TblEng' => 'Search Contraband',
			'TblDesc' => 'This table stores types of illegal items that were seized during a Search. Important for associating multiple types of contraband items with a single Search.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '11',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 151,
			'TblDatabase' => '1',
			'TblAbbr' => 'SrchSeiz',
			'TblName' => 'SearchSeize',
			'TblEng' => 'Search Seizures',
			'TblDesc' => 'This table stores types of legal property that were seized during a Search. Important for associating multiple types of seized items with a single Search.',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '12',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 152,
			'TblDatabase' => '1',
			'TblAbbr' => 'Vehic',
			'TblName' => 'Vehicles',
			'TblEng' => 'Vehicles',
			'TblDesc' => 'This table includes identifying information about Civilian and Officer vehicles. This data helps investigators locate or verify the identities of Officers and Civilians involved in an Incident.',
			'TblType' => 'Subset',
			'TblGroup' => 'People Involved In Complaint',
			'TblOrd' => '31',
			'TblExtend' => '0',
			'TblNumFields' => '7',
			'TblNumForeignIn' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 153,
			'TblDatabase' => '1',
			'TblAbbr' => 'Phys',
			'TblName' => 'PhysicalDesc',
			'TblEng' => 'Physical Descriptions',
			'TblDesc' => 'This table includes subjective characteristics of Civilians and Officers as described by Complainants. This information is vital for for verifying the identity of Civilians and Officers connected to Complaints.',
			'TblNotes' => 'For example, the Person Contact table has a "date of birth field" while the Physical Descriptions table has an "age range" field.',
			'TblType' => 'Subset',
			'TblGroup' => 'People Involved In Complaint',
			'TblOrd' => '29',
			'TblExtend' => '0',
			'TblNumFields' => '12',
			'TblNumForeignIn' => '3'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 154,
			'TblDatabase' => '1',
			'TblAbbr' => 'Prsn',
			'TblName' => 'PersonContact',
			'TblEng' => 'Person Contact Info',
			'TblDesc' => 'This is the name and contact info for Civilians, Officers, and other system Users. This information is vital for keeping in touch with all the key people connected to the police oversight process.',
			'TblType' => 'Subset',
			'TblGroup' => 'People Involved In Complaint',
			'TblOrd' => '28',
			'TblExtend' => '0',
			'TblNumFields' => '18',
			'TblNumForeignKeys' => '1',
			'TblNumForeignIn' => '5'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 155,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkOffVehic',
			'TblName' => 'LinksOfficerVehicles',
			'TblEng' => 'Officer-Vehicle Links',
			'TblDesc' => 'This table includes linkages between vehicles and people. This allows a single vehicle to be associated with one or more Officer.',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '42',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 156,
			'TblDatabase' => '1',
			'TblAbbr' => 'AlleSil',
			'TblName' => 'AllegSilver',
			'TblEng' => 'Silver Allegations',
			'TblDesc' => 'Tracks the yes/no responses to Silver-Level Allegations against Officers, vital to identifying the most serious Complaints. This information also helps OPC provide useful next-steps advice for Complainants who generate Complaints.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '4',
			'TblExtend' => '0',
			'TblNumFields' => '28',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 157,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkCivAlle',
			'TblName' => 'LinksCivilianAllegations',
			'TblEng' => 'Civilian-Allegation Links',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '43',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 158,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkOffAlle',
			'TblName' => 'LinksOfficerAllegations',
			'TblEng' => 'Officer-Allegation Links',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '39',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 159,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkOffOrd',
			'TblName' => 'LinksOfficerOrders',
			'TblEng' => 'Officer-Order Links',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '41',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 160,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkOffEve',
			'TblName' => 'LinksOfficerEvents',
			'TblEng' => 'Officer-Event Links',
			'TblDesc' => 'Includes linkages between Officers involved in an Incident with key events that happened during an Incident. Each record links one Officer with one Event (a Stop, Search, Property, or Use of Force).',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '40',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 161,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkCivOrd',
			'TblName' => 'LinksCivilianOrders',
			'TblEng' => 'Civilian-Order Links',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '45',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 162,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkCivEve',
			'TblName' => 'LinksCivilianEvents',
			'TblEng' => 'Civilian-Event Links',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '44',
			'TblOpts' => '3',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 163,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkCivVehic',
			'TblName' => 'LinksCivilianVehicles',
			'TblEng' => 'Civilian-Vehicle Links',
			'TblDesc' => 'This table includes linkages between vehicles and people. This allows a single vehicle to be associated with one or more Civilian.',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '46',
			'TblExtend' => '0',
			'TblNumFields' => '3',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 164,
			'TblDatabase' => '1',
			'TblAbbr' => 'PhysRace',
			'TblName' => 'PhysicalDescRace',
			'TblEng' => 'Physical Descriptions Race',
			'TblDesc' => 'This subset table stores races associated with the Physical Descriptions of Civilians and Officers. This table specifically provides the important ability to accurately store data for people with multiple races.',
			'TblNotes' => 'The U.S. Census in 2000 and 2010 has been tracking multiple races for each citizen.',
			'TblType' => 'Subset',
			'TblGroup' => 'People Involved In Complaint',
			'TblOrd' => '30',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 165,
			'TblDatabase' => '1',
			'TblAbbr' => 'CivWeap',
			'TblName' => 'CivWeapons',
			'TblEng' => 'Civilian Body Weapons',
			'TblDesc' => 'This subset table stores one or more body weapons used by a Civilian against an Officer.',
			'TblNotes' => 'Many-to-1 relationship with Complaint',
			'TblType' => 'Subset',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '16',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 167,
			'TblDatabase' => '1',
			'TblName' => 'users',
			'TblEng' => 'Users',
			'TblDesc' => 'This represents the Laravel Users table, but will not actually be implemented by SurvLoop as part of the database installation.',
			'TblOrd' => '50',
			'TblExtend' => '0',
			'TblNumForeignIn' => '14'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 168,
			'TblDatabase' => '1',
			'TblAbbr' => 'Vis',
			'TblName' => 'Visitors',
			'TblEng' => 'Site Visitors',
			'TblDesc' => 'Represent user sessions while visiting the web site. This is useful for tracking visitor responses, searches, etc.',
			'TblGroup' => 'Internal Infrastructure',
			'TblOrd' => '47',
			'TblExtend' => '0',
			'TblNumFields' => '7',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 169,
			'TblDatabase' => '1',
			'TblAbbr' => 'OffComp',
			'TblName' => 'OffCompliments',
			'TblEng' => 'Officer Compliments',
			'TblDesc' => 'Tracks the yes/no responses to Compliments given to Officers.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '6',
			'TblExtend' => '0',
			'TblNumFields' => '9',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 170,
			'TblDatabase' => '1',
			'TblAbbr' => 'Compli',
			'TblName' => 'Compliments',
			'TblEng' => 'Compliments',
			'TblDesc' => 'Reports of satisfaction that contain one or more Compliment/Commendation of Officer conduct. Compliments are vital, because they provide the essential store of information for communicating what happened during positive police encounters.',
			'TblGroup' => 'Complaint Data Tables',
			'TblOrd' => '1',
			'TblExtend' => '0',
			'TblNumFields' => '20',
			'TblNumForeignKeys' => '3',
			'TblNumForeignIn' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 171,
			'TblDatabase' => '1',
			'TblAbbr' => 'LnkCompliDept',
			'TblName' => 'LinksComplimentDept',
			'TblEng' => 'Compliment-Department Links',
			'TblDesc' => 'Includes linkages between Police Departments and Complaints. This allows a single Complaint to be associated with more than one Police Department.',
			'TblType' => 'Linking',
			'TblGroup' => 'Complaint Linkage Tables',
			'TblOrd' => '38',
			'TblExtend' => '0',
			'TblNumFields' => '2',
			'TblNumForeignKeys' => '2'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 172,
			'TblDatabase' => '1',
			'TblAbbr' => 'ZedDept',
			'TblName' => 'Zedit_Departments',
			'TblEng' => 'Edits: Police Departments',
			'TblDesc' => 'Each record stores a copy of individual edits made to unique records in the Departments table.',
			'TblType' => 'Validation',
			'TblGroup' => 'Record Edit Histories',
			'TblOrd' => '60',
			'TblExtend' => '111',
			'TblNumFields' => '2',
			'TblNumForeignIn' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 173,
			'TblDatabase' => '1',
			'TblAbbr' => 'ZedOver',
			'TblName' => 'Zedit_Oversight',
			'TblEng' => 'Edits: Oversight Agencies',
			'TblDesc' => 'Each record stores a copy of individual edits made to unique records in the Oversight table.',
			'TblType' => 'Validation',
			'TblGroup' => 'Record Edit Histories',
			'TblOrd' => '61',
			'TblExtend' => '106',
			'TblNumFields' => '5',
			'TblNumForeignKeys' => '1'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 174,
			'TblDatabase' => '1',
			'TblAbbr' => 'VolunStat',
			'TblName' => 'zVolunStatDays',
			'TblEng' => 'Volunteers: Daily Stats',
			'TblDesc' => 'Each record stores daily statistics for volunteer activity.',
			'TblType' => 'Validation',
			'TblGroup' => 'Internal Volunteer Data',
			'TblOrd' => '70',
			'TblExtend' => '0',
			'TblNumFields' => '15'
		]);
		DB::table('SL_Tables')->insert([
			'TblID' => 176,
			'TblDatabase' => '1',
			'TblAbbr' => 'UserInfo',
			'TblName' => 'zVolunUserInfo',
			'TblEng' => 'Volunteers: User Info',
			'TblDesc' => 'Each record stores extra information related to one volunteer User.',
			'TblGroup' => 'Internal Volunteer Data',
			'TblOrd' => '72',
			'TblExtend' => '0',
			'TblNumFields' => '8',
			'TblNumForeignKeys' => '2'
		]);
	
	DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 1,
			'LnkCivVehicCivID' => '8',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 4,
			'LnkCivVehicCivID' => '51',
			'LnkCivVehicVehicID' => '15',
			'LnkCivVehicRole' => 'Passenger'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 5,
			'LnkCivVehicCivID' => '52',
			'LnkCivVehicVehicID' => '16',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 6,
			'LnkCivVehicCivID' => '53',
			'LnkCivVehicVehicID' => '17',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 7,
			'LnkCivVehicCivID' => '57',
			'LnkCivVehicVehicID' => '18',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 8,
			'LnkCivVehicVehicID' => '18',
			'LnkCivVehicRole' => 'Passenger'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 9,
			'LnkCivVehicCivID' => '50',
			'LnkCivVehicVehicID' => '19'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 10,
			'LnkCivVehicCivID' => '59',
			'LnkCivVehicVehicID' => '20',
			'LnkCivVehicRole' => 'Passenger'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 11,
			'LnkCivVehicCivID' => '62'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 12,
			'LnkCivVehicCivID' => '69',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 13,
			'LnkCivVehicCivID' => '92',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 14,
			'LnkCivVehicCivID' => '93',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 15,
			'LnkCivVehicCivID' => '95'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 16,
			'LnkCivVehicCivID' => '96'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 17,
			'LnkCivVehicCivID' => '97'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 18,
			'LnkCivVehicCivID' => '98'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 19,
			'LnkCivVehicCivID' => '101'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 20,
			'LnkCivVehicCivID' => '103',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 21,
			'LnkCivVehicCivID' => '104',
			'LnkCivVehicRole' => 'Driver'
		]);
		DB::table('OP_LinksCivilianVehicles')->insert([
			'LnkCivVehicID' => 22,
			'LnkCivVehicCivID' => '105'
		]);
	



	DB::table('SL_Fields')->insert([
			'FldID' => 190,
			'FldDatabase' => '1',
			'FldTable' => '29',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Experience.',
			'FldForeignTable' => '167',
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
			'FldID' => 191,
			'FldDatabase' => '1',
			'FldTable' => '29',
			'FldName' => 'SubmissionProgress',
			'FldEng' => 'Experience Node Progress',
			'FldDesc' => 'Indicates the unique Node ID number of the last Experience Node loaded during this User\'s Experience.',
			'FldForeignTable' => '0',
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
			'FldID' => 192,
			'FldDatabase' => '1',
			'FldTable' => '29',
			'FldName' => 'VersionAB',
			'FldEng' => 'A/B Testing Version',
			'FldDesc' => 'Stores a complex string reflecting all A/B Testing variations in effect at the time of this User\'s Experience of this Node.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldOpts' => '39'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 200,
			'FldDatabase' => '1',
			'FldTable' => '5',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Opts',
			'FldEng' => 'Tree Options',
			'FldDesc' => 'A brief narrative profile drafted by OPC Administrators. This helps us identify and keep track of Administrator\'s skills and qualifications related to the work.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 208,
			'FldDatabase' => '1',
			'FldTable' => '110',
			'FldOrd' => '4',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '923',
			'FldName' => 'CompanyName',
			'FldEng' => 'Company Name',
			'FldDesc' => 'The full name of the business or or organization a Customer represents. This is an integral component of a Customer\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 209,
			'FldDatabase' => '1',
			'FldTable' => '110',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '924',
			'FldName' => 'CompanyWebsite',
			'FldEng' => 'Website',
			'FldDesc' => 'The home page of a Customer\'s business or organization. Might include important updated information about Customers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 210,
			'FldDatabase' => '1',
			'FldTable' => '110',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '922',
			'FldName' => 'Title',
			'FldEng' => 'Title',
			'FldDesc' => 'The job position of a Customer within an organization. We might use this in formal communications with Customers or regarding Customers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 217,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '29',
			'FldSpecSource' => '0',
			'FldName' => 'SubmitDeadline',
			'FldEng' => 'Days Until Submission Deadline',
			'FldDesc' => 'Indicates that there is a policy stipulating a maximum number of days between when the Incident occurred and when a Complaint must be submitted [or received?] by an Oversight Agency in order for it to be properly investigated. This information might help OPC Administrators prioritize the review of new complaints. For Departments without a time limit for submitting Complaints, this field is stored as -1.',
			'FldNotes' => 'This is used to calculate deadlines for each Complaint in the Administrator tools.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 222,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '29',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '0',
			'FldName' => 'AttID',
			'FldEng' => 'Attorney ID',
			'FldDesc' => 'The unique number of the Customer record related to this Complaint. This is important for identifying the Attorney who has taken this case.',
			'FldForeignTable' => '110',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 223,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Submitted',
			'FldEng' => 'Submitted to Oversight Agency',
			'FldDesc' => 'Indicates date and time when an electronic Complaint was sent to an appropriate Oversight Agency. This is an essential for tracking an Oversight Agency\'s response -- or non-response -- rates to OPC-submitted Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NULL',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 224,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Received',
			'FldEng' => 'Received by Oversight Agency',
			'FldDesc' => 'Indicates date and time when an electronic Complaint was received by an appropriate Oversight Agency. This is essential for tracking an Oversight Agency\'s  response time when acknowledging receipt of OPC-submitted Complaints.',
			'FldNotes' => 'We don\'t yet know how many departments will opt to electronically indicate that they\'ve received OPC complaints. In fact, many will likely skip this process and directly contact Complainants.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NULL',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 225,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '925',
			'FldName' => 'OverID',
			'FldEng' => 'Oversight ID',
			'FldDesc' => 'The unique number of the Oversight Agency record involved with this Complaint. This number helps track the investigative progress of any Oversight Agencies who have jurisdiction over this Complaint.',
			'FldForeignTable' => '106',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldEditRule' => 'NowNot',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 230,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '25',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerDisciplined',
			'FldEng' => 'Officer Disciplined',
			'FldDesc' => 'Indicates whether or not any Subject Officers associated with a Complaint have received any formal punishment for their actions. Important for for statistical purposes and for bringing sense of justice and closure to Civilians.',
			'FldNotes' => 'The likelihood of an individual complaint allegation being sustained is very low. However, the likelihood of obtaining officer discipline data relating to a specific sustained complaint is extremely remote.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 231,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '26',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerDisciplineType',
			'FldEng' => 'Officer Discipline Type',
			'FldDesc' => 'Indicates the category of formal punishment any Subject Officers associated with a Complaint have received. Important for statistical purposes and for bringing sense of justice and closure to Civilians.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Officer Discipline Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 232,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '27',
			'FldSpecSource' => '0',
			'FldName' => 'MediaLinks',
			'FldEng' => 'Media Links',
			'FldDesc' => 'The URL address of a news report containing information related to this Complaint. Important for tracking and verifying new information related to a Complaint\'s Allegations.',
			'FldNotes' => 'Proper news story selection criteria here: http://www.policemisconduct.net/about/news-feed-faq/

Must be careful not to let people post links to their personal blogs, because that won\'t include objective information.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 234,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'AgencyComplaintNumber',
			'FldEng' => 'Agency Complaint Number',
			'FldDesc' => 'A unique number used to identify Complaints. It is assigned by the Oversight Agency investigating an OPC-generated Complaint.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 240,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '841',
			'FldName' => 'Address',
			'FldEng' => 'Street Address',
			'FldDesc' => 'The first line of the postal location at or near where an Incident occurred. This is an integral component of a complete Incident location address.',
			'FldNotes' => 'Could we indicate highway mile markers or exits in the address field -- or should that be part of a separate field? Incident address locations should only include locations within the 50 U.S. states and the District of Columbia.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 245,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '10',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '938',
			'FldName' => 'TimeStart',
			'FldEng' => 'Start Time',
			'FldDesc' => 'The date, hour, and minute when a single Incident begins. This information is vital for verifying an Incident or merging Incident data from other sources.',
			'FldNotes' => 'Morgan: We might need a Business rule indicating that the Incident "Start Time" and "End Time" can\'t be more than 24 hours apart. Specifically, we don\'t want Complainants reporting allegations from multiple Incidents lumping them into a months or years-long Incident timeframe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldRequired' => '1',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 246,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '11',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '939',
			'FldName' => 'TimeEnd',
			'FldEng' => 'End Time',
			'FldDesc' => 'The date, hour, and minute when a single Incident ends. This information is vital for verifying an Incident occurrence or merging Incident data from other sources.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 247,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '12',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '940',
			'FldName' => 'Duration',
			'FldEng' => 'Duration',
			'FldDesc' => 'The total calculated minutes of of a single Incident.  This information is vital for verifying an Incident occurrence or merging Incident data from other sources.',
			'FldNotes' => 'calculated based on Incident Start Time and Incident End Time.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '5',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 249,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Scene Type',
			'FldDesc' => 'Category of possible settings where an Incident occurred. This selection provides important contextual information about the location of the Incident, which helps us better evaluate the Incident and Allegations.',
			'FldNotes' => 'Which of the following best describes the scene of the incident? If the incident includes multiple locations, pick the one where you first witnessed police contact.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Scene Type',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 250,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Description',
			'FldEng' => 'Description',
			'FldDesc' => 'Narrative details about the setting where an Incident occurred. Beyond mere time and location, scene information adds an important contextual backdrop behind specific Incident events.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 256,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Which Types of Force did this officer use?',
			'FldDesc' => 'The categories of Force an Officer used on a Subject. Essential for evaluating Allegations of excessive force and for tracking Force trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Force Type',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 257,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'TypeOther',
			'FldEng' => 'Other Type of Force',
			'FldDesc' => 'Category of Force an Officer used on Subject. Important for identifying less-common Force types.',
			'FldNotes' => 'Only visible if "Other" selected under "Which Types of Force did this officer used?"',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 258,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'HowManyTimes',
			'FldEng' => 'How many times was Subject struck with this type of force?',
			'FldDesc' => 'Indicates the number of times an Officer hit a Subject with this type of Force. Important for investigating Allegations of Force and for identifying Force trends.',
			'FldNotes' => 'We might not want to reveal this for certain types of force -- such takedowns and control holds. Because those are likely to only be deployed once.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 260,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'OrdersBeforeForce',
			'FldEng' => 'Did this officer issue orders before use of force?',
			'FldDesc' => 'Indicates whether or not an Officer issued orders before using Force against a Subject. Important for investigating Excessive Force Allegations of Excessive Force.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 262,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'OrdersSubjectResponse',
			'FldEng' => 'What did the victim say or do before use of force?',
			'FldDesc' => 'Narrative account of what the Subject of an Excessive Force Allegation said or did before Officer used Force. Important for investigating Excessive Force Allegations and for identifying Force trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 263,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'WhileHandcuffed',
			'FldEng' => 'Victim handcuffed when struck?',
			'FldDesc' => 'Indicates whether or not Subject was handcuffed when struck by an Officer. Important for evaluating Allegations of Excessive Force and tracking Force trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 264,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'WhileHeldDown',
			'FldEng' => 'Victim held down when struck?',
			'FldDesc' => 'Indicates whether or not Subject was restrained on the ground when struck by an Officer. Important for evaluating Allegations of Excessive Force and tracking Force trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 269,
			'FldDatabase' => '1',
			'FldTable' => '117',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'HowManyTimes',
			'FldEng' => 'How many Injuries',
			'FldDesc' => 'A number to indicate how many Injuries a particular Civilian received during an use of Force Incident. Important for quantifying the number of discrete Injuries received from Force.',
			'FldNotes' => 'We have decided this might not be necessary 10/17/15======This field hidden for Injury Types (these types count by Injury Locations): In-patient hospital stay required, Blood loss requiring transfusion, Major concussion, Longer than brief loss of consciousness, Minor concussion, Brief loss of consciousness, ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 271,
			'FldDatabase' => '1',
			'FldTable' => '117',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Injury Type',
			'FldDesc' => 'Category of Injury on a Civilian\'s body resulting from use of Force. Important for evaluating Allegations of excessive Force.',
			'FldNotes' => 'SJ IPA has twenty injury options broken into six categories -- including "none," "pre-existing," and "unknown."',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Injury Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 272,
			'FldDatabase' => '1',
			'FldTable' => '117',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Description',
			'FldEng' => 'Injury Description',
			'FldDesc' => 'Additional narrative information about a specific Injury. Important for evaluating Allegations of excessive Force.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 273,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'ResultInDeath',
			'FldEng' => 'Result in Death?',
			'FldDesc' => 'Indicates that an Injury resulted in death to the Civilian. Important for identifying police killings.',
			'FldNotes' => 'This might be redundant if we have a "Fatal injuries" selection under "Injury Type." However, I feel like this might be an important stand-alone question. 
Implementation Note: This should NOT be asked if the Complainant indicates that they are the Civilian linked to this particular Injury -- because that would be damn near impossible.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 274,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'TimeOfDeath',
			'FldEng' => 'Date & Time of Death',
			'FldDesc' => 'Indicates the official time of death of a Civilian resulting from a Force Injury. Important for statistical purposes.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 275,
			'FldDatabase' => '1',
			'FldTable' => '117',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectID',
			'FldEng' => 'Subject of Injury',
			'FldDesc' => 'Indicates which Subject\'s Injuries are described by this record.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 276,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'HospitalTreated',
			'FldEng' => 'Hospital Where Treated',
			'FldDesc' => 'The name of the hospital where the Civilian who received Injuries was initially treated. Important for verifying Injury information.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 277,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'DoctorNameFirst',
			'FldEng' => 'Doctor First Name',
			'FldDesc' => 'The legal given name of a doctor who treated an Injury. Important for identifying doctors who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 279,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'DoctorNameLast',
			'FldEng' => 'Doctor Last Name',
			'FldDesc' => 'The legal surname of a doctor who treated an Injury. Important for identifying doctors who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 280,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'DoctorEmail',
			'FldEng' => 'Doctor Email',
			'FldDesc' => 'The valid email address of a doctor who treated an Injury. Important for communicating with doctors who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 281,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'DoctorPhone',
			'FldEng' => 'Doctor Phone',
			'FldDesc' => 'The contact number of a doctor who treated an Injury. Important for communicating with doctors who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 282,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyOnScene',
			'FldEng' => 'Emergency Staff on Scene?',
			'FldDesc' => 'Indicates whether or not emergency medical staff were present at the Scene where any Injuries were received. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 283,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyNameFirst',
			'FldEng' => 'Emergency Staff First Name',
			'FldDesc' => 'The legal given name of an emergency medical services staffer present at the Scene where any Injuries were received. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 285,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyNameLast',
			'FldEng' => 'Emergency Staff Last Name',
			'FldDesc' => 'The legal surname of an emergency medical services staffer present at the Scene where any Injuries were received. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 286,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyIDnumber',
			'FldEng' => 'Emergency Staff ID Number',
			'FldDesc' => 'A unique number assigned to the emergency medical services staffer by their department. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 287,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyVehicleNumber',
			'FldEng' => 'Emergency Staff Vehicle Number',
			'FldDesc' => 'A unique number assigned to an emergency medical services vehicle. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 288,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyLicenceNumber',
			'FldEng' => 'Emergency Staff Licence Number',
			'FldDesc' => 'The license plate number of an emergency medical services vehicle. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 289,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'EmergencyDeptName',
			'FldEng' => 'Emergency Staff Department Name',
			'FldDesc' => 'The official name of the emergency medical services department. Important for identifying emergency medical personnel who treated Injuries.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 294,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'Seized',
			'FldEng' => 'Property Seized?',
			'FldDesc' => 'Indicates that Officers took property from a Subject. Important for evaluating seizure Allegations and forfeiture trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 296,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'SeizedDesc',
			'FldEng' => 'Seized Description',
			'FldDesc' => 'Additional narrative information about the specific Property items that were seized. Important for evaluating Property seizure Allegations.',
			'FldNotes' => 'Describe the specific items seized. For example, if cash seized, please specify the dollar amount.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 298,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'Damage',
			'FldEng' => 'Property Damage?',
			'FldDesc' => 'Indicates that Officers damaged or destroyed Subject\'s Property. Important for evaluating Property damage claims.',
			'FldNotes' => 'IF Yes, then Evidence can be optionally uploaded.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 300,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'DamageDesc',
			'FldEng' => 'Damage Description',
			'FldDesc' => 'Additional information about the nature of the damaged or destroyed Property. Important for evaluating Property damage claims.',
			'FldNotes' => 'Describe the specific items damaged and the nature of the damage. Please include dollar amounts for repairing or replacing damaged items.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 311,
			'FldDatabase' => '1',
			'FldTable' => '120',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'TroubleUnderstading',
			'FldEng' => 'Why Subject Trouble Understanding Order?',
			'FldDesc' => 'Indicates whether or not a Subject had difficulty hearing or understanding an Officer\'s order. Important for evaluating Force Allegations.',
			'FldNotes' => 'Only for Use of Force Orders. If users indicate that there was trouble hearing order, should we show this field asking Why [victim] had trouble understanding order?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 312,
			'FldDatabase' => '1',
			'FldTable' => '120',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Description',
			'FldEng' => 'Order Description',
			'FldDesc' => 'Narrative account of an Officer\'s Order. Important for evaluating certain Allegations.',
			'FldNotes' => 'As best you can recall, what were the exact words and tone the Officers used when making this order?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 314,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'StatedReasonDesc',
			'FldEng' => 'Stated Stop Reason Description',
			'FldDesc' => 'Narrative description of the Officer\'s stated reason for stopping any Civilians related to this Stop. Might provide additional information for evaluating any Allegations related to this Stop.',
			'FldNotes' => 'As best you can remember, what were the exact words this Officer used to explain why you were stopped? ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 315,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectAskedToLeave',
			'FldEng' => 'Subject(s) Asked to Leave?',
			'FldDesc' => 'Indicates whether or not this Subject asked the Officer if he/she was free to go during the Stop. Doing this indicates that a Stop is no longer voluntary. This is important information for evaluating certain Allegations related to this stop.',
			'FldNotes' => 'http://www.flexyourrights.org/faqs/how-long-can-police-detain-you/',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 316,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectStatementsDesc',
			'FldEng' => 'Subject Statements Description',
			'FldDesc' => 'Narrative account of what Subject said to the Officer during the Stop. This is important for evaluating any Allegations related to this Stop.',
			'FldNotes' => 'As best you can remember, what were the exact words this Subject said to this Officer? ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 317,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'RefuseID',
			'FldEng' => 'Did you refuse to provide ID?',
			'FldDesc' => 'Indicates whether or not a Civilian refused to provide ID to an Officer. Important for identifying patterns of excessive ID requests and retaliatory policing. ',
			'FldNotes' => 'http://www.flexyourrights.org/faqs/when-can-police-ask-for-id/',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 318,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectHandcuffed',
			'FldEng' => 'Subject Handcuffed?',
			'FldDesc' => 'Indicates whether or not this Subject was handcuffed by an Officer during this Stop. Important information for identifying Department patterns of excessive handcuffing during Stops.',
			'FldNotes' => 'San Jose IPA is trying to limit this common practice.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 319,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectFrisk',
			'FldEng' => 'Subject Frisk?',
			'FldDesc' => 'Indicates whether or not this Subject was patted down by an Officer during this Stop. This is important information for evaluating certain Allegations related to this stop. Also important for identifying patterns of excessive pat downs.',
			'FldNotes' => 'Related Article
http://www.flexyourrights.org/faqs/what-is-reasonable-suspicion/

There was at one point a copy of this field in Searches. We deleted it because frisks are not technically searches and are generally legally permissible as part of a Stop.



Q. Do we want mere frisks to trigger wrongful search Allegations? Because frisks alone are almost always legally permissible, this might be Allegation overkill.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 320,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '20',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '938',
			'FldName' => 'TimeStart',
			'FldEng' => 'Start Time',
			'FldDesc' => 'The date, hour, and minute when a single Stop begins. This information is vital for verifying a Stop occurrence and evaluating its legal legitimacy.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 321,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '21',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '939',
			'FldName' => 'TimeEnd',
			'FldEng' => 'End Time',
			'FldDesc' => 'The date, hour, and minute when a single Stop ends. This information is vital for verifying a Stop occurrence and evaluating its legal legitimacy.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 322,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '22',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '940',
			'FldName' => 'Duration',
			'FldEng' => 'Duration',
			'FldDesc' => 'The total calculated minutes of of a single Stop. This information is vital for verifying a Stop occurrence and evaluating its legal legitimacy.',
			'FldNotes' => 'Calculated based on Stop Start Time and Stop End Time. Instead of capturing this information within the Stop, this is found in the duration of the Incident for Complaints with a Stop but no Arrest.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '5',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 325,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'StatedReason',
			'FldEng' => 'Officer stated reason for search?',
			'FldDesc' => 'Indicates whether or not the Officer gave a reason for this Search. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 326,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'StatedReasonDesc',
			'FldEng' => 'Stated reason description',
			'FldDesc' => 'The specific reason the Officer gave for conducting this Search. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldNotes' => 'As best you can remember, what were the exact words this Officer said before conducting this search?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 327,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerRequest',
			'FldEng' => 'Officer request permission to search?',
			'FldDesc' => 'Indicates whether or not the Officer asked the Subject for permission to Search their person or property. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 328,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerRequestDesc',
			'FldEng' => 'Officer request description',
			'FldDesc' => 'The specific words and tone the Officer used to solicit consent to search. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldNotes' => 'What were the exact words and tone this Officer used to request consent to search? (Search requests often sound a lot like commands.)',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 329,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectConsent',
			'FldEng' => 'Did subject consent to search?',
			'FldDesc' => 'Indicates whether or not the Subject gave the Officer permission to Search their person or property. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 330,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectSay',
			'FldEng' => 'What did subject say?',
			'FldDesc' => 'Narrative account of specific words a Subject used to consent to the search. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldNotes' => 'What were the exact words and tone [the Subject] used to give consent to the Officer?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 331,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerThreats',
			'FldEng' => 'Officer make threats or lie to get consent to search?',
			'FldDesc' => 'Indicates whether or not an Officer made any stated or implied threats to the Subject to obtain consent to search Subject\'s person or property. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 332,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerThreatsDesc',
			'FldEng' => 'Officer threat description',
			'FldDesc' => 'The specific words the Officer used to trick Subject into consenting to Search. Important for evaluating possible wrongful search Allegation and identifying Search trends.',
			'FldNotes' => 'What were the exact words this Officer used to threaten or trick the Subject into giving consent to search?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 333,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'K9sniff',
			'FldEng' => 'K9 (Dog) sniff?',
			'FldDesc' => 'Indicates whether or not an Officer had a K9 (Dog) sniff a Subject or their property. Important for identifying possible wrongful search Allegation and identifying K9 (Dog) search trends.',
			'FldNotes' => 'Q. Do we want mere K9 sniffs to trigger wrongful search Allegations? Because K9 sniffs alone are almost always legally permissible, this might be Allegation overkill.',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 335,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'Strip',
			'FldEng' => 'Strip Searched?',
			'FldDesc' => 'Indicates whether or not an Officer performed a strip search on a Subject. Important for evaluating possible wrongful search Allegation and identifying search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 341,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'ContrabandDiscovered',
			'FldEng' => 'Contraband discovered?',
			'FldDesc' => 'Indicates whether or not illegal items were discovered during a Search. Important for identifying search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 343,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerWarrant',
			'FldEng' => 'Officer have warrant?',
			'FldDesc' => 'Indicates whether or not an Officer had a warrant for a Search. Important for evaluating possible wrongful search Allegations and for identifying search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 344,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerWarrantSay',
			'FldEng' => 'What did warrant say?',
			'FldDesc' => 'The words on the official warrant. Important for evaluating possible wrongful search Allegations.',
			'FldNotes' => 'User may type actual words on the warrant. Or they may upload a photograph or scan of the warrant as Evidence.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 348,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'StatedReason',
			'FldEng' => 'Officer state reason for arrest?',
			'FldDesc' => 'Indicates whether or not an Officer stated a reason for Arresting a Civilian. Important for evaluating wrongful arrest Allegations.',
			'FldNotes' => 'Did [the officer] verbally state a reason why he arrested [this Subject]?  ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 349,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'StatedReasonDesc',
			'FldEng' => 'Stated reason for arrest',
			'FldDesc' => 'The specific reason an Officer gave for making an arrest. Important for evaluating wrongful arrest Allegations.',
			'FldNotes' => 'Before the arrest happened, what specific reasons did [the officer] give for arresting [this Subject]?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 352,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'SITA',
			'FldEng' => 'Was this a Search Incident To Arrest? (Admin)',
			'FldDesc' => 'Indicates whether or not Administrators determine that an Officer searched a Subject after an Arrest. Important for tracking searches incident to arrest. ',
			'FldNotes' => 'Commonly known as Search Incident To Arrest (SITA) or the Chimel rule, is a legal principle that allows police to perform a warrantless search of an arrested person, and the area within the arrestee--s immediate control, in the interest of officer safety, the prevention of escape, and the destruction of evidence.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 357,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'ChargesOther',
			'FldEng' => 'Arrest Charges Other (not in list)',
			'FldDesc' => 'Formal Arrest charges received. Important for tracking arrest trends and for evaluating wrongful arrest Allegations. ',
			'FldNotes' => 'Only reveal if user selects "Other" category under "Arrest Charges."

Please write what you were charged with. ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 369,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'NoChargesFiled',
			'FldEng' => 'Were all the charges dropped before they were released?',
			'FldDesc' => 'Indicates that Subject was placed under Arrest, but no official charges were filed. Important for identifying and evaluating wrongful arrest Allegations.',
			'FldNotes' => 'Check with Attorney for precise terminology and common usage.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 370,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '927',
			'FldName' => 'IncidentID',
			'FldEng' => 'Incident ID',
			'FldDesc' => 'The unique number of the Incident record related to this Complaint. This number helps us identify the Incident that this complaint documents. It might also help us associate additional Complaints with the same Incident.',
			'FldNotes' => 'More than one Complaint record can be associated with a single Incident record.',
			'FldForeignTable' => '114',
			'FldForeignMax' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 371,
			'FldDatabase' => '1',
			'FldTable' => '113',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Allegation, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 373,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Civilian\'s record. Vital for associating this Civilian with other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '11',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 375,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Incident, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => 'N',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 386,
			'FldDatabase' => '1',
			'FldTable' => '113',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Allegation Type',
			'FldDesc' => 'The specific misconduct accusations cited by Complainants against one or more Officers related to a single Incident. Essential for statistical purposes and for prioritizing new Complaints. ',
			'FldNotes' => 'Asked at the end during Phase 4: Policy or Procedure, Courtesy, Intimidating Display Of Weapon, Sexual Assault, Conduct Unbecoming an Officer (Neglect of Duty is part of Policy/Procedure)',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Allegation Type',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldNullSupport' => '0',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 387,
			'FldDatabase' => '1',
			'FldTable' => '113',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Findings',
			'FldEng' => 'Allegation Findings',
			'FldDesc' => 'The official final disposition of an Allegation. Important for tracking responsiveness of Police Departments, Oversight Agencies, and Complainants.',
			'FldNotes' => 'Final Allegation status is categorized by Admins after reviewing official department documentation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Allegation Findings',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 393,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'Privacy',
			'FldEng' => 'Privacy',
			'FldDesc' => 'User-selected category for Complaint records. This defines what personally identifiable information (PII) is publicly shared.',
			'FldNotes' => 'Should we move privacy settings at the end of the Complaint process for now (in the Simulation/Implementation)? (We can later test whether users prefer this selection to appear earlier or later in the process.)

Wikipedia Article on the topic: https://en.wikipedia.org/wiki/Personally_identifiable_information',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Privacy Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 396,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldSpecSource' => '0',
			'FldName' => 'IsVerified',
			'FldEng' => 'Is Verified Officer Record?',
			'FldDesc' => 'This marks the verified record of an Verified Officer Record with the most accurate and current information. This is vital for tracking Officers involved in multiple Incidents over time.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldValues' => '0;1',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 397,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Role',
			'FldEng' => 'Role in Incident',
			'FldDesc' => 'Indicates category of an Officer. This information is essential for understanding whether a given Officer was a Subject of an Allegation or a Witness to the Allegation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Subject Officer;Witness Officer',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 401,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '28',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '929',
			'FldName' => 'AdminID',
			'FldEng' => 'Primary Admin ID',
			'FldDesc' => 'The unique number of the Administrator record related to this Complaint. This number helps us identify which Administrator is the point of contact for this Complaint.',
			'FldNotes' => 'OPC staff in charge of Complaint ',
			'FldForeignTable' => '108',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 404,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Miranda',
			'FldEng' => 'Did the officer read your Miranda rights?',
			'FldDesc' => 'Indicates whether or not an Officer read a Subject their Miranda rights during Arrest. Important for evaluating possible procedure Allegation.',
			'FldNotes' => 'Failure to read subject Miranda rights turns on a "procedure" violation, and a value of Yes here is equivalent to an Allegation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 405,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Status',
			'FldEng' => 'OPC Complaint Status',
			'FldDesc' => 'The current progress of a "complete" or "incomplete" Complaint within the OPC system. We use this information internally to determine next Administrator actions to guide a Complaint to the final status of "closed."',
			'FldNotes' => 'AKA Disposition of Complaint. <a href="extras.php?flows=1" target="_blank" class="f12">see Work Flows</a>.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Complaint Status',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 416,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to this Oversight Agency. This number allows Oversight Agency contacts to log into the OPC system.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 418,
			'FldDatabase' => '1',
			'FldTable' => '108',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to an Administrator\'s record. This number allows Administrators to log into the OPC system.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 419,
			'FldDatabase' => '1',
			'FldTable' => '110',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to a Customer\'s record. This number allows Customers to log into the OPC system.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 422,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldSpecSource' => '0',
			'FldName' => 'Name',
			'FldEng' => 'Department Name',
			'FldDesc' => 'The official name of the law enforcement agency. This is an essential component of a Police Department\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 430,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'TotOfficers',
			'FldEng' => 'Total Number of Employees',
			'FldDesc' => 'The total number of sworn Officers employed by a Police Department. This information will be used for comparative statistical purposes and to determine the most likely Department on-scene during a particular Incident.',
			'FldNotes' => 'For example, if a User doesn\'t know which Police Department employed a given Subject Officer, the field list for Police Departments would begin by listing the local department with the highest number of total sworn officers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOperateSame' => '10010',
			'FldOperateOther' => '10010',
			'FldOperateValue' => '10010'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 431,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'JurisdictionPopulation',
			'FldEng' => 'Jurisdiction Population',
			'FldDesc' => 'The total number of people who live within the geographical boundaries of a Police Department. This information will help identify policing trends and for comparative statistical purposes.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6',
			'FldOperateSame' => '10010',
			'FldOperateOther' => '10010',
			'FldOperateValue' => '10010'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 432,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'JurisdictionGPS',
			'FldEng' => 'Jurisdiction GPS',
			'FldDesc' => 'A series of GPS coordinates, representing the geographical polygon of a department\'s jurisdiction. Important for identifying the Police Department attached to a Complaint -- especially when Complainants are unsure.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 457,
			'FldDatabase' => '1',
			'FldTable' => '125',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to this Privilege Profile, vital to associating permissions with a specific System User.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 458,
			'FldDatabase' => '1',
			'FldTable' => '125',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Privilege Profile, vital to associating permissions with a specific Complaint (instead of an entire Department).',
			'FldForeignTable' => '112',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 479,
			'FldDatabase' => '1',
			'FldTable' => '126',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to this Action, vital for tracking System User behavior and system security.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 480,
			'FldDatabase' => '1',
			'FldTable' => '126',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '957',
			'FldName' => 'Timestamp',
			'FldEng' => 'Timestamp',
			'FldDesc' => 'The date and time of an Administrator\'s specific Action affecting system data, vital for tracking System User behavior and potentially providing security validation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NOW()',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 481,
			'FldDatabase' => '1',
			'FldTable' => '126',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Table',
			'FldEng' => 'Table Edited',
			'FldDesc' => 'Name of the table whose contents are being altered by a System User (usually an Administrator), at any point after a Complaint has been submitted. This is important tracking the table being altered.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 482,
			'FldDatabase' => '1',
			'FldTable' => '126',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'RecordID',
			'FldEng' => 'Table Record ID',
			'FldDesc' => 'The unique primary ID number of the record in the table whose contents are being altered by a System User (usually an Administrator), at any point after a Complaint has been submitted. This is important tracking the specific record being altered.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 483,
			'FldDatabase' => '1',
			'FldTable' => '126',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'OldData',
			'FldEng' => 'Old Data Values',
			'FldDesc' => 'A data dump of the initial value of all the table record\'s fields\' contents, or at least those with values being changed by a System User. This is vital to provide breadcrumbs to correct potential problems.',
			'FldNotes' => 'Data formatting TBD, but could be like JSON or XML.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 484,
			'FldDatabase' => '1',
			'FldTable' => '126',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'NewData',
			'FldEng' => 'New Data Values',
			'FldDesc' => 'A data dump of the newly updated values of all the table record\'s fields\' contents, or at least those with values being changed by a System User. This is vital to provide breadcrumbs to correct potential problems.',
			'FldNotes' => 'Data formatting TBD, but could be like JSON or XML.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 485,
			'FldDatabase' => '1',
			'FldTable' => '133',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Action',
			'FldEng' => 'Login Action',
			'FldDesc' => 'The type of login activity being tracked, vital for behavior logging and potentially providing security validation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Successful Login;Unsuccessful Login Attempt(s);Logout',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 486,
			'FldDatabase' => '1',
			'FldTable' => '133',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '957',
			'FldName' => 'Timestamp',
			'FldEng' => 'Timestamp',
			'FldDesc' => 'The date and time of a System User login, vital for tracking System User behavior and potentially providing security validation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NOW()',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 487,
			'FldDatabase' => '1',
			'FldTable' => '133',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to this Login activity, vital for tracking System User behavior and system security.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 488,
			'FldDatabase' => '1',
			'FldTable' => '133',
			'FldOrd' => '4',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '951',
			'FldName' => 'IP',
			'FldEng' => 'IP Address',
			'FldDesc' => 'The encrypted IP address address of the login (attempts) to this System User\'s account, vital for security precautions and protections.',
			'FldNotes' => 'Perhaps non-Civilian Users\' IP addresses should not be encrypted.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '50',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 489,
			'FldDatabase' => '1',
			'FldTable' => '133',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'TotAttempts',
			'FldEng' => 'Number of Login Attempts',
			'FldDesc' => 'The number of login attempts made before successfully logging in, or without success, vital for security precautions and protections.',
			'FldNotes' => 'Number of attempts within some time frame, TBD.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 501,
			'FldDatabase' => '1',
			'FldTable' => '125',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '920',
			'FldName' => 'DeptID',
			'FldEng' => 'Department ID',
			'FldDesc' => 'The unique number of the Department record related to this Privilege Profile, vital to associating permissions with all Complaints tied to a Police Department (instead of a single Complaint).',
			'FldForeignTable' => '111',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 502,
			'FldDatabase' => '1',
			'FldTable' => '125',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'AccessLevel',
			'FldEng' => 'Data Access Level',
			'FldDesc' => 'The permission specifications for this record\'s User\'s access to either one Complaint or one Department\'s Complaints. This is vital for determining exactly what privacy rules apply for a given User attempting to access a given Complaint.',
			'FldNotes' => 'Details TBD, with approximate categories suggested for now.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'View w/out Names;View;Edit;Manage',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 516,
			'FldDatabase' => '1',
			'FldTable' => '128',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Survey record, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 519,
			'FldDatabase' => '1',
			'FldTable' => '128',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'AuthUserID',
			'FldEng' => 'User Authentication ID',
			'FldDesc' => 'The unique number of the System User record logged in while completing this Survey record. Important for tracking which User is providing this feedback on the OPC system.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 532,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '11',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '837',
			'FldName' => 'Email',
			'FldEng' => 'Email',
			'FldDesc' => 'The valid email address provided by or for a person. This is the primary way we communicate with them.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => 'AAA@AAA.AAA',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 533,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '12',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '838',
			'FldName' => 'PhoneHome',
			'FldEng' => 'Home Phone',
			'FldDesc' => 'The contact number where a person can be reached at their home. This is usually a landline phone, which cannot receive text messages.',
			'FldNotes' => 'At least one phone number -- be it Home, Work, or Mobile -- should be required for public complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 534,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '13',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '839',
			'FldName' => 'PhoneWork',
			'FldEng' => 'Work Phone',
			'FldDesc' => 'The contact number where persons can be reached at their place of business. This is usually a landline phone, which cannot receive text messages.',
			'FldNotes' => 'We might need to add an "extension" field for some work phone numbers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 535,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '14',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '840',
			'FldName' => 'PhoneMobile',
			'FldEng' => 'Mobile Phone',
			'FldDesc' => 'The contact number where persons can be reached on their cellular device. We may use this number for both text and voice communications.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 536,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '15',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '841',
			'FldName' => 'Address',
			'FldEng' => 'Street Address',
			'FldDesc' => 'The first line of the postal location where a person resides or is able to receive mail. This is an integral component of a person\'s complete address.',
			'FldNotes' => 'If they can\'t reliable receive mail at residence, use reliable mailing address instead.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 537,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '16',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '842',
			'FldName' => 'Address2',
			'FldEng' => 'Street Address 2',
			'FldDesc' => 'The second line, if needed, of the postal location where a person resides or is able to receive mail. This is an integral component of a person\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 538,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '17',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '843',
			'FldName' => 'AddressCity',
			'FldEng' => 'City',
			'FldDesc' => 'The metropolitan area where a person resides or is able to receive mail. This is an integral component of a person\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 539,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '18',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '844',
			'FldName' => 'AddressState',
			'FldEng' => 'State',
			'FldDesc' => 'The state or district where a person resides or is able to receive mail. This is an integral component of a person\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '2',
			'FldCharSupport' => ',Letters,',
			'FldInputMask' => 'AA',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 540,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '19',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '845',
			'FldName' => 'AddressZip',
			'FldEng' => 'Zip Code',
			'FldDesc' => 'The postal code where a person resides or is able to receive mail. This is an integral component of a person\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => '#####[-####]',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 541,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '21',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '847',
			'FldName' => 'Facebook',
			'FldEng' => 'Facebook',
			'FldDesc' => 'This is the URL address of a person\'s Facebook page. This is used to help verify their identities and for communicating with them if they select this as a preferred method of contact.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => 'http://facebook.com/AAAAAA',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 568,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '22',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '837',
			'FldName' => 'Email',
			'FldEng' => 'Email',
			'FldDesc' => 'The valid email address for an Oversight Agency contact. This is the primary way we communicate with them.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => 'AAA@AAA.AAA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 570,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '23',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '839',
			'FldName' => 'PhoneWork',
			'FldEng' => 'Work Phone',
			'FldDesc' => 'The contact number where Oversight Agency contact can be reached.',
			'FldNotes' => 'We might need to add an "extension" field for some work phone numbers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 572,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '24',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '841',
			'FldName' => 'Address',
			'FldEng' => 'Street Address',
			'FldDesc' => 'The first line of the postal location where an Oversight Agency contact receive mail. This is an integral component of an Oversight Agency contact\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 573,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '25',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '842',
			'FldName' => 'Address2',
			'FldEng' => 'Street Address 2',
			'FldDesc' => 'The second line, if needed, of the postal location where an Oversight Agency contact receives mail. This is an integral component of an Oversight Agency contact\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 574,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '26',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '843',
			'FldName' => 'AddressCity',
			'FldEng' => 'City',
			'FldDesc' => 'The metropolitan area where an Oversight Agency contact receives mail. This is an integral component of an Oversight Agency contact\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 575,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '27',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '844',
			'FldName' => 'AddressState',
			'FldEng' => 'State',
			'FldDesc' => 'The state or district where an Oversight Agency contact receives mail. This is an integral component of an Oversight Agency contact\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '2',
			'FldCharSupport' => ',Letters,',
			'FldInputMask' => 'AA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 576,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '28',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '845',
			'FldName' => 'AddressZip',
			'FldEng' => 'Zip Code',
			'FldDesc' => 'The postal code where an Oversight Agency contact receives mail. This is an integral component of an Oversight Agency contact\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => '#####[-####]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 616,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '837',
			'FldName' => 'Email',
			'FldEng' => 'Email',
			'FldDesc' => 'The valid public-facing email address for Police Departments. If we don\'t have a direct email contact with an Internal Affairs Staffer, this will be the primary way we contact Police Departments.',
			'FldNotes' => 'This is the email that would be featured on their OPC web page for this Department. We might include this in addition to a general Internal Affairs email contact, if one exists.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => 'AAA@AAA.AAA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 618,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '6',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '839',
			'FldName' => 'PhoneWork',
			'FldEng' => 'Work Phone',
			'FldDesc' => 'The public contact number where Police Departments can be reached.',
			'FldNotes' => 'We might need to add an "extension" field for some work phone numbers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 620,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '7',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '841',
			'FldName' => 'Address',
			'FldEng' => 'Street Address',
			'FldDesc' => 'The first line of the postal location where a Police Department receives mail. This is an integral component of a Police Department\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 621,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '8',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '842',
			'FldName' => 'Address2',
			'FldEng' => 'Street Address 2',
			'FldDesc' => 'The second line, if needed, of the postal location where a Police Department receives mail. This is an integral component of a Police Department\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 622,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '9',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '843',
			'FldName' => 'AddressCity',
			'FldEng' => 'City',
			'FldDesc' => 'The metropolitan area where a Police Department receives mail. This is an integral component of a Police Department\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 623,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '10',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '844',
			'FldName' => 'AddressState',
			'FldEng' => 'State',
			'FldDesc' => 'The state or district where a Police Department receives mail. This is an integral component of a Police Department\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '2',
			'FldCharSupport' => ',Letters,',
			'FldInputMask' => 'AA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 624,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '11',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '845',
			'FldName' => 'AddressZip',
			'FldEng' => 'Zip Code',
			'FldDesc' => 'The postal code where a Police Department receives mail. This is an integral component of a Police Department\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => '#####[-####]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 628,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '865',
			'FldName' => 'Gender',
			'FldEng' => 'Gender',
			'FldDesc' => 'The sex classification of a Civilian or Officer. This category is used to help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'Depending on who is selecting, this might be self-ascribed or Complainant-selected for another Civilian. Question should read, "What is the gender of the subject of the alleged misconduct?" Options should be Male, Female, and Other (please specify)',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'M;F;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 629,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '868',
			'FldName' => 'Age',
			'FldEng' => 'Age',
			'FldDesc' => 'Estimated age in years of a Civilian or Officer. This information is used to verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'Because the "Date of Birth" field will determine Complainant\'s age. This field should become visible only if a Civilian\'s age is unknown. The "Harmonised Standard 3" age ranges presented here: https://meta.wikimedia.org/wiki/Survey_best_practices#Age',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Age Ranges',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 632,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '4',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '877',
			'FldName' => 'Height',
			'FldEng' => 'Height',
			'FldDesc' => 'Estimated height of a Civilian or Officer in feet and inches. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'We should use a tool that indicates feet and inches (not just total inches.) Most people wouldn\'t describe someone as 72 inches. They would say 6 feet. ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 637,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '6',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '902',
			'FldName' => 'HairDescription',
			'FldEng' => 'Hair Description',
			'FldDesc' => 'The color, style, texture -- or other attributes of a Civilian or Officer\'s hair. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'Please describe this person\'s hair color, style, texture, and length. If they are bald or partially-bald, please describe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 639,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '7',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '904',
			'FldName' => 'HairFacialDesc',
			'FldEng' => 'Facial Hair Description',
			'FldDesc' => 'The attributes of a male Civilian or Officer\'s facial hair. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'The UX should start with a Y/N input before requiring description, if civilian is male. Did this person have a beard, mustache, sideburns, or goatee? Please describe their facial hair.NOTE: This selection should ONLY appear for male Civilians.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 640,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '8',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '905',
			'FldName' => 'Eyes',
			'FldEng' => 'Eyes Description',
			'FldDesc' => 'The attributes of a Civilian or Officer\'s eyes, including eyewear. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'Please describe this person\'s eyes. What color were they? Did they wear glasses? Did they have a distinctive shape? Please describe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 643,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '9',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '908',
			'FldName' => 'DistinguishingMarksDesc',
			'FldEng' => 'Distinguishing Marks Description',
			'FldDesc' => 'The attributes of any prominent physical characteristics on a Civilian or Officer. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'The UX should start with a Y/N input before requiring description. For example, does this person have any scars, marks, tattoos, or any other prominent physical characteristics? Please describe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 644,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '10',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '909',
			'FldName' => 'VoiceDesc',
			'FldEng' => 'Voice Description',
			'FldDesc' => 'The attributes of a Civilian or Officer\'s speech. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'For example, does this person have a high or low voice. Did they speak with an accent? Please describe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 645,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '11',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '910',
			'FldName' => 'ClothesDesc',
			'FldEng' => 'Clothes/Uniform Description',
			'FldDesc' => 'The attributes of a Civilian or Officer\'s attire. This information might help verify a Civilian or Officer\'s identity.',
			'FldNotes' => 'What was this person wearing? Please describe the color, material, style, etc.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 647,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '12',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '912',
			'FldName' => 'DisabilitiesDesc',
			'FldEng' => 'Disabilities Description',
			'FldDesc' => 'The attributes of a Civilian\'s physical or mental handicaps (not for Officers). This information might help verify a Civilian\'s identity.',
			'FldNotes' => 'The UX should start with a Y/N input before requiring description. For example, does this person use a wheelchair? Have any amputations? Mental impairments? Please describe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 648,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '913',
			'FldName' => 'Transportation',
			'FldEng' => 'Transportation',
			'FldDesc' => 'Indicates whether or not Civilian was in or near their vehicle during Incident. This information might help verify a Civilian\'s identity.',
			'FldNotes' => 'Subject with Vehicles are the driver during Vehicle Stops. Subjects without Vehicles during Vehicle Stops are passengers. ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Transportation Officer',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 649,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '914',
			'FldName' => 'VehicleMake',
			'FldEng' => 'Vehicle Make',
			'FldDesc' => 'The company that manufactured a Civilian\'s vehicle. This information might help verify a Civilian\'s identity.',
			'FldNotes' => 'Vehicle makes include Toyota, Ford, Honda, Chevrolet, etc.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 650,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '915',
			'FldName' => 'VehicleModel',
			'FldEng' => 'Vehicle Model',
			'FldDesc' => 'The name or brand of the Civilian\'s vehicle. This information might help verify a Civilian\'s identity.',
			'FldNotes' => 'Vehicle models include Toyota Camry, Ford F-Series, Chevrolet Silverado, etc.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 651,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldOrd' => '4',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '916',
			'FldName' => 'VehicleDesc',
			'FldEng' => 'Vehicle Description',
			'FldDesc' => 'Additional attributes of the Civilian\'s vehicle. This information might help verify a Civilian\'s identity.',
			'FldNotes' => 'What color was the vehicle? Did it have any distinguishing characteristics, such as dents, customizations, etc?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 652,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '917',
			'FldName' => 'VehicleLicence',
			'FldEng' => 'Vehicle Licence Plate',
			'FldDesc' => 'The tag numbers on the Civilian\'s vehicle. This information might help verify a Civilian\'s identity.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 706,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'OPC Complaint Type',
			'FldDesc' => 'The Administrator-selected category for newly-submitted Complaint records. Essential for determining where and how new Complaint records are stored and shared.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::OPC Staff/Internal Complaint Type',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 707,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'OversightReportEvidenceID',
			'FldEng' => 'Oversight Investigative Report',
			'FldDesc' => 'The Evidence record storing the final determination letter submitted by the Oversight Agency regarding an OPC-submitted Complaint. This report provides essential information for tracking how Oversight Agencies respond to citizen complaints.',
			'FldNotes' => 'Complainants must scan these reports and upload them to their Complaint as Evidence.',
			'FldForeignTable' => '119',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 769,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Officer\'s record. Vital for associating Officer with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '11',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 771,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '4',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '926',
			'FldName' => 'Verified',
			'FldEng' => 'Contact Info Last Verified',
			'FldDesc' => 'Indicates the date and time when Police Department contact information was last verified. This is crucial for keeping track of the accuracy of Police Department contact information.',
			'FldNotes' => 'NULL value means unverified.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 772,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '926',
			'FldName' => 'Verified',
			'FldEng' => 'Contact Info Last Verified',
			'FldDesc' => 'Indicates the date and time when an Oversight Agency\'s Police contact information was last verified. This is crucial for keeping track of the accuracy of Oversight Agency\'s contact information.',
			'FldNotes' => 'NULL value means unverified.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 773,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'Investigated',
			'FldEng' => 'Investigated by Oversight Agency',
			'FldDesc' => 'Indicates the date and time when the Complainant was contacted by an Oversight Agency contact regarding the status of their Complaint or to investigate any Allegations contained in the Complaint.',
			'FldNotes' => 'Complainants must provide OPC this information. So we must send Complainants regular follow up emails to track Oversight Agency response rates and response times.',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NULL',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,Keyboard,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 776,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '30',
			'FldSpecSource' => '0',
			'FldName' => 'Notes',
			'FldEng' => 'Complaint Notes',
			'FldDesc' => 'Additional annotations related to this Complaint. Might add additional information or context regarding the Complaint.',
			'FldNotes' => 'Changed to "Complaint Notes"',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 777,
			'FldDatabase' => '1',
			'FldTable' => '136',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Complaint Note. Vital for associating Complaint record to Complaint Note data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 778,
			'FldDatabase' => '1',
			'FldTable' => '136',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to this Complaint Note. This is vital to tracking which system user provide which new information and Evidence to a Complaint.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 779,
			'FldDatabase' => '1',
			'FldTable' => '136',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '957',
			'FldName' => 'Timestamp',
			'FldEng' => 'Timestamp',
			'FldDesc' => 'The date and time when a Note was added to a Complaint. This is vital to tracking the chronology of new updates, information, and/or Evidence appended to a completed Complaint.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NOW()',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 780,
			'FldDatabase' => '1',
			'FldTable' => '136',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Content',
			'FldEng' => 'Note Content',
			'FldDesc' => 'The main text/body of a Note being added to a Complaint, providing new information or descriptive updates of the Complaint\'s status. This is important as an open-ended tool to add extra information about a Complaint.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 782,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'CCTV',
			'FldEng' => 'Any Closed Circuit Cameras on Scene?',
			'FldDesc' => 'Indicates that there was possible video surveillance of the Scene. Important for obtaining new Evidence of the Incident.',
			'FldNotes' => 'Go back to the scene to check for CCTV cameras to chase down potential video Evidence.

If selected, we might want to email user article on How to Track Down CCTV Footage, or something like that.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 783,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'CCTVDesc',
			'FldEng' => 'Closed Circuit Camera Descriptions',
			'FldDesc' => 'Narrative details about the type and location of any visible video surveillance cameras. Might help Oversight Agencies or Attorneys track down new Evidence of the Incident.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 785,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '12',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '918',
			'FldName' => 'CameraRecord',
			'FldEng' => 'Recorded Event?',
			'FldDesc' => 'Indicates whether or not this Civilian recorded the Incident. This information might help us track down new video Evidence.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 786,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'BodyCam',
			'FldEng' => 'Officer Wearing On-body Camera?',
			'FldDesc' => 'Indicates whether or not the Officer was wearing a body-mounted camera. Essential for obtaining possible video Evidence of the Incident.',
			'FldNotes' => 'We will need to create document with tips on obtaining dash-cam and on-body camera footage.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 788,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '33',
			'FldSpecSource' => '0',
			'FldName' => 'SubmissionProgress',
			'FldEng' => 'Submission Progress',
			'FldDesc' => 'Indicates current progress of an "incomplete" Complaint. Important for identifying problem areas which might cause Complaints to be left unfinished.',
			'FldNotes' => 'TBD: [Shorthand] notation of where in the complaint submission process. Probably both an overall section identifier (like Turbo Tax), and sub-section identifier.',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 789,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'Summary',
			'FldEng' => 'Summary (Narrative)',
			'FldDesc' => 'The Complainant narrative describes the chronological sequence of key Incident events and Allegations. This story brings to life to an otherwise clinical and legalistic Complaint document.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 791,
			'FldDatabase' => '1',
			'FldTable' => '120',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '959',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The unique number of the Incident Event record related to this Order record. This is important for associating this Order with a Stop, Search, Use of Force, or Arrest.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 804,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '842',
			'FldName' => 'Address2',
			'FldEng' => 'Street Address 2',
			'FldDesc' => 'The second line, if needed of the postal location at or near where an Incident occurred. This is an integral component of a complete Incident location address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 805,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '843',
			'FldName' => 'AddressCity',
			'FldEng' => 'City',
			'FldDesc' => 'The metropolitan area where an Incident occurred. This is an integral component of a complete Incident location address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldRequired' => '1',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 806,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '4',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '844',
			'FldName' => 'AddressState',
			'FldEng' => 'State',
			'FldDesc' => 'The state or district where an Incident occurred. This is an integral component of a complete Incident location address.',
			'FldNotes' => 'Incident locations should only include locations within the 50 U.S. states and the District of Columbia.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '2',
			'FldCharSupport' => ',Letters,',
			'FldInputMask' => 'AA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldRequired' => '1',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 807,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '845',
			'FldName' => 'AddressZip',
			'FldEng' => 'Zip Code',
			'FldDesc' => 'The postal code where an Incident occurred. This is an integral component of a complete Incident location address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => '#####[-####]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 808,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'AddressLat',
			'FldEng' => 'Geolocation Latitude',
			'FldDesc' => 'The geographic coordinate that specifies the north-south position of the point on the Earth\'s surface where an Incident occurred. This information might help identify Police Departments whose jurisdictions cover the coordinates where an Incident occurred.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DOUBLE',
			'FldDataLength' => '0',
			'FldDataDecimals' => '10',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 809,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'AddressLng',
			'FldEng' => 'Geolocation Longitude',
			'FldDesc' => 'The geographic coordinate that specifies the east-west position of the point on the Earth\'s surface where an Incident occurred. This information might help identify Police Departments whose jurisdictions cover the coordinates where an Incident occurred.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DOUBLE',
			'FldDataLength' => '0',
			'FldDataDecimals' => '10',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 810,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Type of Department',
			'FldDesc' => 'The general category of a particular law enforcement agency. This information helps us identify the responsibilities and jurisdiction of a given Police Department. ',
			'FldNotes' => 'Detailed Google Doc: https://docs.google.com/document/d/1OZBZYd8V7gC3es46Z5sWrfu2IRvhrK1CkfV8vKp8xgY/edit?usp=sharing',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Types of Departments',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 812,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '22',
			'FldSpecSource' => '0',
			'FldName' => 'IntimidatingWeapon',
			'FldEng' => 'Intimidating Display Of Weapon?',
			'FldDesc' => 'The Officer brandished or discharged a weapon in threatening manner. Important for identifying non-Force complaints where an Officer might have used a weapon for intimidation purposes.',
			'FldNotes' => 'IF this Complaint includes a Use of Force Allegation, THEN the Complainant should NOT be offered this Weapon Intimidation Allegation.

Weapon-related threats do not apply to weapons safely in holsters. Actual use of weapon AGAINST a person is use of Force, not just Intimidation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Intimidating Displays Of Weapon',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 815,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '23',
			'FldSpecSource' => '0',
			'FldName' => 'IntimidatingWeaponType',
			'FldEng' => 'If Intimidating Display of Weapon, What Weapon?',
			'FldDesc' => 'The category of weapon the Officer used in a threatening manner. Important for identifying whether an Officer might have violated Department policies or procedures.',
			'FldNotes' => 'Range of Values is a subset of Force Type, which can be used for intimidation. Hide Force Type Definitions: Control Hold, Body Weapons, Takedown, Vehicle',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Force Type',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 816,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'SubjectHandcuffInjury',
			'FldEng' => 'Handcuff Injury ID',
			'FldDesc' => 'Indicates whether or not the Subject reported any Injuries related to an Officer\'s misuse of handcuffs. Important for connecting with possible Use of Force.',
			'FldNotes' => 'Injuries from handcuffs should only link to an Allegation of Excessive Force if and only if the Complainant provides Evidence of Injury.

NULL if No',
			'FldForeignTable' => '117',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 817,
			'FldDatabase' => '1',
			'FldTable' => '137',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this Incident Event, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 818,
			'FldDatabase' => '1',
			'FldTable' => '137',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Order',
			'FldEng' => 'Event Order',
			'FldDesc' => 'The is the chronological rank of events within an incident, identifying the order in which they occurred. This is vital to presenting the Stops, Searches, Uses of Force, and Arrests in the order they occurred during the Incident.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldCompareSame' => '6',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 823,
			'FldDatabase' => '1',
			'FldTable' => '113',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Description',
			'FldEng' => 'Allegation Description',
			'FldDesc' => 'Additional narrative information about a specific Allegation. Might provide additional  context to help investigate and verify these Allegations.',
			'FldNotes' => 'Anytime a user indicate an Allegation they will be prompted for additional information to describe the Allegation or why they think the Allegation should apply.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 824,
			'FldDatabase' => '1',
			'FldTable' => '138',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Charges',
			'FldEng' => 'Citations Charges',
			'FldDesc' => 'Category of citation charge (or charges) an Officer issued a Subject. Important for evaluating wrongful citation Allegations and tracking citation trends.',
			'FldNotes' => 'Complainants should be able to select multiple Citations, because we want to be able to track Officers who are Citation-crazy.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Citation Charges',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 830,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '27',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'NameFirst',
			'FldEng' => 'First Name',
			'FldDesc' => 'The legal given name of a person related to this record that we use in all formal communications with that person or regarding that person.',
			'FldNotes' => 'Use the legal spelling and format of their first name even if they use the initial of their first name and prefer their middle name. http://departments.weber.edu/qsupport&training/Data_Standards/Name.htm',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 832,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '29',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'NameMiddle',
			'FldEng' => 'Middle Name',
			'FldDesc' => 'The name of a Complainant, placed between their first name and before their surname, that we use in all formal communications with Complainants or regarding Complainants.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '100',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 833,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '31',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'NameSuffix',
			'FldEng' => 'Name Suffix',
			'FldDesc' => 'Letters added after a person\'s last name related to this record -- which provide additional information about their title or inherited name -- that we use in all formal communications with that person or regarding that person.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 834,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '26',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'NamePrefix',
			'FldEng' => 'Name Prefix',
			'FldDesc' => 'Letters placed before a person\'s first name related to this record, whether original or by title, that we use in all formal communications with that person or regarding that person.',
			'FldNotes' => '"Name Prefix" should also include titles (e.g. "Judge" or "Vice Admiral"). Field may be left blank. Question: If these are generic fields, will this incident-involved definition apply to IA staff, Attorneys, and others not directly associated with incident?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 835,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '30',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'NameLast',
			'FldEng' => 'Last Name',
			'FldDesc' => 'The legal surname of a person related to this record that we use in all formal communications with that person or regarding that person.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 837,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '32',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Email',
			'FldEng' => 'Email',
			'FldDesc' => 'The valid email address provided by Complainants. This is the primary way we communicate with them.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => 'AAA@AAA.AAA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 838,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '33',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'PhoneHome',
			'FldEng' => 'Home Phone',
			'FldDesc' => 'The contact number where the person can be reached at their home. This is usually a landline phone, which cannot receive text messages.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 839,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '34',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'PhoneWork',
			'FldEng' => 'Work Phone',
			'FldDesc' => 'The contact phone number where the person can be reached at their place of employment. This is usually a landline phone, which cannot receive text messages.',
			'FldNotes' => 'We might need to add an "extension" field for some work phone numbers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 840,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '35',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'PhoneMobile',
			'FldEng' => 'Mobile Phone',
			'FldDesc' => 'The contact number where the person can be reach on their cellular device. We may use this number for both text and voice communications.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => '###-###-#### [x###]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 841,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '36',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Address',
			'FldEng' => 'Street Address',
			'FldDesc' => 'The postal location at or near where an Incident occurred or the postal location where a contact resides.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 842,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '37',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Address2',
			'FldEng' => 'Street Address 2',
			'FldDesc' => 'Line 2 of person\'s address, if needed',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 843,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '38',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'AddressCity',
			'FldEng' => 'City',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 844,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '39',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'AddressState',
			'FldEng' => 'State',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '2',
			'FldCharSupport' => ',Letters,',
			'FldInputMask' => 'AA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 845,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '40',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'AddressZip',
			'FldEng' => 'Zip Code',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => '#####[-####]',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 846,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '41',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Birthday',
			'FldEng' => 'Date of Birth',
			'FldDesc' => 'The month, day, and year a Complainant was born. This information is used to help verify a Complainant\'s identity.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATE',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => 'YYYY-MM-DD',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldCompareOther' => '6',
			'FldCompareValue' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 847,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '42',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Facebook',
			'FldEng' => 'Facebook',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldInputMask' => 'http://facebook.com/AAAAAA',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 860,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'AgainstAnimal',
			'FldEng' => 'Against Animal',
			'FldDesc' => 'Indicates that this use of Force was directed against a non-human pet or animal. Important for identifying and tracking animal cruelty Allegations.',
			'FldNotes' => 'IF "Yes" force was used against an animal, and "Yes" this is allegation-worthy excessive use of force, THEN this is an Animal Cruelty Allegation.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 861,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'AnimalDesc',
			'FldEng' => 'Describe Animal',
			'FldDesc' => 'Narrative description of animal that Officer Force was directed against. Important for verifying animal cruelty Allegations.',
			'FldNotes' => 'Tells us about the animal. For example, what kind of animal is it? Is it a wild animal or stray? Or is it a family pet? Please describe.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 864,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'ForcibleEntry',
			'FldEng' => 'Forcible Entry or SWAT Raid?',
			'FldDesc' => 'Indicates that the Incident involved the use of a forcible entry, SWAT raids, or drug task force. Important for evaluating SWAT incidents and trends.',
			'FldNotes' => 'This should only appear as an option if users selects from Home or Private Residence; Workplace; School or University Property from Scene Type options.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 865,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '43',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Gender',
			'FldEng' => 'Gender',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'M;F;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 866,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The primary ID of the Complaint record related to the this table\'s record.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 867,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '1',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'ComplimentID',
			'FldEng' => 'Compliment ID',
			'FldDesc' => 'The primary ID of the Compliment record related to the this table\'s record.',
			'FldForeignTable' => '134',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowNot',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 868,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '44',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Age',
			'FldEng' => 'Age',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => '0-15;16-24;24-34;35-44;45-54;55-64;65-74;75-84;85+',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 869,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '45',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Race',
			'FldEng' => 'Race',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Races',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 877,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '47',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '838',
			'FldName' => 'Height',
			'FldEng' => 'Height',
			'FldNotes' => 'Converted to Inches',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 889,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'GunAmmoType',
			'FldEng' => 'What type of ammo did this officer\'s gun use?',
			'FldDesc' => 'Category of ammunition an Officer used on a Subject. Important for investigating Allegations of Force and for identifying Force trends.',
			'FldNotes' => 'Only if Force Type is \'Gun\'.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Gun Ammo Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 890,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'GunDesc',
			'FldEng' => 'Describe gun, and specific type if known.',
			'FldDesc' => 'Narrative details of gun Officer used on Suspect. Important for investigating Allegations of Force and for identifying Force trends.',
			'FldNotes' => 'Only if Force Type is \'Gun\'.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 902,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '52',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'HairDescription',
			'FldEng' => 'Hair Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 904,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '54',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'HairFacialDesc',
			'FldEng' => 'Facial Hair Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 905,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '55',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Eyes',
			'FldEng' => 'Eyes Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 908,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '58',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'DistinguishingMarksDesc',
			'FldEng' => 'Distinguishing Marks Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 909,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '59',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'VoiceDesc',
			'FldEng' => 'Voice Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 910,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '60',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'ClothesDesc',
			'FldEng' => 'Clothes Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 912,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '62',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'DisabilitiesDesc',
			'FldEng' => 'Disabilities Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 913,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '63',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Transportation',
			'FldEng' => 'Transportation',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Foot;Car;Van;Bike;Boat;Motorcycle;Helicopter',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 914,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '64',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'VehicleMake',
			'FldEng' => 'Vehicle Make',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 915,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '65',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'VehicleModel',
			'FldEng' => 'Vehicle Model',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 916,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '66',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'VehicleDesc',
			'FldEng' => 'Vehicle Description',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 917,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '67',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'VehicleLicence',
			'FldEng' => 'Vehicle Licence Number',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 918,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '68',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'CameraRecord',
			'FldEng' => 'Recorded Event?',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 920,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '2',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'DeptID',
			'FldEng' => 'Department ID',
			'FldDesc' => 'The primary ID of the Department record related to the this table\'s record.',
			'FldForeignTable' => '111',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 921,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '22',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'AuthUserID',
			'FldEng' => 'User Authentication ID',
			'FldDesc' => 'The primary ID of the System User record related to the this table\'s record.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 922,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '69',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Title',
			'FldEng' => 'Title',
			'FldDesc' => 'Title of this person\'s role in their organization.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 923,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '70',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'CompanyName',
			'FldEng' => 'Company Name',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 924,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '71',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'CompanyWebsite',
			'FldEng' => 'Company Website',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 925,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '4',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'OversightID',
			'FldEng' => 'Oversight ID',
			'FldDesc' => 'The primary ID of the Oversight record related to the this table\'s record.',
			'FldForeignTable' => '106',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 926,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '72',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Verified',
			'FldEng' => 'Contact Info Last Verified',
			'FldNotes' => 'NULL value means unverified.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 927,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '9',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'IncidentID',
			'FldEng' => 'Incident ID',
			'FldDesc' => 'The primary ID of the Incident record related to the this table\'s record.',
			'FldForeignTable' => '114',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 928,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '25',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'CustomerID',
			'FldEng' => 'Customer ID',
			'FldDesc' => 'The primary ID of the Customer record related to the this table\'s record.',
			'FldForeignTable' => '110',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 929,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '23',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'AdminID',
			'FldEng' => 'Primary Admin ID',
			'FldDesc' => 'The primary ID of the Administrator record related to the this table\'s record.',
			'FldNotes' => 'OPC staff in charge of Complaint ',
			'FldForeignTable' => '108',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 932,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '13',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'StopID',
			'FldEng' => 'Related Stop ID',
			'FldDesc' => 'The primary ID of the Stop record related to the this table\'s record.',
			'FldForeignTable' => '121',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 933,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '14',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'SearchID',
			'FldEng' => 'Related Search ID',
			'FldDesc' => 'The primary ID of the Search record related to the this table\'s record.',
			'FldForeignTable' => '122',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 934,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '15',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'PropertyID',
			'FldEng' => 'Related Property ID',
			'FldDesc' => 'The primary ID of the Property record related to the this table\'s record.',
			'FldForeignTable' => '118',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 935,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '16',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'ForceID',
			'FldEng' => 'Related Force ID',
			'FldDesc' => 'The primary ID of the Force record related to the this table\'s record.',
			'FldForeignTable' => '116',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 936,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '20',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'ArrestID',
			'FldEng' => 'Related Arrest ID',
			'FldDesc' => 'The primary ID of the Arrest record related to the this table\'s record.',
			'FldForeignTable' => '123',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 938,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '73',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'TimeStart',
			'FldEng' => 'Start Time',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 939,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '74',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'TimeEnd',
			'FldEng' => 'End Time',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 940,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '75',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Duration',
			'FldEng' => 'Duration',
			'FldDesc' => 'In Minutes, calculated based on Start Time and End Time.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '5',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 941,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '11',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'AllegationID',
			'FldEng' => 'Related Allegation ID',
			'FldDesc' => 'The primary ID of the Allegation record related to the this table\'s record.',
			'FldForeignTable' => '113',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 944,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '10',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'SceneID',
			'FldEng' => 'Scene ID',
			'FldDesc' => 'The primary ID of the Scene record related to the this table\'s record.',
			'FldNotes' => 'For optionally associating Photographs, Diagrams, Documents, or Videos with a Scene.',
			'FldForeignTable' => '115',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 945,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '17',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'InjuryID',
			'FldEng' => 'Injury ID',
			'FldDesc' => 'The primary ID of the Injury record related to the this table\'s record.',
			'FldNotes' => 'For optionally associating Photographs, Diagrams, Documents, or Videos with an Injury.',
			'FldForeignTable' => '117',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 947,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '5',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'CivilianID',
			'FldEng' => 'Civilian ID',
			'FldDesc' => 'The primary ID of the Civilian record related to the this table\'s record.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 948,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '18',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'EvidenceID',
			'FldEng' => 'Evidence ID',
			'FldDesc' => 'The primary ID of the Evidence record related to the this table\'s record.',
			'FldForeignTable' => '119',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NULL',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 949,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '6',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerID',
			'FldEng' => 'Officer ID',
			'FldDesc' => 'The primary ID of the Officer record related to the this table\'s record.',
			'FldForeignTable' => '104',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 950,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '19',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'OrderID',
			'FldEng' => 'Order ID',
			'FldDesc' => 'The primary ID of the Order record related to the this table\'s record.',
			'FldForeignTable' => '120',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 951,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '76',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'IP',
			'FldEng' => 'IP Address',
			'FldNotes' => 'IP addresses may want to be encrypted.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '50',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 952,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '77',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Name',
			'FldEng' => 'Username',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 953,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '78',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Password',
			'FldEng' => 'Password',
			'FldNotes' => 'Encrypted',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 954,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '79',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'LastLogin',
			'FldEng' => 'Last Login',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 955,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '80',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerRank',
			'FldEng' => 'Officer Rank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Police Officer;Detective;Corporal;Sergeant;Lieutenant;Captain;Staff Inspector;Inspector;Chief Inspector;Deputy Commissioner;Commissioner;?',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 956,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '81',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'BadgeNumber',
			'FldEng' => 'Badge Number',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 957,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '82',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Timestamp',
			'FldEng' => 'Timestamp',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDefault' => 'NOW()',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldDisplayFormat' => 'YYYY-MM-DD HH:MM:SS',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 959,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '12',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The primary ID of the Event Sequence record related to the this table\'s record.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 960,
			'FldDatabase' => '1',
			'FldTable' => '113',
			'FldOrd' => '2',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '959',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The unique number of the Incident Event record related to this Allegation. This is important for linking the Allegation to a specific Stop, Search, Use of Force, or Arrest.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Foreign',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 962,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerRefuseID',
			'FldEng' => 'Did Officer Refuse To Provide ID?',
			'FldDesc' => 'Indicates whether or not the Officer shared their name and/or badge number after Subject requested it. Important for identifying possible violation of Department procedure.',
			'FldNotes' => 'We might use this opportunity to let Complainants know that insisting Officers share their badge number isn\'t always a smart move.

Tip: It\'s okay if you didn\'t get the badge or vehicle number. An officer--s identity can usually be established with a time, location, and physical description.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 963,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'AddressCounty',
			'FldEng' => 'County',
			'FldDesc' => 'The state region where a local Police Department is headquartered or has jurisdiction. This information helps us track which Police Departments have jurisdiction over a specific Incident location.',
			'FldNotes' => 'Not applicable to Departments with Federal and Statewide jurisdictions.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '100',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 964,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '10',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '967',
			'FldName' => 'Precinct',
			'FldEng' => 'Department Precinct/District',
			'FldDesc' => 'The district within a city or municipality where an Officer is assigned. This information might help us  verify an Officer\'s identity.',
			'FldNotes' => 'Most Departments will NOT have precinct. However, precincts or districts usually exist in big cities.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 965,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '83',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'IDnumber',
			'FldEng' => 'Additional ID#',
			'FldNotes' => 'Eg. in New York, officers have a consistent Tax ID.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 967,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '84',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Precinct',
			'FldEng' => 'Department Precinct',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '2'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 969,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'Landmarks',
			'FldEng' => 'Nearby Landmarks',
			'FldDesc' => 'An easily-recognized place, object, or feature that can help establish a precise Incident location. Useful for identifying locations where an address alone won\'t suffice.',
			'FldNotes' => 'e.g. near a memorial inside a large park.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 970,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'DashCam',
			'FldEng' => 'Officer Dash Cam',
			'FldDesc' => 'Indicates whether or not the Officer\'s vehicle had a dashcam. Essential for obtaining possible video Evidence of the Incident.',
			'FldNotes' => 'The Complainant won\'t likely know if there was a dashcam. This is intended as a prompt to urge Complainants to seek this information on their own or with an Attorney\'s help. 

On 9/17, we decided not to include this in the UI specs because most complainants will 1) not know if there is a dash cam; 2) won\'t be able to access any available footage without lawyer; and 3) this could be an unnecessary and complex distraction for users.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 971,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '28',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'Nickname',
			'FldEng' => 'Nickname',
			'FldDesc' => 'Preferred first name of a Complainant that we only use in personal communications with Complainants.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 973,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '7',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '971',
			'FldName' => 'Nickname',
			'FldEng' => 'Nickname',
			'FldDesc' => 'Preferred first name of persons that we may use in less-formal communications with them. Important for maintaining cordial relationships with these key contacts.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 977,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '8',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '971',
			'FldName' => 'Nickname',
			'FldEng' => 'Nickname',
			'FldDesc' => 'Preferred first name of Oversight Agency contacts that we use in less-formal communications with them. Important for maintaining cordial relationships with these key contacts.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 984,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'IsCreator',
			'FldEng' => 'Is Creator?',
			'FldDesc' => 'Indicates that this Civilian is the person who created the Complaint or Compliment. This information helps us identity Complainants who are also Subjects or Witnesses.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N',
			'FldDefault' => 'N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 985,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '921',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'The primary User Authentication number connected to a Civilian\'s record. Important for allowing Complainants to log into OPC to complete Complaints or update Complaints.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 987,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Oversight Type',
			'FldDesc' => 'Indicates category of Oversight Agency. This information is essential for determining if a given Oversight Agency is either an Internal Affairs Department or a Citizen Oversight Agency. (They cannot be both.)',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Oversight Agency Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 988,
			'FldDatabase' => '1',
			'FldTable' => '110',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Customer Occupation',
			'FldDesc' => 'The profession of a Customer. This information helps us keep track of who is using our product and identify potential marketing opportunities for new Customers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Customer Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 990,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Role',
			'FldEng' => 'Role in Incident',
			'FldDesc' => 'Indicates category of a Civilian. This information is essential for understanding whether a given Civilian was a Subject of an Allegation or a Witness to the Allegation.',
			'FldNotes' => 'People cannot be both a Subject and a Witness to an Incident. If considered both, then they are a Subject.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Witness;Subject;Neither',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Letters,',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 991,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'AgncName',
			'FldEng' => 'Agency Name',
			'FldDesc' => 'The official name of an Oversight Agency. This is an essential component of a Oversight Agency\'s complete address.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 993,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '14',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '924',
			'FldName' => 'Website',
			'FldEng' => 'Website',
			'FldDesc' => 'The page URL of the Civilian Oversight Agency or Internal Affairs Department. Ideally provides clear information about the Police Department\'s complaint process.',
			'FldNotes' => 'IMPORTANT: Oversight Agencies include both Civilian Oversight Agencies (COAs) and Internal Affairs (IA) Departments. If a Police Department has a NACOLE-approved COA, use their URL instead of the Department\'s IA URL.
https://nacole.org/resources/u-s-oversight-agency-websites/

Keep in mind that a .gov URL with clear-cut police complaint information will frequently be absent or difficult to find. If that\'s the case, OPC\'s web form must clearly indicate this absence.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 994,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '1000',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'BodyType',
			'FldEng' => 'Body Type',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Slim/Slender;Medium/Average;Athletic/Muscular;Large/Fat',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 995,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '994',
			'FldName' => 'BodyType',
			'FldEng' => 'Body Type',
			'FldDesc' => 'General category of a Civilian or Officer\'s body shape. This information might help verify a Civilian or Officer\'s identity.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Body Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 997,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '1000',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'LinkedIn',
			'FldEng' => 'LinkedIn',
			'FldDesc' => 'The URL link to a Customer\'s LinkedIn profile page. This information helps us keep track of who is using our product and identify potential marketing opportunities for new Customers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 998,
			'FldDatabase' => '1',
			'FldTable' => '144',
			'FldOrd' => '1000',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 999,
			'FldDatabase' => '1',
			'FldTable' => '144',
			'FldOrd' => '1000',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '920',
			'FldName' => 'DeptID',
			'FldEng' => 'Department ID',
			'FldDesc' => 'The unique number of the Department record related to this Complaint. This number helps us identify which Department this Complaint is directed and therefore which Oversight Agency should be contacted.',
			'FldForeignTable' => '111',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldEditRule' => 'NowNot',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1000,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '866',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record, vital to associating with all other Complaint data, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '11',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldEditRule' => 'NowNot',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1001,
			'FldDatabase' => '1',
			'FldTable' => '0',
			'FldOrd' => '1000',
			'FldSpecType' => 'Generic',
			'FldSpecSource' => '0',
			'FldName' => 'WebComplaintInfo',
			'FldEng' => 'Website Complaint Information',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1003,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'HomepageComplaintLink',
			'FldEng' => 'Homepage Complaint Link?',
			'FldDesc' => 'Indicates whether or not a Police Department--s main homepage has a visible link to complaint information. (It should, but most will not.)',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1004,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '19',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '1001',
			'FldName' => 'WebComplaintInfo',
			'FldEng' => 'Website Complaint Information URL',
			'FldDesc' => 'Indicates whether or not the designated Oversight Agency URL (if there is one) has clear-cut info on how about how the complaints process works. (Most will not.)',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1005,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'StripSearchDesc',
			'FldEng' => 'Strip Search Description',
			'FldDesc' => 'Narrative details about the nature of the strip search. Important for identifying particularly egregious wrongful search Allegations -- including cavity searches. ',
			'FldNotes' => 'We understand that this isn\'t easy, but please provide as much detail as you can about the strip search. The more information you provide, the better we can help address your complaint. ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1006,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'DutyStatus',
			'FldEng' => 'Duty Status',
			'FldDesc' => 'Category indicates whether Officer was on-duty or off-duty during Incident. Particularly important for investigating Allegations against off-duty officers.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'On-Duty;Off-Duty;?',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1007,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'VehicleNumber',
			'FldEng' => 'Vehicle Number',
			'FldDesc' => 'A unique vehicle ID number. Important for verifying an Officer\'s identity or Department.',
			'FldNotes' => 'This is not the same as License Number.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1008,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '19',
			'FldSpecSource' => '0',
			'FldName' => 'AdditionalDetails',
			'FldEng' => 'Additional Details',
			'FldDesc' => 'Supplemental narrative details about Officers involved in Incident. Important for verifying an Officer\'s identity or Department.',
			'FldNotes' => 'This field only used for light/bronze version of user experience.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1011,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '1',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '935',
			'FldName' => 'SubjectID',
			'FldEng' => 'Subject ID',
			'FldDesc' => 'Indicates which Subject received Injury Care tracked in this record. Important for identifying and matching specific uses of Force with specific Injuries received during an Incident.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1012,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '959',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The primary ID of the Incident Event record related to this Force record. This is important for establishing this Force\'s position in the chronology of events, and associating one or more Civilians and one or more Officers with this Force.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldValuesEnteredBy' => 'System',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1013,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '959',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The primary ID of the Incident Event record related to this Arrest record. This is important for establishing this Arrest\'s position in the chronology of events, and associating one or more Civilians and one or more Officers with this Arrest.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1014,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '959',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The primary ID of the Incident Event record related to this Search record. This is important for establishing this Search\'s position in the chronology of events, and associating one or more Civilians and one or more Officers with this Search.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1015,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '959',
			'FldName' => 'EventSequenceID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The primary ID of the Incident Event record related to this Stops record. This is important for establishing this Stop\'s position in the chronology of events, and associating one or more Civilians and one or more Officers with this Stop.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1016,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'GotMedical',
			'FldEng' => 'Got Medical Care?',
			'FldDesc' => 'Indicates whether or not an Injured Civilian received medical care from a licensed medical practitioner. Essential for urging Injured Civilians to seek medical care who haven\'t received it yet.',
			'FldNotes' => 'Implementation Note: We must urge users with fresh Injuries to seek medical care if they haven\'t yet. Because official medical records are essential for substantiating Force Allegations.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => 'Non',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1022,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '32',
			'FldSpecSource' => '0',
			'FldName' => 'RecordSubmitted',
			'FldEng' => 'Record Submitted',
			'FldDesc' => 'Date and time when this Complaint was completed and submitted to the OPC database.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldIsIndex' => '1',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => 'YYYY-MM-DD HH:MM:SS',
			'FldEditRule' => 'LateNot',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1025,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'ScoreOpenness',
			'FldEng' => 'Openness Score',
			'FldDesc' => 'This indicates an overall rating for a Police Department\'s online presence and the quality of their Complaint submission process. This is important for encouraging Departments to improve these metrics.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '6',
			'FldOperateSame' => '10010'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1026,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '21',
			'FldSpecSource' => '0',
			'FldName' => 'ComplaintWebForm',
			'FldEng' => 'Website Complaint Form URL',
			'FldDesc' => 'Indicates whether or not the designated Police Department website has a form to allow complaints to be submitted online. (Most will not.)',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1027,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'RequestID',
			'FldEng' => 'Did the officer request your ID?',
			'FldDesc' => 'Indicates whether or not the Officer requested the Subject\'s identification. Important for identifying possible violation of Department procedure.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1029,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'Occupation',
			'FldEng' => 'Occupation',
			'FldDesc' => 'The profession of a Civilian. This information can help investigators identify Civilians.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldCompareSame' => '130',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1031,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'AwardMedallion',
			'FldEng' => 'Award Medallion',
			'FldDesc' => 'The current level of detail asked of Complainant based on their selection. We use this information internally to determine what fields to show the Complainant, and what Medallion up-sells to encourage when appropriate.',
			'FldNotes' => 'Defaults to Gold.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Silver;Gold',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1032,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'ReportDate',
			'FldEng' => 'Date of Oversight Investigative Report',
			'FldDesc' => 'Indicates the date and time when an Oversight Agency contact regarding the status of their Complaint or to investigate any Allegations contained in the Complaint.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldCompareSame' => '6',
			'FldCompareOther' => '6',
			'FldOperateSame' => '70',
			'FldOperateOther' => '70'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1034,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'PermissionEnter',
			'FldEng' => 'Officer request permission to enter a private residence or workplace?',
			'FldDesc' => 'Indicates whether or not the Officer requested permission to enter a private residence or workplace. Important for identifying possible violation of Department procedure.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1035,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'PermissionEnterGranted',
			'FldEng' => 'Subject grant permission to enter a private residence or workplace?',
			'FldDesc' => 'Indicates whether or not the Civilian granted an Officer permission to enter a private residence or workplace. Important for identifying possible violation of Department procedure.',
			'FldNotes' => 'If Complainant respond "No", then prompt for Wrongful Entry Allegation (with description).',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1036,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'EnterPrivateProperty',
			'FldEng' => 'Officer entered private property?',
			'FldDesc' => 'Indicates whether or not the an Officer entered a private residence or workplace. Important for identifying possible violation of Department procedure.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1037,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'EnterPrivatePropertyDesc',
			'FldEng' => 'Officer entered private property: Describe',
			'FldDesc' => 'Describes how an Officer entered a private residence or workplace. Important for identifying possible violation of Department procedure.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1038,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'PhysDescID',
			'FldEng' => 'Physical Description ID',
			'FldDesc' => 'The primary Physical Description record number connected to a Civilian\'s record. Important for linking name and basic contact information to a Civilian.',
			'FldForeignTable' => '153',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1039,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'PhysDescID',
			'FldEng' => 'Physical Description ID',
			'FldDesc' => 'The primary Physical Description record number connected to a Officer\'s record. Important for linking name and basic contact information to a Officer.',
			'FldForeignTable' => '153',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1040,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'RequestOfficerID',
			'FldEng' => 'Did the subject request an officer\'s ID?',
			'FldDesc' => 'Indicates whether or not a Subject requested the Officer\'s identification. Important for identifying possible violation of Department procedure.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1041,
			'FldDatabase' => '1',
			'FldTable' => '120',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'TroubleUnderYN',
			'FldEng' => 'Subject Trouble Understanding Order?',
			'FldDesc' => 'Indicates whether or not a Subject had difficulty hearing or understanding an Officer\'s order during a Use of Force. Important for evaluating Force Allegations.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1042,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '20',
			'FldSpecSource' => '0',
			'FldName' => 'ComplaintPDF',
			'FldEng' => 'Complaint PDF',
			'FldDesc' => 'Indicates whether or not the designated Police Department provide a PDF of their paper Complaint form online. This URL is very important for providing Complainants with easy access to the form they might be required to fill out for an investigation to begin.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1043,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'Facebook',
			'FldEng' => 'Facebook',
			'FldDesc' => 'This is the URL address of a Agency\'s Facebook page. If available, this will be included in each Agency\'s OPC page so that members of public may have another way to voice their complements or concerns with the department.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1044,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '38',
			'FldSpecSource' => '0',
			'FldName' => 'UniqueStr',
			'FldEng' => 'Unique String',
			'FldDesc' => 'This unique, randomly generated string can be important for creating custom URLs which are more secure than using the Complaint ID#.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Numbers,',
			'FldUnique' => '1',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1047,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Slug',
			'FldEng' => 'URL Slug',
			'FldDesc' => 'This defines the version of the department name which is compatible and ideal to be used as part of a website URL. This is vital for creating public pages of the website which are intuitive and professional.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '100',
			'FldCharSupport' => ',Letters,Numbers,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1048,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '31',
			'FldSpecSource' => '0',
			'FldName' => 'Slug',
			'FldEng' => 'URL Slug',
			'FldDesc' => 'This defines the version of the Complaint\'s Social Media Headline which is compatible and ideal to be used as part of a website URL. This is vital for creating public pages of the website which are intuitive and professional.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '255',
			'FldCharSupport' => ',Letters,Numbers,',
			'FldCompareSame' => '878800',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1049,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '35',
			'FldSpecSource' => '0',
			'FldName' => 'WaySubPaperMail',
			'FldEng' => 'Way to Submit: Paper Form Mail',
			'FldDesc' => 'This flags that the designated Police Department currently accepts complaints when submitted on their paper form by mail, important for rating Departments.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1050,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '32',
			'FldSpecSource' => '0',
			'FldName' => 'WaySubOnline',
			'FldEng' => 'Way to Submit: Online Form',
			'FldDesc' => 'This flags that the designated Police Department currently accepts complaints when submitted via an online form, important for rating Departments.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1051,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '30',
			'FldSpecSource' => '0',
			'FldName' => 'OfficialFormNotReq',
			'FldEng' => 'Official Form Not Required',
			'FldDesc' => 'This flags that the designated Police Department requires their own paper form to investigate, important for rating Departments.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1052,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '36',
			'FldSpecSource' => '0',
			'FldName' => 'WaySubPaperInPerson',
			'FldEng' => 'Way to Submit: Requires In-Person Paper Form',
			'FldDesc' => 'This flags that the designated Police Department currently accepts complaints when submitted on their paper form in-person, important for rating Departments.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1054,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '34',
			'FldSpecSource' => '0',
			'FldName' => 'WaySubVerbalPhone',
			'FldEng' => 'Way to Submit: Verbal Phone',
			'FldDesc' => 'This flags that the designated Police Department currently accepts complaints when submitted verbally over the phone, important for rating Departments.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1055,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '33',
			'FldSpecSource' => '0',
			'FldName' => 'WaySubEmail',
			'FldEng' => 'Way to Submit: Email',
			'FldDesc' => 'This flags that the designated Police Department currently accepts complaints when submitted via email, important for rating Departments.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1057,
			'FldDatabase' => '1',
			'FldTable' => '147',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Reason',
			'FldEng' => 'Reason Officer Gave for Stop?',
			'FldDesc' => 'The stated or non-stated Officer explanations to Civilians for a Stop or detention. This table is important for associating multiple reasons given for a single Stop.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Reason for Vehicle Stop',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1058,
			'FldDatabase' => '1',
			'FldTable' => '147',
			'FldSpecSource' => '0',
			'FldName' => 'StopID',
			'FldEng' => 'Stop ID',
			'FldDesc' => 'The unique number of the Stop record related to this Stop Reason. Vital for associating multiple Stop Reasons with a single Stop during an Incident.',
			'FldForeignTable' => '121',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Non,Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1059,
			'FldDatabase' => '1',
			'FldTable' => '148',
			'FldOrd' => '1000',
			'FldSpecSource' => '0',
			'FldName' => 'ForceID',
			'FldEng' => 'Force ID',
			'FldDesc' => 'The unique number of the Force record related to further type of force details. Vital for associating multiple secondary types of force used with a single Use of Force during an Incident.',
			'FldForeignTable' => '116',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1060,
			'FldDatabase' => '1',
			'FldTable' => '148',
			'FldOrd' => '1000',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Secondary Force Type',
			'FldDesc' => 'This provides greater detail on the type of force used in this instance. The options vary between Force types Control Hold, Body Weapon, or Takedown.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Force Type - Control Hold',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1061,
			'FldDatabase' => '1',
			'FldTable' => '149',
			'FldSpecSource' => '0',
			'FldName' => 'ForceID',
			'FldEng' => 'Force ID',
			'FldDesc' => 'The unique number of the Use of Force record which this Body Part helps describe. Vital for associating multiple Body Parts with a single Use of Force during an Incident.',
			'FldForeignTable' => '116',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1062,
			'FldDatabase' => '1',
			'FldTable' => '149',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'InjuryID',
			'FldEng' => 'Injury ID',
			'FldDesc' => 'The unique number of the Injury record which this Body Part helps describe. Vital for associating multiple Body Parts with a single type of Injury ocurring during an Incident.',
			'FldForeignTable' => '117',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1063,
			'FldDatabase' => '1',
			'FldTable' => '149',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Part',
			'FldEng' => 'Body Part',
			'FldDesc' => 'This describes which part of the body Force was used upon or where Injuries happened. This is important for providing investigators documentation regarding Civilian health.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Body Part',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1064,
			'FldDatabase' => '1',
			'FldTable' => '150',
			'FldSpecSource' => '0',
			'FldName' => 'SearchID',
			'FldEng' => 'Search ID',
			'FldDesc' => 'The unique number of the Search record related to this Search Contraband found. Vital for associating multiple Search Contraband items with a single Search during an Incident.',
			'FldForeignTable' => '122',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1065,
			'FldDatabase' => '1',
			'FldTable' => '150',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Contraband Types',
			'FldDesc' => 'Categories of illegal items discovered during a Search. Important for identifying search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Contraband Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1066,
			'FldDatabase' => '1',
			'FldTable' => '151',
			'FldSpecSource' => '0',
			'FldName' => 'SearchID',
			'FldEng' => 'Search ID',
			'FldDesc' => 'The unique number of the Search record related to this Search Seizure. Vital for associating multiple Search Seizures items with a single Search during an Incident.',
			'FldForeignTable' => '122',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1067,
			'FldDatabase' => '1',
			'FldTable' => '151',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Seized Property Type',
			'FldDesc' => 'Categories of items Seized discovered during a Search. Important for identifying search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Property Seized Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1068,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'PersonID',
			'FldEng' => 'Person Contact Info ID',
			'FldDesc' => 'The primary Person Contact record number connected to a Civilian\'s record. Important for linking name and basic contact information to a Civilian.',
			'FldForeignTable' => '154',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1069,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'PersonID',
			'FldEng' => 'Person Contact Info ID',
			'FldDesc' => 'The primary Person Contact record number connected to a Officer\'s record. Important for linking name and basic contact information to an Officer.',
			'FldForeignTable' => '154',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1070,
			'FldDatabase' => '1',
			'FldTable' => '108',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'PersonID',
			'FldEng' => 'Person Contact Info ID',
			'FldDesc' => 'The primary Person Contact record number connected to a Administrator\'s record. Important for linking name and basic contact information to a Administrator.',
			'FldForeignTable' => '154',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1071,
			'FldDatabase' => '1',
			'FldTable' => '110',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'PersonID',
			'FldEng' => 'Person Contact Info ID',
			'FldDesc' => 'The primary Person Contact record number connected to a Customer\'s record. Important for linking name and basic contact information to a Customer.',
			'FldForeignTable' => '154',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1073,
			'FldDatabase' => '1',
			'FldTable' => '155',
			'FldSpecSource' => '0',
			'FldName' => 'OffID',
			'FldEng' => 'Officer ID',
			'FldDesc' => 'The unique number of the Officer record being linked in this record. Vital for associating Officers with the Vehicle.',
			'FldForeignTable' => '104',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1074,
			'FldDatabase' => '1',
			'FldTable' => '155',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'VehicID',
			'FldEng' => 'Vehicle ID',
			'FldDesc' => 'The unique number of the Vehicle record being linked in this record. Vital for associating Civilians or Officers with the Vehicle.',
			'FldForeignTable' => '152',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1076,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '39',
			'FldSpecSource' => '0',
			'FldName' => 'IPaddy',
			'FldEng' => 'IP Address (encrypted)',
			'FldDesc' => 'This logs an encrypted copy of the Complainants IP Address. This is important for checking if multiple Complaints were submitted from the same location, especially when filtering Complaints categorized as spam or abuse.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldCompareSame' => '130',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1077,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'GivePhysDesc',
			'FldEng' => 'Would you like to provide a physical description?',
			'FldDesc' => 'Indicates that the Complainant has chosen to submit a physical description of this Civilian. This information helps the software provide the correct form fields to the Complainant.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1078,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'Resident',
			'FldEng' => 'Resident of Department Jurisdiction',
			'FldDesc' => 'Indicates whether or not a Victim was a resident of the police Department\'s jurisdiction, at the time of the incident. This information could help inform race-based allegations.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1079,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'CitationNumber',
			'FldEng' => 'Citation ID#',
			'FldDesc' => 'Citation ID Number issued to a Subject. Important for tracking down more information during investigations.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '25',
			'FldCharSupport' => ',Letters,Numbers,',
			'FldCompareSame' => '878800',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1080,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'Twitter',
			'FldEng' => 'Twitter',
			'FldDesc' => 'This is the URL address of a Agency\'s Twitter page. If available, this will be included in each Agency\'s OPC page so that members of public may have another way to voice their complements or concerns with the department.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1081,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '31',
			'FldSpecSource' => '0',
			'FldName' => 'OfficialAnon',
			'FldEng' => 'Anonymous Complaints Investigated',
			'FldDesc' => 'This flags that the designated Police Department does accept -- and will investigate -- complaints which are submitted anonymously. This is important for department ratings and OPC compatibility.

',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1082,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'GivePhysDesc',
			'FldEng' => 'Would you like to provide a physical description?',
			'FldDesc' => 'Indicates that the Complainant has chosen to submit a physical description of this Officer. This information helps the software provide the correct form fields to the Complainant.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1083,
			'FldDatabase' => '1',
			'FldTable' => '138',
			'FldSpecSource' => '0',
			'FldName' => 'EventID',
			'FldEng' => 'Event Sequence ID',
			'FldDesc' => 'The unique number of the Event Sequence record related to this Arrest or Stop Charge.',
			'FldForeignTable' => '137',
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
			'FldID' => 1084,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'GivenCitation',
			'FldEng' => 'Given Citation (not arrested)',
			'FldDesc' => 'Indicates whether or not this Subject was given a Citation with Charges as a result of this Stop.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1085,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'GivenWarning',
			'FldEng' => 'Given Written Warning',
			'FldDesc' => 'Indicates whether or not this Subject was only given a Warning as a result of this Stop.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1086,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'ChargesOther',
			'FldEng' => 'Citation Charges: Other (not in list)',
			'FldDesc' => 'Citation charge (or charges) an Officer issued a Subject as a result of this Stop. Important for evaluating wrongful citation Allegations and tracking citation trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1087,
			'FldDatabase' => '1',
			'FldTable' => '145',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'StillNoResponse',
			'FldEng' => 'Still No Response by Oversight',
			'FldDesc' => 'Indicates date and time when a Complainant last confirmed that they have heard no response from the appropriate Oversight Agency. This is essential for tracking an Oversight Agency\'s response time when acknowledging receipt of OPC-submitted Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1088,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'StopSubjectHandcuffInjYN',
			'FldEng' => 'Did the Subject Have Injuries from Handcuffs?',
			'FldDesc' => 'Indicates whether or not the Subject reported any Injuries related to an Officer\'s misuse of handcuffs. Important for connecting with possible Use of Force.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1089,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '37',
			'FldSpecSource' => '0',
			'FldName' => 'WaySubNotary',
			'FldEng' => 'Way to Submit: Requires Notary',
			'FldDesc' => 'This flags that the designated Police Department currently requires complaints to be submitted with a notary.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1090,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'CivModel',
			'FldEng' => 'Civilian Oversight Model',
			'FldDesc' => 'Indicates subcategory of Civilian Oversight Agency, either Investigative, Review, or Audit. This information is essential for determining scope of a given Oversight Agency.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Civilian Oversight Models',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1091,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldSpecSource' => '0',
			'FldName' => 'ComplaintID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to this record of Allegation-related responses, vital to associating with all other Complaint data.',
			'FldForeignTable' => '112',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '878800',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1092,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'StopYN',
			'FldEng' => 'Did an officer stop or detain anyone during this incident?',
			'FldDesc' => 'Indicates whether or not an Officer detained anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1093,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'StopWrongful',
			'FldEng' => 'Do do you believe anybody was stopped wrongfully?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful stop of at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1094,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerID',
			'FldEng' => 'Did anybody ask for an officer\'s identification?',
			'FldDesc' => 'Indicates whether or not anybody asked for any Officer\'s identification. Storing this is important in determining whether or not to display the question regarding an Officer\'s refusal.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1095,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerRefuseID',
			'FldEng' => 'Did an officer refuse to provide identification?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is a potential Allegation regarding an Officer refusing to provide identification. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1096,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'SearchYN',
			'FldEng' => 'Did an officer search anyone during this incident?',
			'FldDesc' => 'Indicates whether or not an Officer searched anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1097,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'SearchWrongful',
			'FldEng' => 'Do do you believe anybody was searched wrongfully?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful search of at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1098,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'PropertyYN',
			'FldEng' => 'Was any property seized or damaged?',
			'FldDesc' => 'Indicates whether or not an Officer seized or damaged any property during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1099,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'PropertyWrongful',
			'FldEng' => 'Do you believe this property was wrongfully seized or damaged?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful seizure or destruction of at least one Civilian\'s property. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1100,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'ForceYN',
			'FldEng' => 'Did an officer use physical force against anyone during this incident?',
			'FldDesc' => 'Indicates whether or not an Officer used physical force against anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1101,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'ForceUnreason',
			'FldEng' => 'Do you believe the use of physical force on someone was unreasonable?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the unreasonable use of force against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1102,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'ArrestYN',
			'FldEng' => 'Did an officer arrest anyone during this incident?',
			'FldDesc' => 'Indicates whether or not an Officer arrested anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1103,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'ArrestWrongful',
			'FldEng' => 'Do do you believe anybody was arrested wrongfully?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful arrest of at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1104,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'ArrestMiranda',
			'FldEng' => 'Did the officer fail to read any arrestees their Miranda rights?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding a failure to read Miranda rights during at least one Arrest. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1105,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'CitationYN',
			'FldEng' => 'Did anyone get a ticket or citation?',
			'FldDesc' => 'Indicates whether or not an Officer cited anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1106,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'ArrestRetaliatory',
			'FldEng' => 'Do you believe the charges filed against anyone were retaliatory?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding retaliatory charges against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1107,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'Procedure',
			'FldEng' => 'Do you believe an officer took actions which did not follow appropriate policy or procedure?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding Officer actions which did not follow appropriate policy or procedure. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1108,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '21',
			'FldSpecSource' => '0',
			'FldName' => 'SexualAssault',
			'FldEng' => 'Did an officer sexually assault someone during the incident?',
			'FldDesc' => 'Indicates whether or not an Officer sexually assaulted anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1109,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '19',
			'FldSpecSource' => '0',
			'FldName' => 'Bias',
			'FldEng' => 'Do you believe the officer was policing based on bias?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding policing based on bias. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1110,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '24',
			'FldSpecSource' => '0',
			'FldName' => 'Retaliation',
			'FldEng' => 'Do you believe any officers used force or made arrests as an act of retaliation?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding retaliatory arrests against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1111,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '25',
			'FldSpecSource' => '0',
			'FldName' => 'Unbecoming',
			'FldEng' => 'Do you believe an officer\'s conduct was improper or unbecoming?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding conduct unbecoming of an Officer. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1112,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '26',
			'FldSpecSource' => '0',
			'FldName' => 'Discourteous',
			'FldEng' => 'Was an officer discourteous in ways not included in other allegations?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding at least one Officer\'s discourtesy. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1113,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '36',
			'FldSpecSource' => '0',
			'FldName' => 'HoneyPot',
			'FldEng' => 'Honey Pot (to catch spam bots)',
			'FldDesc' => 'Indicates whether or not a spam bot filled in the form field which human users cannot see. This is important for quickly categorizing Complaints as spam.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',',
			'FldKeyType' => ',',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1114,
			'FldDatabase' => '1',
			'FldTable' => '153',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'GenderOther',
			'FldEng' => 'Gender Other',
			'FldDesc' => 'Other gender classifications of a Civilian or Officer, with suggestions provided by the Definitions for Gender Identity. This category is used to help verify a Civilian or Officer\'s identity.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1115,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerInjured',
			'FldEng' => 'Was an officer injured?',
			'FldDesc' => 'Indicates whether or not any Officer was also injured during a specific Use of Force. This is important for tracking an acknowledging the fact that there is more than one side to every story.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1116,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'OfficerInjuredDesc',
			'FldEng' => 'Officer injury descriptions',
			'FldDesc' => 'Further describes Officers\' injuries incurred during a specific Use of Force. This is important for tracking an acknowledging the fact that there is more than one side to every story.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1117,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'NeglectDuty',
			'FldEng' => 'Do you believe an officer failed to take actions which did not follow appropriate procedure?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding Officer inactions which did not follow appropriate policy or procedure. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1118,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'TriedOtherWays',
			'FldEng' => 'Did you try to submit your complaint some other way before using OPC?',
			'FldDesc' => 'Indicates whether or not the Complainant previously attempted to formally submit their police complaint before using OPC. This is important for research about the use of OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1119,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'TriedOtherWaysDesc',
			'FldEng' => 'Describe what else you\'ve tried:',
			'FldDesc' => 'The Complainant can describe how else they previously attempted to formally submit their police complaint before using OPC. This is important for research about the use of OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1121,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '23',
			'FldSpecSource' => '0',
			'FldName' => 'HowHear',
			'FldEng' => 'How did you hear about us?',
			'FldDesc' => 'Indicates how the Complainant heard about Open Police Complaints. This is important for internal understanding of OPC marketing strategies.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldCompareSame' => '878800',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1122,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'AttorneyHas',
			'FldEng' => 'Are you already represented by an attorney in this matter?',
			'FldDesc' => 'Indicates whether or not the Complainant is already represented by an attorney. This is important for understanding the incident\'s legal situation and as a safety check to minimize risks.',
			'FldNotes' => 'eg. http://www.aclupa.org/our-work/legal/fileacomplaint/electronic-complaint-form/',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldRequired' => '1',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1123,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'AttorneyWant',
			'FldEng' => 'Do you want an attorney?',
			'FldDesc' => 'Indicates whether or not the Complainant would like an attorney. This is important for understanding the incident\'s legal situation and as a safety check to minimize potential risks of using OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1124,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'AllChargesResolved',
			'FldEng' => 'Have ALL of these charges been resolved?',
			'FldDesc' => 'Indicates whether or not all criminal charges have been resolved. This is important for understanding the Incident\'s legal situation and as a safety check to minimize potential risks of using OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1125,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '19',
			'FldSpecSource' => '0',
			'FldName' => 'UnresolvedChargesActions',
			'FldEng' => 'There are unresolved charges, but I still want to use OPC now...',
			'FldDesc' => 'Indicates whether or not the Complainant has explicitly chosen to use OPC despite legal situations related to the Incident. This is important for understanding the incident\'s legal situation and as a safety check to minimize potential risks of using OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Unresolved Charges Actions',
			'FldDefault' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1126,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'HadVehicle',
			'FldEng' => 'Using a vehicle at the start of the incident?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide information about the vehicle this Civilian was in. This is important for later reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1127,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'HadVehicle',
			'FldEng' => 'Using a vehicle at the start of the incident?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide information about the vehicle this Officer was in. This is important for later reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1128,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'GiveName',
			'FldEng' => 'Do you know their name, badge number or similar information?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide information about the name and/or badge number(s) of this Officer. This is important for reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1129,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'GiveName',
			'FldEng' => 'Do you know their name?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide information about the name of this Civilian. This is important for later reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1130,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'GiveContactInfo',
			'FldEng' => 'Do you know any of their contact information?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide any contact information for this Civilian. This is important for later reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1133,
			'FldDatabase' => '1',
			'FldTable' => '157',
			'FldSpecSource' => '0',
			'FldName' => 'CivID',
			'FldEng' => 'Civilian ID',
			'FldDesc' => 'This helps to link one Civilian to one Allegation, via the Civilian\'s primary ID.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1134,
			'FldDatabase' => '1',
			'FldTable' => '157',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'AlleID',
			'FldEng' => 'Allegation ID',
			'FldDesc' => 'This helps to link one Civilian to one Allegation, via the Allegation\'s primary ID.',
			'FldForeignTable' => '113',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1135,
			'FldDatabase' => '1',
			'FldTable' => '158',
			'FldSpecSource' => '0',
			'FldName' => 'OffID',
			'FldEng' => 'Officer ID',
			'FldDesc' => 'This helps to link one Officer to one Allegation, via the Officer\'s primary ID.',
			'FldForeignTable' => '104',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1136,
			'FldDatabase' => '1',
			'FldTable' => '158',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'AlleID',
			'FldEng' => 'Allegation ID',
			'FldDesc' => 'This helps to link one Officer to one Allegation, via the Allegation\'s primary ID.',
			'FldForeignTable' => '113',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1137,
			'FldDatabase' => '1',
			'FldTable' => '159',
			'FldSpecSource' => '0',
			'FldName' => 'OffID',
			'FldEng' => 'Officer ID',
			'FldDesc' => 'This helps to link one Officer to one verbal Order, via the Officer\'s primary ID.',
			'FldForeignTable' => '104',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1138,
			'FldDatabase' => '1',
			'FldTable' => '159',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'OrdID',
			'FldEng' => 'Order ID',
			'FldDesc' => 'This helps to link one Officer to one verbal Order given, via the Order\'s primary ID.',
			'FldForeignTable' => '120',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1139,
			'FldDatabase' => '1',
			'FldTable' => '160',
			'FldSpecSource' => '0',
			'FldName' => 'OffID',
			'FldEng' => 'Officer ID',
			'FldDesc' => 'This helps to link one Officer to one Event (which represents one Stop, Search, Use of Force, or Arrest), via the Officer\'s primary ID.',
			'FldForeignTable' => '104',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1140,
			'FldDatabase' => '1',
			'FldTable' => '160',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'EveID',
			'FldEng' => 'Event ID',
			'FldDesc' => 'This helps to link one Officer to one Event (which represents one Stop, Search, Use of Force, or Arrest), via the Event\'s primary ID.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1141,
			'FldDatabase' => '1',
			'FldTable' => '161',
			'FldSpecSource' => '0',
			'FldName' => 'CivID',
			'FldEng' => 'Civilian ID',
			'FldDesc' => 'This helps to link one Civilian to one verbal Order, via the Civilian\'s primary ID.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1142,
			'FldDatabase' => '1',
			'FldTable' => '161',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'OrdID',
			'FldEng' => 'Order ID',
			'FldDesc' => 'This helps to link one Civilian to one verbal Order given, via the Order\'s primary ID.',
			'FldForeignTable' => '120',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1143,
			'FldDatabase' => '1',
			'FldTable' => '162',
			'FldSpecSource' => '0',
			'FldName' => 'CivID',
			'FldEng' => 'Civilian ID',
			'FldDesc' => 'This helps to link one Civilian to one Event (which represents one Stop, Search, Use of Force, or Arrest), via the Civilian\'s primary ID.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1144,
			'FldDatabase' => '1',
			'FldTable' => '162',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'EveID',
			'FldEng' => 'Event ID',
			'FldDesc' => 'This helps to link one Civilian to one Event (which represents one Stop, Search, Use of Force, or Arrest), via the Event\'s primary ID.',
			'FldForeignTable' => '137',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Primary,Foreign,',
			'FldKeyStruct' => 'Composite',
			'FldValuesEnteredBy' => 'System',
			'FldCompareSame' => '878800',
			'FldCompareOther' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1145,
			'FldDatabase' => '1',
			'FldTable' => '163',
			'FldSpecSource' => '0',
			'FldName' => 'CivID',
			'FldEng' => 'Civilian ID',
			'FldDesc' => 'The unique number of the Civilian record being linked in this record. Vital for associating Civilians with the Vehicle.',
			'FldForeignTable' => '102',
			'FldForeignMin' => '0',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1146,
			'FldDatabase' => '1',
			'FldTable' => '163',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'VehicID',
			'FldEng' => 'Vehicle ID',
			'FldDesc' => 'The unique number of the Vehicle record being linked in this record. Vital for associating Civilians with the Vehicle.',
			'FldForeignTable' => '152',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1147,
			'FldDatabase' => '1',
			'FldTable' => '163',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Role',
			'FldEng' => 'Vehicle Role',
			'FldDesc' => 'When a Vehicle is associated with multiple Civilians, this information is important for identifier for which person was driving a Vehicle.',
			'FldForeignTable' => '0',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Driver;Passenger',
			'FldDataLength' => '10',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1148,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '35',
			'FldSpecSource' => '0',
			'FldName' => 'TreeVersion',
			'FldEng' => 'Tree Version',
			'FldDesc' => 'Indicates which precise version number of this software was running at the time of this Complaint\'s submission. This is important for internal use, quality control, and potential debugging.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '50',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldCompareSame' => '54925',
			'FldOpts' => '13'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1150,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'AttorneyOKedOPC',
			'FldEng' => 'Attorney said complainant could use Open Police Complaints?',
			'FldDesc' => 'Indicates whether or not the Complainant\'s attorney explicitly approved their use of Open Police Complaints. This is an important safety check to minimize potential risks of using OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '54925'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1151,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'AnyoneCharged',
			'FldEng' => 'Is anyone involved in this event now under arrest, OR has anyone been charged with a crime?',
			'FldDesc' => 'Indicates whether or not any Civilians were arrested or charged with a crime. This is important for understanding the Incident\'s legal situation and as a safety check to minimize potential risks of using OPC.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldRequired' => '1',
			'FldCompareSame' => '54925'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1152,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'InPreviousVehicle',
			'FldEng' => 'Have you already described this vehicle?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide information about a vehicle not previously described in this Complaint. This is important for later reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1153,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'InPreviousVehicle',
			'FldEng' => 'Have you already described this vehicle?',
			'FldDesc' => 'Indicates whether or not the Complainant intends to provide information about a vehicle not previously described in this Complaint. This is important for later reloading Complaint forms with the user\'s previous response.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1154,
			'FldDatabase' => '1',
			'FldTable' => '137',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'Event Type',
			'FldDesc' => 'Indicates whether this record is the type of Event representing a Stop, Search, Use of Force, or Arrest. This is important for determining how to handle this Event throughout the system.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Stops;Searches;Force;Arrests',
			'FldDataLength' => '0',
			'FldCharSupport' => ',',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1155,
			'FldDatabase' => '1',
			'FldTable' => '137',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'UserFinished',
			'FldEng' => 'User Finished Event',
			'FldDesc' => 'Internally indicates whether or not the Complainant completed the final questions related to a Stop, Search, Use of Force, or Arrest. This is important for determining when the Complainant has completed all dynamically generated sections describing \'What Happened\'.',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1156,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '23',
			'FldSpecSource' => '0',
			'FldName' => 'AllegWrongfulStop',
			'FldEng' => 'Do you believe they were wrongfully stopped or questioned?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful stop of at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1157,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '24',
			'FldSpecSource' => '0',
			'FldName' => 'AllegWrongfulEntry',
			'FldEng' => 'Did an officer enter anyway without a warrant?',
			'FldDesc' => 'Indicates whether or not an Officer entered a space without a warrant. This is important for determining the details of justified entry on private property.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1158,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '19',
			'FldSpecSource' => '0',
			'FldName' => 'AllegWrongfulSearch',
			'FldEng' => ' Do you believe they were wrongfully searched?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful search of at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1159,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '20',
			'FldSpecSource' => '0',
			'FldName' => 'AllegWrongfulProperty',
			'FldEng' => 'Do you believe this property was wrongfully seized?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful seizure of at least one Civilian\'s property. Such Allegation claims are central to Complaints.',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1160,
			'FldDatabase' => '1',
			'FldTable' => '116',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'AllegUnreasonable',
			'FldEng' => 'Do you believe this use of force was unreasonable?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful use of force against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1161,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'AllegWrongfulArrest',
			'FldEng' => 'Do do you believe they were wrongfully arrested?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful arrest of at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1162,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'AllegRetaliatoryCharges',
			'FldEng' => 'Do you believe the charges were retaliatory?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding retaliatory arrest charges against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1163,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'ChargesFiled',
			'FldEng' => 'Were any charges filed?',
			'FldDesc' => 'Indicates whether or not any charges were filed as a part of this specific Arrest. This is important for determining the risk involved with this Complaint, as well as revealing fields to describe the specific Charges filed.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1164,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '25',
			'FldSpecSource' => '0',
			'FldName' => 'AllegRetaliatoryCharges',
			'FldEng' => 'Do you believe the charges were retaliatory?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding retaliatory citation charges against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1165,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'CitationExcessive',
			'FldEng' => 'Do you believe the citations filed against anyone were excessive?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding retaliatory citation charges against at least one Civilian. Such Allegation claims are central to Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1166,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'Strip',
			'FldEng' => 'Strip Searched?',
			'FldDesc' => 'Indicates whether or not an Officer performed a strip search on a Subject. Important for evaluating possible wrongful search Allegation and identifying search trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1167,
			'FldDatabase' => '1',
			'FldTable' => '123',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'StripSearchDesc',
			'FldEng' => 'Strip Search Description',
			'FldDesc' => 'Narrative details about the nature of the strip search. Important for identifying particularly egregious wrongful search Allegations -- including cavity searches. ',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1168,
			'FldDatabase' => '1',
			'FldTable' => '164',
			'FldSpecSource' => '0',
			'FldName' => 'PhysDescID',
			'FldEng' => 'Physical Description ID',
			'FldDesc' => 'The primary Physical Description record number connected to this race association. Important for multiple races to a single Civilian or Officer.',
			'FldForeignTable' => '153',
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
			'FldID' => 1169,
			'FldDatabase' => '1',
			'FldTable' => '164',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Race',
			'FldEng' => 'Race',
			'FldDesc' => 'The Complainant-selected or self-ascribed racial classification of a Civilian or Officer. This classification helps verify the identity of Subject of Complaints and is useful for determining possible patterns or practices of race-based policing.',
			'FldNotes' => 'Depending on who is selecting, this might be self-ascribed or Complainant-selected for another Civilian. We will use the Census Bureau\'s classifications: http://www.census.gov/topics/population/race/about.html',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Races',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1170,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Status',
			'FldEng' => 'Activity Status',
			'FldDesc' => 'The current activity status of the Department, 1 for active, 0 for inactive. This vital for identifying Departments which have been closed since the creation of the original data set.',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Department Status',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1171,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'IsVehicle',
			'FldEng' => 'Did this incident begin with a vehicle stop?',
			'FldDesc' => 'Whether or not an Incident began with a vehicle, which includes cars, motorcycles, bicycles, and boats. This provides important contextual information about the the nature of the Incident to better evaluate the Incident and Allegations.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1172,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'YouTube',
			'FldEng' => 'YouTube URL',
			'FldDesc' => 'This is the URL address of a Agency\'s YouTube page. If available, this will be included in each Agency\'s OPC page so that members of public see this aspect of the department\'s web presence.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1173,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'Chase',
			'FldEng' => 'At any point, was the Subject chased by the police?',
			'FldDesc' => 'Indicates whether or not Subject was chased by an Officer. Important for evaluating Allegations of Excessive Force and tracking Force trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1174,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'ChaseType',
			'FldEng' => 'What kind of chase was it?',
			'FldDesc' => 'Indicates whether the Subject\'s chase took place on foot, in vehicles, or both. Important for evaluating Allegations of Excessive Force and tracking Force trends.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1175,
			'FldDatabase' => '1',
			'FldTable' => '152',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Unmarked',
			'FldEng' => 'Was the vehicle unmarked or undercover?',
			'FldDesc' => 'Indicates whether or not an Officer\'s vehicle was an unmarked car, meaning it looks like a normal vehicle with no badges, graphics, or visible police lights. This is important for tracking trends of Officer behavior.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1176,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'Uniform',
			'FldEng' => 'Was this officer wearing a uniform?',
			'FldDesc' => 'Indicates whether or not Officer was wearing a uniform during the Incident. Particularly important for investigating Allegations against Officers in plain clothes.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1177,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '37',
			'FldSpecSource' => '0',
			'FldName' => 'IsMobile',
			'FldEng' => 'Using Mobile Device?',
			'FldDesc' => 'Indicates whether or not this Complaint was started on a mobile device. This is important for tracking trends of usage, and potential debugging.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1178,
			'FldDatabase' => '1',
			'FldTable' => '165',
			'FldSpecSource' => '0',
			'FldName' => 'ComID',
			'FldEng' => 'Complaint ID',
			'FldDesc' => 'The unique number of the Complaint record related to these uses of force against an officer. Vital for associating multiple secondary types of force used with a single Use of Force during an Incident.',
			'FldForeignTable' => '116',
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
			'FldID' => 1179,
			'FldDatabase' => '1',
			'FldTable' => '165',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'BodyWeapon',
			'FldEng' => 'Civilian Body Weapon',
			'FldDesc' => 'The Definition ID number of the type of Body Weapon used by the Civilian against the Officer. Vital for associating multiple Body Weapons with a single Use of Force during an Incident.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1180,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldSpecSource' => '0',
			'FldName' => 'VictimHadWeapon',
			'FldEng' => 'Victim Had A Weapon',
			'FldDesc' => 'Indicates whether or not the Victim of this Use of Force seemed to have a weapon at the time of the Incident. This is important for determining if the Officer used Unreasonable Force.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1181,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldSpecSource' => '0',
			'FldName' => 'VictimWhatWeapon',
			'FldEng' => 'What type of Victim weapon?',
			'FldDesc' => 'Indicates what type of weapon the Victim of this Use of Force seemed to have at the time of the Incident. This is important for determining if the Officer used Unreasonable Force.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Civilian Weapons',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1182,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldSpecSource' => '0',
			'FldName' => 'VictimUseWeapon',
			'FldEng' => 'Did the victim use the weapon at all?',
			'FldDesc' => 'Indicates how the Victim used a weapon during this Use of Force. This is important for determining if the Officer used Unreasonable Force.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '0',
			'FldForeignMax' => '0',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Def::Intimidating Displays Of Weapon',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1219,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '5',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '834',
			'FldName' => 'NamePrefix',
			'FldEng' => 'Name Prefix',
			'FldDesc' => 'Letters placed before a person\'s first name that we use in all formal communications with persons or regarding Complaints.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1220,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '6',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '830',
			'FldName' => 'NameFirst',
			'FldEng' => 'First Name',
			'FldDesc' => 'The legal given name of persons that we use in all formal communications with persons or regarding Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1221,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '8',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '832',
			'FldName' => 'NameMiddle',
			'FldEng' => 'Middle Name',
			'FldDesc' => 'The name of a person, placed between their first name and before their surname, that we use in all formal communications with persons or regarding Complaints.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1222,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '9',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '835',
			'FldName' => 'NameLast',
			'FldEng' => 'Last Name',
			'FldDesc' => 'The legal surname of persons that we use in formal communications with or regarding Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1223,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '10',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '833',
			'FldName' => 'NameSuffix',
			'FldEng' => 'Name Suffix',
			'FldDesc' => 'Letters added after a person\'s last name related to this record -- which provide additional information about their title or inherited name -- that we use in all formal communications with persons or regarding Complaints.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1233,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '20',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '846',
			'FldName' => 'Birthday',
			'FldEng' => 'Date of Birth',
			'FldDesc' => 'The month, day, and year a person was born. This information is used to help verify a person\'s identity.',
			'FldNotes' => 'If Date of Birth is unknown, the "Age" field will become visible, which offers age ranges to be selected.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'DATE',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Keyboard,Numbers,',
			'FldInputMask' => 'YYYY-MM-DD',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldCompareOther' => '6',
			'FldCompareValue' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1265,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '6',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '834',
			'FldName' => 'NamePrefix',
			'FldEng' => 'Name Prefix',
			'FldDesc' => 'Letters placed before an Oversight Agency contact\'s first name that we use in all formal communications with Oversight Agency contacts or regarding Oversight Agency contacts.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1266,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '7',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '830',
			'FldName' => 'NameFirst',
			'FldEng' => 'First Name',
			'FldDesc' => 'The legal given name of an Oversight Agency contact that we use to identify them. We also use this in all formal communications with Oversight Agency contacts and regarding Oversight Agency contacts.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1267,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '9',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '832',
			'FldName' => 'NameMiddle',
			'FldEng' => 'Middle Name',
			'FldDesc' => 'The name of an Oversight Agency contact, placed between their first name and before their surname, that we use in all formal communications with Oversight Agency contacts or regarding Oversight Agency contacts.',
			'FldNotes' => 'Use only if selected. Field may otherwise be left blank',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '100',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1268,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '10',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '835',
			'FldName' => 'NameLast',
			'FldEng' => 'Last Name',
			'FldDesc' => 'The legal surname of an Oversight Agency contact that we use in formal communications with or regarding an Oversight Agency contact.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1269,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '11',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '833',
			'FldName' => 'NameSuffix',
			'FldEng' => 'Name Suffix',
			'FldDesc' => 'Letters added after an Oversight Agency contact\'s last name -- which provide additional information about their title or inherited name -- that we use in all formal communications with Oversight Agency contacts or regarding Oversight Agency contacts.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1336,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '13',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '955',
			'FldName' => 'OfficerRank',
			'FldEng' => 'Officer Rank',
			'FldDesc' => 'This indicates an Officer\'s position within the Department. This information might help us verify an Officer\'s identity.',
			'FldNotes' => 'Option list from Philly PD, adapt as needed.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldValues' => 'Police Officer;Detective;Corporal;Sergeant;Lieutenant;Captain;Staff Inspector;Inspector;Chief Inspector;Deputy Commissioner;Commissioner;?',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1338,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '12',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '965',
			'FldName' => 'IDnumber',
			'FldEng' => 'Additional ID#',
			'FldDesc' => 'A secondary number that Departments assign to Officers, which stays consistent over time. This information might help us verify an Officer\'s identity.',
			'FldNotes' => 'Eg. in New York, officers have a consistent Tax ID. This might help us track Officers who obtain a new rank, or move to a different precinct or district.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1339,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '11',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '956',
			'FldName' => 'BadgeNumber',
			'FldEng' => 'Badge Number',
			'FldDesc' => 'An identification number Departments assign to Officers. This information might help us verify an Officer\'s identity.',
			'FldNotes' => 'We might want to tell users that they don\'t necessarily need a badge number to identify Officers',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '7'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1341,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '920',
			'FldName' => 'DeptID',
			'FldEng' => 'Department ID',
			'FldDesc' => 'The unique number of the Department record related to this Officer. Vital for associating this Officer with other Department data.
',
			'FldForeignTable' => '111',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1369,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '12',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '922',
			'FldName' => 'Title',
			'FldEng' => 'Title',
			'FldDesc' => 'The job position of an Oversight Agency contact. We might use this in formal communications with Oversight Agency contacts or regarding Oversight Agency contacts.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyType' => 'Non',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '130',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1370,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'IDnumber',
			'FldEng' => 'ID Number',
			'FldDesc' => 'A unique number used to identify each Oversight Agency. We assign a number to all active Oversight Agencies and remains with them for the duration of their existence.',
			'FldForeignTable' => '0',
			'FldForeignMin' => '11',
			'FldForeignMax' => '11',
			'FldForeign2Min' => '0',
			'FldForeign2Max' => '0',
			'FldDataLength' => '50',
			'FldCharSupport' => ',Letters,Keyboard,Numbers,',
			'FldKeyStruct' => 'Simple',
			'FldCompareSame' => '6',
			'FldOpts' => '11'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1372,
			'FldDatabase' => '1',
			'FldTable' => '106',
			'FldOrd' => '3',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '920',
			'FldName' => 'DeptID',
			'FldEng' => 'Department ID',
			'FldDesc' => 'The unique number of the Department record related to this Oversight Agency. This information helps us identify the proper Oversight Agency with jurisdiction over an OPC Complaint directed at a specific Police Department.

',
			'FldForeignTable' => '111',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '11',
			'FldForeign2Max' => '11',
			'FldIsIndex' => '1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldKeyStruct' => 'Simple',
			'FldEditRule' => 'NowAllowed',
			'FldNullSupport' => '0',
			'FldRequired' => '1',
			'FldCompareSame' => '130'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1373,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'UsedProfanity',
			'FldEng' => 'Used Profanity?',
			'FldDesc' => 'Indicates whether or not this Civilian used profanity during the Incident. This information might help establish credibility of the Complainant.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1374,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'UsedProfanity',
			'FldEng' => 'Used Profanity?',
			'FldDesc' => 'Indicates whether or not this Officer used profanity during the Incident. This information helps flag a certain type of discourtesy.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1375,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '28',
			'FldSpecSource' => '0',
			'FldName' => 'BreathCannabis',
			'FldEng' => 'Did officer give a marijuana breath test?',
			'FldDesc' => 'Indicates whether or not an Officer administered a marijuana breath test.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1376,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '29',
			'FldSpecSource' => '0',
			'FldName' => 'BreathCannabisFailed',
			'FldEng' => 'Did officer say a marijuana breath test was failed?',
			'FldDesc' => 'Indicates whether or not an Officer administered a marijuana breath test, and claimed someone failed.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1377,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '27',
			'FldSpecSource' => '0',
			'FldName' => 'BreathAlcoholFailed',
			'FldEng' => 'Did officer say an alcohol breath test was failed?',
			'FldDesc' => 'Indicates whether or not an Officer administered an alcohol breath test, and claimed someone failed.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1378,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '26',
			'FldSpecSource' => '0',
			'FldName' => 'BreathAlcohol',
			'FldEng' => 'Did officer give an alcohol breath test?',
			'FldDesc' => 'Indicates whether or not an Officer administered an alcohol breath test.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1379,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '30',
			'FldSpecSource' => '0',
			'FldName' => 'SalivaTest',
			'FldEng' => 'Did an officer collect saliva?',
			'FldDesc' => 'Indicates whether or not an Officer collected someone\'s saliva.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1380,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '31',
			'FldSpecSource' => '0',
			'FldName' => 'SobrietyOther',
			'FldEng' => 'Did an officer give other sobriety tests?',
			'FldDesc' => 'Indicates whether or not an Officer administered other sobriety tests.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1381,
			'FldDatabase' => '1',
			'FldTable' => '121',
			'FldOrd' => '32',
			'FldSpecSource' => '0',
			'FldName' => 'SobrietyOtherDescribe',
			'FldEng' => 'Describe the other sobriety tests:',
			'FldDesc' => 'Description of other sobriety tests administered by Officer.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldKeyType' => ',',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1382,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '24',
			'FldSpecSource' => '0',
			'FldName' => 'Feedback',
			'FldEng' => 'User Suggestions and Feedback',
			'FldDesc' => 'Indicates any suggestions or feedback the user has for us at the end of the process. This is important to let us know if we missed anything, etc.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1389,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '0',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Experience.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1390,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '34',
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
			'FldID' => 1391,
			'FldDatabase' => '1',
			'FldTable' => '168',
			'FldSpecType' => 'Replica',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Experience.',
			'FldForeignTable' => '167',
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
			'FldID' => 1392,
			'FldDatabase' => '1',
			'FldTable' => '168',
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
			'FldID' => 1393,
			'FldDatabase' => '1',
			'FldTable' => '168',
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
			'FldID' => 1394,
			'FldDatabase' => '1',
			'FldTable' => '168',
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
			'FldID' => 1395,
			'FldDatabase' => '1',
			'FldTable' => '168',
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
			'FldID' => 1396,
			'FldDatabase' => '1',
			'FldTable' => '168',
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
			'FldID' => 1397,
			'FldDatabase' => '1',
			'FldTable' => '168',
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
			'FldID' => 1399,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '20',
			'FldSpecSource' => '0',
			'FldName' => 'SexualHarass',
			'FldEng' => 'Did an officer sexually harass someone during the incident?',
			'FldDesc' => 'Indicates whether or not an Officer sexually harassed anyone during the Incident. This is important for later reloading Complaint forms for silver Allegations with the user\'s previous response.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1400,
			'FldDatabase' => '1',
			'FldTable' => '104',
			'FldOrd' => '20',
			'FldSpecSource' => '0',
			'FldName' => 'GaveCompliment',
			'FldEng' => 'Gave Compliment',
			'FldDesc' => 'Indicates whether or not the Complainant completed a Compliment page for this Officer.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1401,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldSpecSource' => '0',
			'FldName' => 'OffID',
			'FldEng' => 'Officer ID',
			'FldDesc' => 'The unique number of the Officer record being linked in this record. Vital for associating an Officer with this row\'s Compliments.',
			'FldForeignTable' => '104',
			'FldForeignMin' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1402,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Valor',
			'FldEng' => 'Valor',
			'FldDesc' => 'Indicates whether or not the officer showed extraordinary courage in the face of danger.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1403,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Lifesaving',
			'FldEng' => 'Lifesaving',
			'FldDesc' => 'Indicates whether or not the officer applied medical aid exceeding the normal call of duty.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1404,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Deescalation',
			'FldEng' => 'De-escalation',
			'FldDesc' => 'Indicates whether or not the officer skillfully calmed down a tense situation, using minimal or no force.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1405,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Professionalism',
			'FldEng' => 'Professionalism',
			'FldDesc' => 'Indicates whether or not the officer behaved in a courteous, respectful, and straightforward manner.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1406,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'Fairness',
			'FldEng' => 'Fairness',
			'FldDesc' => 'Indicates whether or not the officer’s use of power was reasonable, appropriate, and free from bias.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1407,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'Constitutional',
			'FldEng' => 'Constitutional Policing',
			'FldDesc' => 'Indicates whether or not the officer’s words and actions showed respect for the Bill of Rights.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1408,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'Compassion',
			'FldEng' => 'Compassion',
			'FldDesc' => 'Indicates whether or not the officer displayed empathy and generosity beyond the call of duty.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1409,
			'FldDatabase' => '1',
			'FldTable' => '169',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'Community',
			'FldEng' => 'Community Service',
			'FldDesc' => 'Indicates whether or not the officer engaged the community to build bonds of trust.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1410,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldSpecSource' => '0',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Experience.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => '0',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1411,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '13',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '0',
			'FldName' => 'VersionAB',
			'FldEng' => 'A/B Testing Version',
			'FldDesc' => 'Stores a complex string reflecting all A/B Testing variations in effect at the time of this User\'s Experience of this Node.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '0',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1412,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Status',
			'FldEng' => 'OPC Complaint Status',
			'FldDesc' => 'The current progress of a "complete" or "incomplete" Compliment within the OPC system. We use this information internally to determine next Administrator actions to guide a Compliment to the final status of "closed."',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Compliment Status',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1413,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Type',
			'FldEng' => 'OPC Complaint Type',
			'FldDesc' => 'The Administrator-selected category for newly-submitted Compliment records. Essential for determining where and how new Compliment records are stored and shared.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '3',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1414,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'SubmissionProgress',
			'FldEng' => 'Submission Progress',
			'FldDesc' => 'Indicates current progress of an "incomplete" Compliment. Important for identifying problem areas which might cause Compliments to be left unfinished.',
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
			'FldID' => 1415,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'TreeVersion',
			'FldEng' => 'Tree Version',
			'FldDesc' => 'Indicates which precise version number of this software was running at the time of this Compliment\'s submission. This is important for internal use, quality control, and potential debugging.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '50',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1416,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'IncidentID',
			'FldEng' => 'Incident ID',
			'FldDesc' => 'The unique number of the Incident record related to this Compliment. This number helps us identify the Incident that this Compliment documents.',
			'FldForeignTable' => '114',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1417,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'SceneID',
			'FldEng' => 'Scene ID',
			'FldDesc' => 'The unique number of the Scene record related to this Compliment. This number helps us identify the Scene that this compliment documents.',
			'FldForeignTable' => '115',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1418,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'SceneID',
			'FldEng' => 'Scene ID',
			'FldDesc' => 'The unique number of the Scene record related to this Complaint. This number helps us identify the Scene that this Complaint documents.',
			'FldForeignTable' => '115',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1419,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'Privacy',
			'FldEng' => 'Privacy',
			'FldDesc' => 'User-selected category for Compliment records. This defines what personally identifiable information (PII) is publicly shared.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Def::Privacy Types',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1420,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'Summary',
			'FldEng' => 'Summary (Narrative)',
			'FldDesc' => 'The Complainant narrative describes the chronological sequence of key Incident events and Commendations. This story brings to life to an otherwise clinical and legalistic Compliment document.',
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
			'FldID' => 1421,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'HowHear',
			'FldEng' => 'How did you hear about us?',
			'FldDesc' => 'Indicates how the Complainant heard about Open Police Complaints. This is important for internal understanding of OPC marketing strategies.',
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
			'FldID' => 1422,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'Feedback',
			'FldEng' => 'User Suggestions and Feedback',
			'FldDesc' => 'Indicates any suggestions or feedback the user has for us at the end of the process. This is important to let us know if we missed anything, etc.',
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
			'FldID' => 1423,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'Slug',
			'FldEng' => 'URL Slug',
			'FldDesc' => 'This defines the version of the Compliment\'s Social Media Headline which is compatible and ideal to be used as part of a website URL. This is vital for creating public pages of the website which are intuitive and professional.',
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
			'FldID' => 1424,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'Notes',
			'FldEng' => 'Compliment Notes',
			'FldDesc' => 'Additional annotations related to this Compliment. Might add additional information or context regarding the Compliment.',
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
			'FldID' => 1425,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'RecordSubmitted',
			'FldEng' => 'Record Submitted',
			'FldDesc' => 'Date and time when this Compliment was completed and submitted to the OPC database.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'DATETIME',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1426,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '15',
			'FldSpecSource' => '0',
			'FldName' => 'HoneyPot',
			'FldEng' => 'Honey Pot (to catch spam bots)',
			'FldDesc' => 'Indicates whether or not a spam bot filled in the form field which human users cannot see. This is important for quickly categorizing Compliments as spam.',
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
			'FldID' => 1427,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '16',
			'FldSpecSource' => '0',
			'FldName' => 'IsMobile',
			'FldEng' => 'Using Mobile Device?',
			'FldDesc' => 'Indicates whether or not this Compliment was started on a mobile device. This is important for tracking trends of usage, and potential debugging.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1428,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'UniqueStr',
			'FldEng' => 'Unique String',
			'FldDesc' => 'This unique, randomly generated string can be important for creating custom URLs which are more secure than using the Compliment ID#.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldDataLength' => '20',
			'FldCharSupport' => ',Letters,Numbers,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1429,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'IPaddy',
			'FldEng' => 'IP Address (encrypted)',
			'FldDesc' => 'This logs an encrypted copy of the Complainants IP Address. This is important for checking if multiple Compliments were submitted from the same location, especially when filtering Compliments categorized as spam or abuse.',
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
			'FldID' => 1431,
			'FldDatabase' => '1',
			'FldTable' => '171',
			'FldSpecSource' => '0',
			'FldName' => 'ComplimentID',
			'FldEng' => 'Compliment ID',
			'FldDesc' => 'The unique number of the Compliment record, vital to associating with all other Compliment data.',
			'FldForeignTable' => '170',
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
			'FldID' => 1432,
			'FldDatabase' => '1',
			'FldTable' => '171',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'DeptID',
			'FldEng' => 'Department ID',
			'FldDesc' => 'The unique number of the Department record related to this Complaint. This number helps us identify which Department this Complaint is directed and therefore which Oversight Agency should be contacted.',
			'FldForeignTable' => '111',
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
			'FldID' => 1433,
			'FldDatabase' => '1',
			'FldTable' => '114',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'Public',
			'FldEng' => 'Publicly Mapped',
			'FldDesc' => 'Indicates whether or not the Complainant wants the address of this Incident made public for mapping and research purposes.',
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
			'FldID' => 1434,
			'FldDatabase' => '1',
			'FldTable' => '112',
			'FldOrd' => '40',
			'FldSpecSource' => '0',
			'FldName' => 'PublicID',
			'FldEng' => 'Public ID Number',
			'FldDesc' => 'Indicates the unique identification number for referring to this complaint in public and in URLs. Important for making public IDs counting only completed Complaints, not every partial submission.',
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
			'FldID' => 1435,
			'FldDatabase' => '1',
			'FldTable' => '170',
			'FldOrd' => '19',
			'FldSpecSource' => '0',
			'FldName' => 'PublicID',
			'FldEng' => 'Public ID Number',
			'FldDesc' => 'Indicates the unique identification number for referring to this Compliment in public and in URLs. Important for making public IDs counting only completed Compliments, not every partial submission.',
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
			'FldID' => 1436,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'HasInjury',
			'FldEng' => 'Has Injury?',
			'FldDesc' => 'Indicates whether or not Subject has at least one Injury from the Use of Force. Important for asking followup questions.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1437,
			'FldDatabase' => '1',
			'FldTable' => '102',
			'FldOrd' => '18',
			'FldSpecSource' => '0',
			'FldName' => 'HasInjuryCare',
			'FldEng' => 'Has Injury Care?',
			'FldDesc' => 'Indicates whether or not Subject received Medical Care for any Injuries from the Use of Force. Important for asking followup questions.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1438,
			'FldDatabase' => '1',
			'FldTable' => '117',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'Done',
			'FldEng' => 'Injury Form Done',
			'FldDesc' => 'Indicates whether or not the Complainant has gone through this Injury record yet.',
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
			'FldID' => 1439,
			'FldDatabase' => '1',
			'FldTable' => '146',
			'FldOrd' => '17',
			'FldSpecSource' => '0',
			'FldName' => 'Done',
			'FldEng' => 'Injury Care Form Done',
			'FldDesc' => 'Indicates whether or not the Complainant has gone through this Injury Care record yet.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => '0;1',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '1',
			'FldCharSupport' => ',',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1440,
			'FldDatabase' => '1',
			'FldTable' => '156',
			'FldOrd' => '27',
			'FldSpecSource' => '0',
			'FldName' => 'PropertyDamage',
			'FldEng' => 'Do you believe this property was wrongfully damaged?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful destruction of at least one Civilian\'s property. Such Allegation claims are central to Complaints.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1441,
			'FldDatabase' => '1',
			'FldTable' => '122',
			'FldOrd' => '21',
			'FldSpecSource' => '0',
			'FldName' => 'AllegPropertyDamage',
			'FldEng' => 'Do you believe this property was wrongfully damaged?',
			'FldDesc' => 'Indicates whether or not the Complainant believes there is an Allegation regarding the wrongful destruction of at least one Civilian\'s property. Such Allegation claims are central to Complaints.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1442,
			'FldDatabase' => '1',
			'FldTable' => '115',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'IsVehicleAccident',
			'FldEng' => 'Did this incident begin with a traffic accident?',
			'FldDesc' => 'Whether or not an Incident began with a vehicle accident. This provides important contextual information about the the nature of the Incident to better evaluate the Incident and Allegations.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldValues' => 'Y;N;?',
			'FldDataLength' => '1',
			'FldCharSupport' => ',Letters,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1443,
			'FldDatabase' => '1',
			'FldTable' => '154',
			'FldOrd' => '22',
			'FldSpecSource' => '0',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Contact Info.',
			'FldForeignTable' => '167',
			'FldForeignMin' => '0',
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
			'FldID' => 1451,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
			'FldSpecType' => 'Replica',
			'FldSpecSource' => '0',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User owning the data stored in this record for this Experience.',
			'FldForeignTable' => '167',
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
			'FldID' => 1452,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
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
			'FldID' => 1453,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
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
			'FldID' => 1454,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
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
			'FldID' => 1455,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
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
			'FldID' => 1456,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
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
			'FldID' => 1457,
			'FldDatabase' => '1',
			'FldTable' => '111',
			'FldOrd' => '100',
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
			'FldID' => 1458,
			'FldDatabase' => '1',
			'FldTable' => '172',
			'FldSpecSource' => '0',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User editing the original Departments record.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '11',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1459,
			'FldDatabase' => '1',
			'FldTable' => '172',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Duration',
			'FldEng' => 'Duration of Edit',
			'FldDesc' => 'How many seconds the user spent editing this record.',
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
			'FldID' => 1460,
			'FldDatabase' => '1',
			'FldTable' => '173',
			'FldSpecSource' => '0',
			'FldName' => 'ZedDeptID',
			'FldEng' => 'Department Edit Record ID',
			'FldDesc' => 'The unique ID number of the edit record tied to this extended edit record.',
			'FldForeignTable' => '172',
			'FldForeignMax' => '2',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1461,
			'FldDatabase' => '1',
			'FldTable' => '173',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'OnlineResearch',
			'FldEng' => 'Completed Online Research',
			'FldDesc' => 'Indicates whether or not the volunteer completed the online portion of the police department research process.',
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
			'FldID' => 1462,
			'FldDatabase' => '1',
			'FldTable' => '173',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'MadeDeptCall',
			'FldEng' => 'Made Department Call',
			'FldDesc' => 'Indicates whether or not the volunteer completed the research with a phone call to the police department.',
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
			'FldID' => 1463,
			'FldDatabase' => '1',
			'FldTable' => '173',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'MadeIACall',
			'FldEng' => 'Made Internal Affairs Call',
			'FldDesc' => 'Indicates whether or not the volunteer completed the research with a phone call to the internal affairs office.',
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
			'FldID' => 1464,
			'FldDatabase' => '1',
			'FldTable' => '173',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Notes',
			'FldEng' => 'Volunteer Notes',
			'FldDesc' => 'Provides a space for a volunteer to leave any significant notes about this department (or research attempt) for future volunteers or staff.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'TEXT',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Letters,Numbers,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1465,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldSpecSource' => '0',
			'FldName' => 'Date',
			'FldEng' => 'Date',
			'FldDesc' => 'Indicates the date for which this record\'s totals are cached.',
			'FldForeignMin' => 'N',
			'FldForeignMax' => 'N',
			'FldForeign2Min' => 'N',
			'FldForeign2Max' => 'N',
			'FldType' => 'DATE',
			'FldDataType' => 'DateTime',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,Keyboard,',
			'FldKeyType' => ',',
			'FldNullSupport' => '0',
			'FldCompareSame' => '6'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1466,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'Signups',
			'FldEng' => 'Total Signups',
			'FldDesc' => 'Indicates the total number of new volunteer signups on this date.',
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
			'FldID' => 1467,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Logins',
			'FldEng' => 'Total Logins',
			'FldDesc' => 'Indicates the total number of new volunteer logins on this date.',
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
			'FldID' => 1468,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'UsersUnique',
			'FldEng' => 'Total Unique Research Users',
			'FldDesc' => 'Indicates the total number of unique volunteers making research edits on this date.',
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
			'FldID' => 1469,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'DeptsUnique',
			'FldEng' => 'Total Unique Department Researched',
			'FldDesc' => 'Indicates the total number of unique departments receiving research edits on this date.',
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
			'FldID' => 1470,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'OnlineResearch',
			'FldEng' => 'Total Online Research Edits',
			'FldDesc' => 'Indicates the total number of online research edits on this date.',
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
			'FldID' => 1471,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'CallsDept',
			'FldEng' => 'Total Department Call Edits',
			'FldDesc' => 'Indicates the total number of department call edits on this date.',
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
			'FldID' => 1472,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'CallsIA',
			'FldEng' => 'Total Internal Affairs Call Edits',
			'FldDesc' => 'Indicates the total number of internal affairs call edits on this date.',
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
			'FldID' => 1473,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '8',
			'FldSpecSource' => '0',
			'FldName' => 'Tot',
			'FldEng' => 'Total Call Edits',
			'FldDesc' => 'Indicates the total number of department and internal affairs call edits on this date.',
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
			'FldID' => 1474,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '9',
			'FldSpecSource' => '0',
			'FldName' => 'TotalEdits',
			'FldEng' => 'Total Edits Saved',
			'FldDesc' => 'Indicates the total number of edits saved on this date.',
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
			'FldID' => 1475,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '10',
			'FldSpecSource' => '0',
			'FldName' => 'OnlineResearchV',
			'FldEng' => 'Total Online Research Volunteer Edits',
			'FldDesc' => 'Indicates the total number of online research edits by volunteers on this date.',
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
			'FldID' => 1476,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '11',
			'FldSpecSource' => '0',
			'FldName' => 'CallsDeptV',
			'FldEng' => 'Total Department Call Volunteer Edits',
			'FldDesc' => 'Indicates the total number of department call edits by volunteers on this date.',
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
			'FldID' => 1477,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '12',
			'FldSpecSource' => '0',
			'FldName' => 'CallsIAV',
			'FldEng' => 'Total Internal Affairs Call Volunteer Edits',
			'FldDesc' => 'Indicates the total number of internal affairs call edits by volunteers on this date.',
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
			'FldID' => 1478,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '13',
			'FldSpecSource' => '0',
			'FldName' => 'TotV',
			'FldEng' => 'Total Call Volunteer Edits',
			'FldDesc' => 'Indicates the total number of department and internal affairs call edits by volunteers on this date.',
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
			'FldID' => 1479,
			'FldDatabase' => '1',
			'FldTable' => '174',
			'FldOrd' => '14',
			'FldSpecSource' => '0',
			'FldName' => 'TotalEditsV',
			'FldEng' => 'Total Edits Saved by Volunteers',
			'FldDesc' => 'Indicates the total number of edits by volunteers saved on this date.',
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
			'FldID' => 1480,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldSpecSource' => '0',
			'FldName' => 'UserID',
			'FldEng' => 'User ID',
			'FldDesc' => 'Indicates the unique User ID number of the User record this table extends.',
			'FldForeignTable' => '167',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1481,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '1',
			'FldSpecSource' => '0',
			'FldName' => 'PersonContactID',
			'FldEng' => 'Person Contact Record ID',
			'FldDesc' => 'Indicates the unique Person Contact ID number for the record used to store this system User\'s personal information within OPC\'s data table.',
			'FldForeignTable' => '154',
			'FldType' => 'INT',
			'FldDataType' => 'Numeric',
			'FldDataLength' => '0',
			'FldCharSupport' => ',Numbers,',
			'FldKeyType' => ',Foreign,',
			'FldNullSupport' => '0',
			'FldCompareSame' => '878800'
		]);
		DB::table('SL_Fields')->insert([
			'FldID' => 1482,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '2',
			'FldSpecSource' => '0',
			'FldName' => 'Stars',
			'FldEng' => 'Stars Total',
			'FldDesc' => 'Indicates the total number of stars this volunteer has earned.',
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
			'FldID' => 1483,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '3',
			'FldSpecSource' => '0',
			'FldName' => 'Stars1',
			'FldEng' => 'Stars from Online Research',
			'FldDesc' => 'Indicates the total number of stars this volunteer has earned from completing online research.',
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
			'FldID' => 1484,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '4',
			'FldSpecSource' => '0',
			'FldName' => 'Stars2',
			'FldEng' => 'Stars from Department Calls',
			'FldDesc' => 'Indicates the total number of stars this volunteer has earned from calling and speaking with police departments.',
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
			'FldID' => 1485,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '5',
			'FldSpecSource' => '0',
			'FldName' => 'Stars3',
			'FldEng' => 'Stars from Interal Affairs Calls',
			'FldDesc' => 'Indicates the total number of stars this volunteer has earned from calling and speaking with internal affairs offices.',
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
			'FldID' => 1486,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '6',
			'FldSpecSource' => '0',
			'FldName' => 'Depts',
			'FldEng' => 'Unique Departments',
			'FldDesc' => 'Indicates the number of unique police departments for which this volunteer contributed research.',
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
			'FldID' => 1487,
			'FldDatabase' => '1',
			'FldTable' => '176',
			'FldOrd' => '7',
			'FldSpecSource' => '0',
			'FldName' => 'AvgTimeDept',
			'FldEng' => 'Average Time Researching Department',
			'FldDesc' => 'Indicates average time (in seconds) this volunteer spend editing each department they worked on.',
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
		
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 1,
			'CondValCondID' => '1',
			'CondValValue' => '307'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 2,
			'CondValCondID' => '2',
			'CondValValue' => 'Witness'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 3,
			'CondValCondID' => '2',
			'CondValValue' => 'Victim'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 4,
			'CondValCondID' => '3',
			'CondValValue' => 'F'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 7,
			'CondValCondID' => '6',
			'CondValValue' => 'F'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 12,
			'CondValCondID' => '12',
			'CondValValue' => '349'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 13,
			'CondValCondID' => '12',
			'CondValValue' => '350'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 14,
			'CondValCondID' => '17',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 17,
			'CondValCondID' => '21',
			'CondValValue' => '307'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 18,
			'CondValCondID' => '27',
			'CondValValue' => 'Stops'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 19,
			'CondValCondID' => '29',
			'CondValValue' => 'Searches'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 20,
			'CondValCondID' => '31',
			'CondValValue' => 'Force'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 21,
			'CondValCondID' => '33',
			'CondValValue' => 'Arrests'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 23,
			'CondValCondID' => '39',
			'CondValValue' => '306'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 24,
			'CondValCondID' => '39',
			'CondValValue' => '307'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 25,
			'CondValCondID' => '40',
			'CondValValue' => 'Victim'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 26,
			'CondValCondID' => '41',
			'CondValValue' => 'Witness'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 27,
			'CondValCondID' => '42',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 28,
			'CondValCondID' => '43',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 29,
			'CondValCondID' => '36',
			'CondValValue' => '127'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 30,
			'CondValCondID' => '36',
			'CondValValue' => '128'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 31,
			'CondValCondID' => '36',
			'CondValValue' => '129'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 32,
			'CondValCondID' => '36',
			'CondValValue' => '132'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 33,
			'CondValCondID' => '13',
			'CondValValue' => '127'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 34,
			'CondValCondID' => '14',
			'CondValValue' => '128'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 35,
			'CondValCondID' => '15',
			'CondValValue' => '129'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 36,
			'CondValCondID' => '16',
			'CondValValue' => '132'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 39,
			'CondValCondID' => '48',
			'CondValValue' => 'Upset'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 40,
			'CondValCondID' => '50',
			'CondValValue' => 'Victim'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 41,
			'CondValCondID' => '51',
			'CondValValue' => 'Victim'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 44,
			'CondValCondID' => '52',
			'CondValValue' => 'N'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 45,
			'CondValCondID' => '53',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 46,
			'CondValCondID' => '54',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 47,
			'CondValCondID' => '54',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 48,
			'CondValCondID' => '55',
			'CondValValue' => '398'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 49,
			'CondValCondID' => '55',
			'CondValValue' => '399'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 50,
			'CondValCondID' => '56',
			'CondValValue' => '304'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 51,
			'CondValCondID' => '58',
			'CondValValue' => '304'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 52,
			'CondValCondID' => '59',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 53,
			'CondValCondID' => '62',
			'CondValValue' => '1'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 56,
			'CondValCondID' => '65',
			'CondValValue' => 'Subject Officer'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 65,
			'CondValCondID' => '68',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 66,
			'CondValCondID' => '68',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 69,
			'CondValCondID' => '7',
			'CondValValue' => 'Gold'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 79,
			'CondValCondID' => '44',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 84,
			'CondValCondID' => '78',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 85,
			'CondValCondID' => '64',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 86,
			'CondValCondID' => '64',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 88,
			'CondValCondID' => '79',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 92,
			'CondValCondID' => '10',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 97,
			'CondValCondID' => '75',
			'CondValValue' => 'N'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 98,
			'CondValCondID' => '75',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 101,
			'CondValCondID' => '76',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 103,
			'CondValCondID' => '69',
			'CondValValue' => '77'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 104,
			'CondValCondID' => '69',
			'CondValValue' => '-10'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 105,
			'CondValCondID' => '70',
			'CondValValue' => '77'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 106,
			'CondValCondID' => '70',
			'CondValValue' => '10'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 107,
			'CondValCondID' => '77',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 108,
			'CondValCondID' => '83',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 109,
			'CondValCondID' => '84',
			'CondValValue' => '351'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 110,
			'CondValCondID' => '85',
			'CondValValue' => '404'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 111,
			'CondValCondID' => '86',
			'CondValValue' => '77'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 112,
			'CondValCondID' => '86',
			'CondValValue' => '85'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 113,
			'CondValCondID' => '87',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 114,
			'CondValCondID' => '87',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 115,
			'CondValCondID' => '89',
			'CondValValue' => '140'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 116,
			'CondValCondID' => '89',
			'CondValValue' => '190'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 117,
			'CondValCondID' => '90',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 118,
			'CondValCondID' => '90',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 121,
			'CondValCondID' => '80',
			'CondValValue' => '79'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 122,
			'CondValCondID' => '80',
			'CondValValue' => '10'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 123,
			'CondValCondID' => '91',
			'CondValValue' => 'Y'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 124,
			'CondValCondID' => '91',
			'CondValValue' => 'N'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 125,
			'CondValCondID' => '91',
			'CondValValue' => '?'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 126,
			'CondValCondID' => '92',
			'CondValValue' => 'Passenger'
		]);
		DB::table('SL_ConditionsVals')->insert([
			'CondValID' => 127,
			'CondValCondID' => '96',
			'CondValValue' => 'Y'
		]);
	
	DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 1,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '16'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 2,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '17'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 3,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '22'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 4,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '23'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 5,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '26'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 6,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '27'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 7,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '41'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 8,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '44'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 9,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '50'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 10,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '51'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 11,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '52'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 12,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '53'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 13,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '56'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 14,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '70'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 15,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '75'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 16,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '76'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 17,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '447'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 18,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '92'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 19,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '93'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 20,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '94'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 21,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '95'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 22,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '102'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 23,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '115'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 24,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '117'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 25,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '132'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 26,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '133'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 27,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '134'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 28,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '135'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 29,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '165'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 30,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '176'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 31,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '190'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 32,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '191'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 33,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '192'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 34,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '194'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 35,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '279'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 36,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '280'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 37,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '282'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 39,
			'CondNodeCondID' => '2',
			'CondNodeNodeID' => '47'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 40,
			'CondNodeCondID' => '2',
			'CondNodeNodeID' => '48'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 43,
			'CondNodeCondID' => '4',
			'CondNodeNodeID' => '148'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 44,
			'CondNodeCondID' => '4',
			'CondNodeNodeID' => '485'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 45,
			'CondNodeCondID' => '5',
			'CondNodeNodeID' => '162'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 47,
			'CondNodeCondID' => '7',
			'CondNodeNodeID' => '196'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 48,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '206'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 49,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '211'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 50,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '214'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 51,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '219'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 52,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '224'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 53,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '229'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 54,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '236'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 55,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '241'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 56,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '244'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 57,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '249'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 58,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '253'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 59,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '257'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 60,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '262'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 61,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '263'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 62,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '428'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 63,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '487'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 64,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '495'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 65,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '498'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 66,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '499'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 67,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '593'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 68,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '207'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 69,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '215'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 70,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '220'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 71,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '225'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 72,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '230'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 73,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '233'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 74,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '237'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 75,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '245'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 76,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '250'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 77,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '254'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 78,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '258'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 79,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '271'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 80,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '272'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 81,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '273'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 82,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '429'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 83,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '594'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 84,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '297'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 85,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '392'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 88,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '490',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 89,
			'CondNodeCondID' => '12',
			'CondNodeNodeID' => '301'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 90,
			'CondNodeCondID' => '13',
			'CondNodeNodeID' => '344'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 91,
			'CondNodeCondID' => '14',
			'CondNodeNodeID' => '345'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 92,
			'CondNodeCondID' => '15',
			'CondNodeNodeID' => '346'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 93,
			'CondNodeCondID' => '16',
			'CondNodeNodeID' => '347'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 94,
			'CondNodeCondID' => '17',
			'CondNodeNodeID' => '351'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 95,
			'CondNodeCondID' => '18',
			'CondNodeNodeID' => '357'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 96,
			'CondNodeCondID' => '18',
			'CondNodeNodeID' => '519'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 97,
			'CondNodeCondID' => '19',
			'CondNodeNodeID' => '357'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 99,
			'CondNodeCondID' => '19',
			'CondNodeNodeID' => '519'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 100,
			'CondNodeCondID' => '19',
			'CondNodeNodeID' => '517'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 101,
			'CondNodeCondID' => '19',
			'CondNodeNodeID' => '518'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 106,
			'CondNodeCondID' => '21',
			'CondNodeNodeID' => '445'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 108,
			'CondNodeCondID' => '23',
			'CondNodeNodeID' => '473'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 109,
			'CondNodeCondID' => '24',
			'CondNodeNodeID' => '476'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 110,
			'CondNodeCondID' => '25',
			'CondNodeNodeID' => '479'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 111,
			'CondNodeCondID' => '26',
			'CondNodeNodeID' => '260'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 113,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '487'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 114,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '486'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 115,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '488'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 116,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '489'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 117,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '490'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 118,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '491'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 119,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '492'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 120,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '493'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 121,
			'CondNodeCondID' => '28',
			'CondNodeNodeID' => '486'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 122,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '495'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 123,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '494'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 124,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '500'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 125,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '501'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 126,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '502'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 127,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '503'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 128,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '504'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 129,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '505'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 130,
			'CondNodeCondID' => '29',
			'CondNodeNodeID' => '506'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 131,
			'CondNodeCondID' => '30',
			'CondNodeNodeID' => '494'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 132,
			'CondNodeCondID' => '31',
			'CondNodeNodeID' => '498'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 133,
			'CondNodeCondID' => '31',
			'CondNodeNodeID' => '496'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 134,
			'CondNodeCondID' => '31',
			'CondNodeNodeID' => '507'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 135,
			'CondNodeCondID' => '31',
			'CondNodeNodeID' => '508'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 136,
			'CondNodeCondID' => '31',
			'CondNodeNodeID' => '509'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 138,
			'CondNodeCondID' => '31',
			'CondNodeNodeID' => '511'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 139,
			'CondNodeCondID' => '32',
			'CondNodeNodeID' => '496'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 140,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '497'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 141,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '499'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 142,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '512'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 143,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '513'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 144,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '514'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 145,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '515'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 146,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '516'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 147,
			'CondNodeCondID' => '33',
			'CondNodeNodeID' => '599'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 148,
			'CondNodeCondID' => '34',
			'CondNodeNodeID' => '497'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 149,
			'CondNodeCondID' => '35',
			'CondNodeNodeID' => '506'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 151,
			'CondNodeCondID' => '37',
			'CondNodeNodeID' => '516'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 152,
			'CondNodeCondID' => '38',
			'CondNodeNodeID' => '517'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 153,
			'CondNodeCondID' => '39',
			'CondNodeNodeID' => '565'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 154,
			'CondNodeCondID' => '39',
			'CondNodeNodeID' => '566'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 155,
			'CondNodeCondID' => '40',
			'CondNodeLoopID' => '1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 156,
			'CondNodeCondID' => '41',
			'CondNodeLoopID' => '2'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 157,
			'CondNodeCondID' => '42',
			'CondNodeLoopID' => '7'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 158,
			'CondNodeCondID' => '43',
			'CondNodeLoopID' => '15'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 159,
			'CondNodeCondID' => '44',
			'CondNodeNodeID' => '610'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 160,
			'CondNodeCondID' => '44',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 161,
			'CondNodeCondID' => '44',
			'CondNodeNodeID' => '611'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 162,
			'CondNodeCondID' => '27',
			'CondNodeNodeID' => '621'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 167,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '671'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 168,
			'CondNodeCondID' => '46',
			'CondNodeNodeID' => '674'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 169,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '670'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 170,
			'CondNodeCondID' => '47',
			'CondNodeNodeID' => '676'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 171,
			'CondNodeCondID' => '48',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 172,
			'CondNodeCondID' => '48',
			'CondNodeNodeID' => '677'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 174,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '516'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 175,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '514'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 176,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '381'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 178,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '506'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 179,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '503'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 180,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '493'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 181,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '305'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 184,
			'CondNodeCondID' => '51',
			'CondNodeNodeID' => '680'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 186,
			'CondNodeCondID' => '52',
			'CondNodeNodeID' => '682'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 187,
			'CondNodeCondID' => '53',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 188,
			'CondNodeCondID' => '53',
			'CondNodeNodeID' => '683'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 191,
			'CondNodeCondID' => '55',
			'CondNodeNodeID' => '453'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 192,
			'CondNodeCondID' => '56',
			'CondNodeNodeID' => '687'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 193,
			'CondNodeCondID' => '58',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 194,
			'CondNodeCondID' => '58',
			'CondNodeNodeID' => '688'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 196,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '732'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 197,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '733'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 200,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '732'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 201,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '736'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 203,
			'CondNodeCondID' => '47',
			'CondNodeNodeID' => '737'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 204,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '737'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 205,
			'CondNodeCondID' => '47',
			'CondNodeNodeID' => '738'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 207,
			'CondNodeCondID' => '47',
			'CondNodeNodeID' => '739'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 208,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 209,
			'CondNodeCondID' => '47',
			'CondNodeNodeID' => '747'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 210,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '748'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 216,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '781',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 217,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 218,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '782',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 225,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 226,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 227,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '799'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 230,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '185'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 231,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '126'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 232,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '86'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 234,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 235,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '855'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 236,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '856'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 237,
			'CondNodeCondID' => '64',
			'CondNodeNodeID' => '477'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 238,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '731'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 239,
			'CondNodeCondID' => '47',
			'CondNodeNodeID' => '866'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 240,
			'CondNodeCondID' => '55',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 241,
			'CondNodeCondID' => '55',
			'CondNodeNodeID' => '872'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 243,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '881'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 244,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '882'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 245,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '885'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 246,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '886'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 247,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '891'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 248,
			'CondNodeCondID' => '39',
			'CondNodeNodeID' => '894'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 249,
			'CondNodeCondID' => '39',
			'CondNodeNodeID' => '899'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 250,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '913'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 251,
			'CondNodeCondID' => '21',
			'CondNodeNodeID' => '914'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 252,
			'CondNodeCondID' => '5',
			'CondNodeNodeID' => '926'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 253,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '936'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 254,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '937'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 255,
			'CondNodeCondID' => '65',
			'CondNodeNodeID' => '-3',
			'CondNodeLoopID' => '22'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 257,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '947'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 258,
			'CondNodeCondID' => '58',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 259,
			'CondNodeCondID' => '56',
			'CondNodeNodeID' => '965'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 260,
			'CondNodeCondID' => '58',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 261,
			'CondNodeCondID' => '58',
			'CondNodeNodeID' => '966'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 275,
			'CondNodeCondID' => '67',
			'CondNodeNodeID' => '1093'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 276,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 279,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 280,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '1097'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 281,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '1114',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 282,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '-3'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 284,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '1113',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 285,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '1128'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 286,
			'CondNodeCondID' => '62',
			'CondNodeNodeID' => '1124',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 293,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '296'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 294,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '295'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 295,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '294'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 296,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '293'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 297,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '333'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 298,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '353'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 299,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '408'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 300,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '383'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 301,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '389'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 302,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '318'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 303,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '397'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 304,
			'CondNodeCondID' => '1',
			'CondNodeNodeID' => '1094'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 306,
			'CondNodeCondID' => '25',
			'CondNodeNodeID' => '256'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 307,
			'CondNodeCondID' => '94',
			'CondNodeNodeID' => '1162'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 309,
			'CondNodeCondID' => '97',
			'CondNodeNodeID' => '362'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 310,
			'CondNodeCondID' => '98',
			'CondNodeNodeID' => '1091',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 311,
			'CondNodeCondID' => '8',
			'CondNodeNodeID' => '1181'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 312,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '1182'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 313,
			'CondNodeCondID' => '9',
			'CondNodeNodeID' => '1184'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 314,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '736',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 315,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '738',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 316,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '299',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 317,
			'CondNodeCondID' => '10',
			'CondNodeNodeID' => '393',
			'CondNodeLoopID' => '-1'
		]);
		DB::table('SL_ConditionsNodes')->insert([
			'CondNodeID' => 319,
			'CondNodeCondID' => '99',
			'CondNodeNodeID' => '679'
		]);
	
	DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 2,
			'ArticleCondID' => '69',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/police-at-my-door-what-should-i-do/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 3,
			'ArticleCondID' => '70',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/when-can-police-search-your-car/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 6,
			'ArticleCondID' => '68',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/when-can-police-search-your-car/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 7,
			'ArticleCondID' => '68',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/police-at-my-door-what-should-i-do/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 8,
			'ArticleCondID' => '7',
			'ArticleURL' => 'https://openpolice.org/go-gold-make-your-complaint-strong'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 10,
			'ArticleCondID' => '75',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/police-didnt-read-me-my-rights/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 13,
			'ArticleCondID' => '76',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/how-long-can-police-detain-you/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 14,
			'ArticleCondID' => '76',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/when-can-police-ask-for-id/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 15,
			'ArticleCondID' => '69',
			'ArticleURL' => 'https://www.youtube.com/watch?v=z_ckcdtQ95w'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 16,
			'ArticleCondID' => '70',
			'ArticleURL' => 'https://www.youtube.com/watch?v=3kVX6NIPzB0'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 19,
			'ArticleCondID' => '80',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/avoid-getting-traffic-ticket/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 20,
			'ArticleCondID' => '81',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/my-rights-at-checkpoints/#US-border'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 21,
			'ArticleCondID' => '82',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/my-rights-at-checkpoints/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 22,
			'ArticleCondID' => '75',
			'ArticleURL' => 'https://www.youtube.com/watch?v=b2-nKV5odbw'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 23,
			'ArticleCondID' => '83',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/when-can-police-ask-for-id/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 24,
			'ArticleCondID' => '76',
			'ArticleURL' => 'https://www.youtube.com/watch?v=2d7-0TDsxnw'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 25,
			'ArticleCondID' => '76',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/what-is-reasonable-suspicion/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 26,
			'ArticleCondID' => '76',
			'ArticleURL' => 'https://www.youtube.com/watch?v=7bT6VfVGZ2c'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 27,
			'ArticleCondID' => '77',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/probable-cause/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 28,
			'ArticleCondID' => '77',
			'ArticleURL' => 'https://www.youtube.com/watch?v=0JOitPfRlCw'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 29,
			'ArticleCondID' => '77',
			'ArticleURL' => 'https://www.youtube.com/watch?v=3kVX6NIPzB0'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 30,
			'ArticleCondID' => '83',
			'ArticleURL' => 'https://www.youtube.com/watch?v=eV_ANiGk4Sc'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 31,
			'ArticleCondID' => '81',
			'ArticleURL' => 'https://www.youtube.com/watch?v=BBICaJspXw0'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 32,
			'ArticleCondID' => '81',
			'ArticleURL' => 'https://www.youtube.com/watch?v=6_3dDNPwJTU'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 33,
			'ArticleCondID' => '82',
			'ArticleURL' => 'https://www.youtube.com/watch?v=JQk8zsuqsag'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 34,
			'ArticleCondID' => '84',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/what-are-my-rights-with-airport-security/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 35,
			'ArticleCondID' => '84',
			'ArticleURL' => 'https://www.youtube.com/watch?v=BBICaJspXw0'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 36,
			'ArticleCondID' => '86',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/search-and-seizure-in-public-school/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 37,
			'ArticleCondID' => '87',
			'ArticleURL' => 'https://www.flexyourrights.org/7-rules-for-recording-police/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 38,
			'ArticleCondID' => '88',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/what-if-police-say-they-smell-marijuana/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 39,
			'ArticleCondID' => '88',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/marijuana-legalization/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 40,
			'ArticleCondID' => '88',
			'ArticleURL' => 'https://www.youtube.com/watch?v=6EcUmNJ3ppc'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 41,
			'ArticleCondID' => '89',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/what-if-police-say-they-smell-marijuana/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 42,
			'ArticleCondID' => '89',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/marijuana-legalization/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 43,
			'ArticleCondID' => '89',
			'ArticleURL' => 'https://www.youtube.com/watch?v=6EcUmNJ3ppc'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 44,
			'ArticleCondID' => '90',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/when-can-police-use-drug-dogs/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 45,
			'ArticleCondID' => '90',
			'ArticleURL' => 'https://www.youtube.com/watch?v=dxz9MpgcN5c'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 46,
			'ArticleCondID' => '80',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/top-4-traffic-ticket-myths/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 47,
			'ArticleCondID' => '91',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/can-someone-else-consent-to-a-search-of-my-property/'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 48,
			'ArticleCondID' => '91',
			'ArticleURL' => 'https://www.youtube.com/watch?v=HeyIvq7Z9SA'
		]);
		DB::table('SL_ConditionsArticles')->insert([
			'ArticleID' => 49,
			'ArticleCondID' => '92',
			'ArticleURL' => 'https://www.flexyourrights.org/faqs/what-are-the-rights-of-passengers-during-a-traffic-stop/'
		]);
	
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 1,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '55',
			'DataLoopPlural' => 'Victims',
			'DataLoopSingular' => 'Victim',
			'DataLoopTable' => 'Civilians',
			'DataLoopMaxLimit' => '3',
			'DataLoopWarnLimit' => '1',
			'DataLoopMinLimit' => '1'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 2,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '97',
			'DataLoopPlural' => 'Witnesses',
			'DataLoopSingular' => 'Witness',
			'DataLoopTable' => 'Civilians',
			'DataLoopMaxLimit' => '3',
			'DataLoopWarnLimit' => '1'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 3,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '143',
			'DataLoopPlural' => 'Departments',
			'DataLoopSingular' => 'Department',
			'DataLoopTable' => 'LinksComplaintDept',
			'DataLoopMaxLimit' => '3',
			'DataLoopWarnLimit' => '2',
			'DataLoopMinLimit' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 4,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '160',
			'DataLoopPlural' => 'Officers',
			'DataLoopSingular' => 'Officer',
			'DataLoopTable' => 'Officers',
			'DataLoopMaxLimit' => '5',
			'DataLoopWarnLimit' => '2',
			'DataLoopMinLimit' => '1'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 6,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '148',
			'DataLoopPlural' => 'Events',
			'DataLoopSingular' => 'Event',
			'DataLoopTable' => 'EventSequence',
			'DataLoopSortFld' => 'EveOrder',
			'DataLoopDoneFld' => 'EventSequence:EveUserFinished',
			'DataLoopIsStep' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 7,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '458',
			'DataLoopPlural' => 'Complainants',
			'DataLoopSingular' => 'Complainant',
			'DataLoopTable' => 'Civilians',
			'DataLoopMaxLimit' => '1',
			'DataLoopWarnLimit' => '-1',
			'DataLoopMinLimit' => '1',
			'DataLoopIsStep' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 15,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '400',
			'DataLoopPlural' => 'Citations',
			'DataLoopSingular' => 'Citation',
			'DataLoopTable' => 'Stops',
			'DataLoopDoneFld' => 'Stops:StopChargesOther',
			'DataLoopIsStep' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 16,
			'DataLoopTree' => '1',
			'DataLoopRoot' => '414',
			'DataLoopPlural' => 'Medical Care',
			'DataLoopSingular' => 'Medical Care',
			'DataLoopTable' => 'InjuryCare',
			'DataLoopIsStep' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 17,
			'DataLoopTree' => '5',
			'DataLoopRoot' => '892',
			'DataLoopPlural' => 'Complimentors',
			'DataLoopSingular' => 'Complimentor',
			'DataLoopTable' => 'Civilians',
			'DataLoopDoneFld' => 'Civilians:CivOccupation',
			'DataLoopMaxLimit' => '1',
			'DataLoopWarnLimit' => '-1',
			'DataLoopMinLimit' => '1',
			'DataLoopIsStep' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 20,
			'DataLoopTree' => '5',
			'DataLoopRoot' => '917',
			'DataLoopPlural' => 'Departments',
			'DataLoopSingular' => 'Department',
			'DataLoopTable' => 'LinksComplaintDept',
			'DataLoopMaxLimit' => '3',
			'DataLoopWarnLimit' => '2',
			'DataLoopMinLimit' => '1',
			'DataLoopAutoGen' => '0'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 21,
			'DataLoopTree' => '5',
			'DataLoopRoot' => '921',
			'DataLoopPlural' => 'Officers',
			'DataLoopSingular' => 'Officer',
			'DataLoopTable' => 'Officers',
			'DataLoopMaxLimit' => '5',
			'DataLoopWarnLimit' => '2',
			'DataLoopMinLimit' => '1'
		]);
		DB::table('SL_DataLoop')->insert([
			'DataLoopID' => 22,
			'DataLoopTree' => '5',
			'DataLoopRoot' => '945',
			'DataLoopPlural' => 'Excellent Officers',
			'DataLoopSingular' => 'Excellent Officer',
			'DataLoopTable' => 'Officers',
			'DataLoopDoneFld' => 'Officers:OffGaveCompliment',
			'DataLoopMinLimit' => '1',
			'DataLoopIsStep' => '1',
			'DataLoopAutoGen' => '0'
		]);
	
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 4,
			'DataSubTree' => '1',
			'DataSubTbl' => 'EventSequence',
			'DataSubSubTbl' => 'Searches',
			'DataSubSubLnk' => 'SrchEventSequenceID'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 5,
			'DataSubTree' => '1',
			'DataSubTbl' => 'EventSequence',
			'DataSubSubTbl' => 'Force',
			'DataSubSubLnk' => 'ForEventSequenceID'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 6,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Officers',
			'DataSubTblLnk' => 'OffPersonID',
			'DataSubSubTbl' => 'PersonContact',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 7,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Officers',
			'DataSubTblLnk' => 'OffPhysDescID',
			'DataSubSubTbl' => 'PhysicalDesc',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 8,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Civilians',
			'DataSubTblLnk' => 'CivPersonID',
			'DataSubSubTbl' => 'PersonContact',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 9,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Civilians',
			'DataSubTblLnk' => 'CivPhysDescID',
			'DataSubSubTbl' => 'PhysicalDesc',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 10,
			'DataSubTree' => '1',
			'DataSubTbl' => 'EventSequence',
			'DataSubSubTbl' => 'Stops',
			'DataSubSubLnk' => 'StopEventSequenceID'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 14,
			'DataSubTree' => '1',
			'DataSubTbl' => 'EventSequence',
			'DataSubSubTbl' => 'Arrests',
			'DataSubSubLnk' => 'ArstEventSequenceID'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 25,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Complaints',
			'DataSubTblLnk' => 'ComIncidentID',
			'DataSubSubTbl' => 'Incidents',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 27,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Complaints',
			'DataSubTblLnk' => 'ComSceneID',
			'DataSubSubTbl' => 'Scenes',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 29,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Complaints',
			'DataSubSubTbl' => 'AllegSilver',
			'DataSubSubLnk' => 'AlleSilComplaintID',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 30,
			'DataSubTree' => '1',
			'DataSubTbl' => 'LinksComplaintDept',
			'DataSubTblLnk' => 'LnkComDeptDeptID',
			'DataSubSubTbl' => 'Departments'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 32,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Officers',
			'DataSubSubTbl' => 'OffCompliments',
			'DataSubSubLnk' => 'OffCompOffID',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 33,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Civilians',
			'DataSubTblLnk' => 'CivPersonID',
			'DataSubSubTbl' => 'PersonContact',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 34,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Civilians',
			'DataSubTblLnk' => 'CivPhysDescID',
			'DataSubSubTbl' => 'PhysicalDesc',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 35,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Officers',
			'DataSubTblLnk' => 'OffPersonID',
			'DataSubSubTbl' => 'PersonContact',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 36,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Officers',
			'DataSubTblLnk' => 'OffPhysDescID',
			'DataSubSubTbl' => 'PhysicalDesc',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 37,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Compliments',
			'DataSubTblLnk' => 'CompliIncidentID',
			'DataSubSubTbl' => 'Incidents',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 38,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Compliments',
			'DataSubTblLnk' => 'CompliSceneID',
			'DataSubSubTbl' => 'Scenes',
			'DataSubAutoGen' => '1'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 40,
			'DataSubTree' => '5',
			'DataSubTbl' => 'LinksComplimentDept',
			'DataSubTblLnk' => 'LnkCompliDeptDeptID',
			'DataSubSubTbl' => 'Departments'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 41,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Departments',
			'DataSubSubTbl' => 'Oversight',
			'DataSubSubLnk' => 'OverDeptID'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 42,
			'DataSubTree' => '1',
			'DataSubTbl' => 'LinksComplaintOversight',
			'DataSubTblLnk' => 'LnkComOverOverID',
			'DataSubSubTbl' => 'Oversight'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 43,
			'DataSubTree' => '5',
			'DataSubTbl' => 'Departments',
			'DataSubSubTbl' => 'Oversight',
			'DataSubSubLnk' => 'OverDeptID'
		]);
		DB::table('SL_DataSubsets')->insert([
			'DataSubID' => 44,
			'DataSubTree' => '1',
			'DataSubTbl' => 'Civilians',
			'DataSubSubTbl' => 'InjuryCare',
			'DataSubSubLnk' => 'InjCareSubjectID'
		]);
	
	DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 1,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Stops',
			'DataHelpTable' => 'StopReasons',
			'DataHelpKeyField' => 'StopReasStopID',
			'DataHelpValueField' => 'StopReasReason'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 2,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Searches',
			'DataHelpTable' => 'SearchContra',
			'DataHelpKeyField' => 'SrchConSearchID',
			'DataHelpValueField' => 'SrchConType'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 3,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Force',
			'DataHelpTable' => 'ForceSubType',
			'DataHelpKeyField' => 'ForceSubForceID',
			'DataHelpValueField' => 'ForceSubType'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 4,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Force',
			'DataHelpTable' => 'BodyParts',
			'DataHelpKeyField' => 'BodyForceID',
			'DataHelpValueField' => 'BodyPart'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 5,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Injuries',
			'DataHelpTable' => 'BodyParts',
			'DataHelpKeyField' => 'BodyInjuryID',
			'DataHelpValueField' => 'BodyPart'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 8,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Searches',
			'DataHelpTable' => 'SearchSeize',
			'DataHelpKeyField' => 'SrchSeizSearchID',
			'DataHelpValueField' => 'SrchSeizType'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 20,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'EventSequence',
			'DataHelpTable' => 'Allegations',
			'DataHelpKeyField' => 'AlleEventSequenceID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 21,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Complaints',
			'DataHelpTable' => 'Allegations',
			'DataHelpKeyField' => 'AlleComplaintID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 22,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Allegations',
			'DataHelpTable' => 'LinksCivilianAllegations',
			'DataHelpKeyField' => 'LnkCivAlleAlleID',
			'DataHelpValueField' => 'LnkCivAlleCivID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 23,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Allegations',
			'DataHelpTable' => 'LinksOfficerAllegations',
			'DataHelpKeyField' => 'LnkOffAlleAlleID',
			'DataHelpValueField' => 'LnkOffAlleOffID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 24,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'EventSequence',
			'DataHelpTable' => 'LinksCivilianEvents',
			'DataHelpKeyField' => 'LnkCivEveEveID',
			'DataHelpValueField' => 'LnkCivEveCivID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 25,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'EventSequence',
			'DataHelpTable' => 'LinksOfficerEvents',
			'DataHelpKeyField' => 'LnkOffEveEveID',
			'DataHelpValueField' => 'LnkOffEveOffID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 26,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Orders',
			'DataHelpTable' => 'LinksCivilianOrders',
			'DataHelpKeyField' => 'LnkCivOrdOrdID',
			'DataHelpValueField' => 'LnkCivOrdCivID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 27,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Orders',
			'DataHelpTable' => 'LinksOfficerOrders',
			'DataHelpKeyField' => 'LnkOffOrdOrdID',
			'DataHelpValueField' => 'LnkOffOrdOffID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 28,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'EventSequence',
			'DataHelpTable' => 'Orders',
			'DataHelpKeyField' => 'OrdEventSequenceID'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 29,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'PhysicalDesc',
			'DataHelpTable' => 'PhysicalDescRace',
			'DataHelpKeyField' => 'PhysRacePhysDescID',
			'DataHelpValueField' => 'PhysRaceRace'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 30,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Complaints',
			'DataHelpTable' => 'CivWeapons',
			'DataHelpKeyField' => 'CivWeapID',
			'DataHelpValueField' => 'CivWeapBodyWeapon'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 31,
			'DataHelpTree' => '5',
			'DataHelpParentTable' => 'PhysicalDesc',
			'DataHelpTable' => 'PhysicalDescRace',
			'DataHelpKeyField' => 'PhysRacePhysDescID',
			'DataHelpValueField' => 'PhysRaceRace'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 32,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'EventSequence',
			'DataHelpTable' => 'Charges',
			'DataHelpKeyField' => 'ChrgEventID',
			'DataHelpValueField' => 'ChrgCharges'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 33,
			'DataHelpTree' => '1',
			'DataHelpParentTable' => 'Civilians',
			'DataHelpTable' => 'Injuries',
			'DataHelpKeyField' => 'InjSubjectID',
			'DataHelpValueField' => 'InjType'
		]);
		DB::table('SL_DataHelpers')->insert([
			'DataHelpID' => 34,
			'DataHelpTree' => '36',
			'DataHelpParentTable' => 'Departments',
			'DataHelpTable' => 'Oversight',
			'DataHelpKeyField' => 'OverDeptID'
		]);
	
		DB::table('SL_DataLinks')->insert([
			'DataLinkID' => 1,
			'DataLinkTree' => '1',
			'DataLinkTable' => '163'
		]);
		DB::table('SL_DataLinks')->insert([
			'DataLinkID' => 4,
			'DataLinkTree' => '1',
			'DataLinkTable' => '155'
		]);
	
 } } 