<?php 
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-gen-migration.blade.php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OPCreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
    	Schema::create('OP_Complaints', function(Blueprint $table)
		{
			$table->increments('ComID');
			$table->integer('ComUserID')->unsigned()->nullable();
			$table->foreign('ComUserID')->references('id')->on('users');
			$table->integer('ComStatus')->unsigned()->nullable();
		$table->index('ComStatus');
			$table->integer('ComType')->unsigned()->nullable();
		$table->index('ComType');
			$table->integer('ComIncidentID')->unsigned()->nullable();
			$table->foreign('ComIncidentID')->references('IncID')->on('OP_Incidents');
			$table->integer('ComSceneID')->unsigned()->nullable();
			$table->foreign('ComSceneID')->references('ScnID')->on('OP_Scenes');
			$table->integer('ComPrivacy')->unsigned()->nullable();
			$table->string('ComAwardMedallion', 10)->nullable();
			$table->longText('ComSummary')->nullable();
			$table->char('ComTriedOtherWays', 1)->nullable();
			$table->string('ComTriedOtherWaysDesc')->nullable();
			$table->char('ComOfficerInjured', 1)->nullable();
			$table->string('ComOfficerInjuredDesc')->nullable();
			$table->char('ComAttorneyHas', 1)->nullable();
			$table->char('ComAttorneyOKedOPC', 1)->nullable();
			$table->char('ComAttorneyWant', 1)->nullable();
			$table->char('ComAnyoneCharged', 1)->nullable();
			$table->char('ComAllChargesResolved', 1)->nullable();
			$table->integer('ComUnresolvedChargesActions')->unsigned()->default('0')->nullable();
			$table->char('ComLegalAidHelp', 1)->nullable();
			$table->char('ComGovtInvestigation', 1)->nullable();
			$table->string('ComGovtInvestigationWhyNot')->nullable();
			$table->string('ComHowHear')->nullable();
			$table->longText('ComFeedback')->nullable();
			$table->char('ComOfficerDisciplined', 1)->nullable();
			$table->integer('ComOfficerDisciplineType')->unsigned()->nullable();
			$table->longText('ComMediaLinks')->nullable();
			$table->integer('ComAdminID')->unsigned()->nullable();
		$table->index('ComAdminID');
			$table->foreign('ComAdminID')->references('AdmID')->on('OP_Administrators');
			$table->integer('ComAttID')->unsigned()->nullable();
		$table->index('ComAttID');
			$table->foreign('ComAttID')->references('PartID')->on('OP_Partners');
			$table->longText('ComNotes')->nullable();
			$table->string('ComSlug', 255)->nullable();
			$table->dateTime('ComRecordSubmitted')->nullable();
		$table->index('ComRecordSubmitted');
			$table->string('ComSubmissionProgress')->nullable();
			$table->string('ComVersionAB')->nullable();
			$table->string('ComTreeVersion', 50)->nullable();
			$table->string('ComHoneyPot')->nullable();
			$table->boolean('ComIsMobile')->nullable();
			$table->string('ComUniqueStr', 20)->nullable();
			$table->string('ComIPaddy')->nullable();
			$table->integer('ComPublicID')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Compliments', function(Blueprint $table)
		{
			$table->increments('CompliID');
			$table->integer('CompliUserID')->unsigned()->nullable();
			$table->foreign('CompliUserID')->references('id')->on('users');
			$table->integer('CompliStatus')->unsigned()->nullable();
			$table->integer('CompliType')->nullable();
			$table->integer('CompliIncidentID')->unsigned()->nullable();
			$table->foreign('CompliIncidentID')->references('IncID')->on('OP_Incidents');
			$table->integer('CompliSceneID')->unsigned()->nullable();
			$table->foreign('CompliSceneID')->references('ScnID')->on('OP_Scenes');
			$table->integer('CompliPrivacy')->unsigned()->nullable();
			$table->longText('CompliSummary')->nullable();
			$table->string('CompliHowHear')->nullable();
			$table->longText('CompliFeedback')->nullable();
			$table->string('CompliSlug')->nullable();
			$table->longText('CompliNotes')->nullable();
			$table->dateTime('CompliRecordSubmitted')->nullable();
			$table->string('CompliSubmissionProgress')->nullable();
			$table->string('CompliVersionAB')->nullable();
			$table->string('CompliTreeVersion', 50)->nullable();
			$table->string('CompliHoneyPot')->nullable();
			$table->boolean('CompliIsMobile')->nullable();
			$table->string('CompliUniqueStr', 20)->nullable();
			$table->string('CompliIPaddy')->nullable();
			$table->integer('CompliPublicID')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Incidents', function(Blueprint $table)
		{
			$table->increments('IncID');
			$table->integer('IncComplaintID')->unsigned()->nullable();
		$table->index('IncComplaintID');
			$table->foreign('IncComplaintID')->references('ComID')->on('OP_Complaints');
			$table->string('IncAddress')->nullable();
			$table->string('IncAddress2')->nullable();
			$table->string('IncAddressCity')->nullable();
			$table->string('IncAddressState', 2)->nullable();
			$table->string('IncAddressZip', 10)->nullable();
			$table->double('IncAddressLat')->nullable();
			$table->double('IncAddressLng')->nullable();
			$table->string('IncLandmarks')->nullable();
			$table->dateTime('IncTimeStart')->nullable();
			$table->dateTime('IncTimeEnd')->nullable();
			$table->integer('IncDuration')->nullable();
			$table->boolean('IncPublic')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Scenes', function(Blueprint $table)
		{
			$table->increments('ScnID');
			$table->char('ScnIsVehicle', 1)->nullable();
			$table->integer('ScnType')->unsigned()->nullable();
			$table->longText('ScnDescription')->nullable();
			$table->char('ScnForcibleEntry', 1)->nullable();
			$table->char('ScnCCTV', 1)->nullable();
			$table->longText('ScnCCTVDesc')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_AllegSilver', function(Blueprint $table)
		{
			$table->increments('AlleSilID');
			$table->integer('AlleSilComplaintID')->unsigned()->nullable();
			$table->foreign('AlleSilComplaintID')->references('ComID')->on('OP_Complaints');
			$table->char('AlleSilStopYN', 1)->nullable();
			$table->char('AlleSilStopWrongful', 1)->nullable();
			$table->char('AlleSilOfficerID', 1)->nullable();
			$table->char('AlleSilOfficerRefuseID', 1)->nullable();
			$table->char('AlleSilSearchYN', 1)->nullable();
			$table->char('AlleSilSearchWrongful', 1)->nullable();
			$table->char('AlleSilForceYN', 1)->nullable();
			$table->char('AlleSilForceUnreason', 1)->nullable();
			$table->char('AlleSilPropertyYN', 1)->nullable();
			$table->char('AlleSilPropertyWrongful', 1)->nullable();
			$table->char('AlleSilArrestYN', 1)->nullable();
			$table->char('AlleSilArrestWrongful', 1)->nullable();
			$table->char('AlleSilArrestRetaliatory', 1)->nullable();
			$table->char('AlleSilArrestMiranda', 1)->nullable();
			$table->char('AlleSilCitationYN', 1)->nullable();
			$table->char('AlleSilCitationExcessive', 1)->nullable();
			$table->char('AlleSilProcedure', 1)->nullable();
			$table->char('AlleSilNeglectDuty', 1)->nullable();
			$table->char('AlleSilBias', 1)->nullable();
			$table->char('AlleSilSexualHarass', 1)->nullable();
			$table->char('AlleSilSexualAssault', 1)->nullable();
			$table->integer('AlleSilIntimidatingWeapon')->unsigned()->nullable();
			$table->integer('AlleSilIntimidatingWeaponType')->unsigned()->nullable();
			$table->char('AlleSilRetaliation', 1)->nullable();
			$table->char('AlleSilUnbecoming', 1)->nullable();
			$table->char('AlleSilDiscourteous', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Allegations', function(Blueprint $table)
		{
			$table->increments('AlleID');
			$table->integer('AlleComplaintID')->unsigned()->nullable();
		$table->index('AlleComplaintID');
			$table->foreign('AlleComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('AlleType')->unsigned()->nullable();
			$table->integer('AlleEventSequenceID')->unsigned()->nullable();
			$table->foreign('AlleEventSequenceID')->references('EveID')->on('OP_EventSequence');
			$table->longText('AlleDescription')->nullable();
			$table->integer('AlleFindings')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_OffCompliments', function(Blueprint $table)
		{
			$table->increments('OffCompID');
			$table->integer('OffCompOffID')->unsigned()->nullable();
			$table->foreign('OffCompOffID')->references('OffID')->on('OP_Officers');
			$table->char('OffCompValor', 1)->nullable();
			$table->char('OffCompLifesaving', 1)->nullable();
			$table->char('OffCompDeescalation', 1)->nullable();
			$table->char('OffCompProfessionalism', 1)->nullable();
			$table->char('OffCompFairness', 1)->nullable();
			$table->char('OffCompConstitutional', 1)->nullable();
			$table->char('OffCompCompassion', 1)->nullable();
			$table->char('OffCompCommunity', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_EventSequence', function(Blueprint $table)
		{
			$table->increments('EveID');
			$table->integer('EveComplaintID')->unsigned()->nullable();
		$table->index('EveComplaintID');
			$table->foreign('EveComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('EveOrder')->nullable();
			$table->string('EveType')->nullable();
			$table->char('EveUserFinished', 1)->default('N')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Stops', function(Blueprint $table)
		{
			$table->increments('StopID');
			$table->integer('StopEventSequenceID')->unsigned()->nullable();
			$table->foreign('StopEventSequenceID')->references('EveID')->on('OP_EventSequence');
			$table->longText('StopStatedReasonDesc')->nullable();
			$table->char('StopSubjectAskedToLeave', 1)->nullable();
			$table->longText('StopSubjectStatementsDesc')->nullable();
			$table->char('StopEnterPrivateProperty', 1)->nullable();
			$table->string('StopEnterPrivatePropertyDesc')->nullable();
			$table->char('StopPermissionEnter', 1)->nullable();
			$table->char('StopPermissionEnterGranted', 1)->nullable();
			$table->char('StopRequestID', 1)->nullable();
			$table->char('StopRefuseID', 1)->nullable();
			$table->char('StopRequestOfficerID', 1)->nullable();
			$table->string('StopCitationNumber', 25)->nullable();
			$table->char('StopOfficerRefuseID', 1)->nullable();
			$table->char('StopSubjectFrisk', 1)->nullable();
			$table->char('StopSubjectHandcuffed', 1)->nullable();
			$table->char('StopStopSubjectHandcuffInjYN', 1)->nullable();
			$table->integer('StopSubjectHandcuffInjury')->unsigned()->nullable();
			$table->foreign('StopSubjectHandcuffInjury')->references('InjID')->on('OP_Injuries');
			$table->string('StopChargesOther')->nullable();
			$table->dateTime('StopTimeStart')->nullable();
			$table->dateTime('StopTimeEnd')->nullable();
			$table->integer('StopDuration')->nullable();
			$table->char('StopAllegWrongfulStop', 1)->nullable();
			$table->char('StopAllegWrongfulEntry', 1)->nullable();
			$table->char('StopAllegRetaliatoryCharges', 1)->nullable();
			$table->char('StopBreathAlcohol', 1)->nullable();
			$table->char('StopBreathAlcoholFailed', 1)->nullable();
			$table->char('StopBreathCannabis', 1)->nullable();
			$table->char('StopBreathCannabisFailed', 1)->nullable();
			$table->char('StopSalivaTest', 1)->nullable();
			$table->char('StopSobrietyOther', 1)->nullable();
			$table->string('StopSobrietyOtherDescribe')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_StopReasons', function(Blueprint $table)
		{
			$table->increments('StopReasID');
			$table->integer('StopReasStopID')->unsigned()->nullable();
			$table->foreign('StopReasStopID')->references('StopID')->on('OP_Stops');
			$table->integer('StopReasReason')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Searches', function(Blueprint $table)
		{
			$table->increments('SrchID');
			$table->integer('SrchEventSequenceID')->unsigned()->nullable();
			$table->foreign('SrchEventSequenceID')->references('EveID')->on('OP_EventSequence');
			$table->char('SrchStatedReason', 1)->nullable();
			$table->longText('SrchStatedReasonDesc')->nullable();
			$table->char('SrchOfficerRequest', 1)->nullable();
			$table->longText('SrchOfficerRequestDesc')->nullable();
			$table->char('SrchSubjectConsent', 1)->nullable();
			$table->longText('SrchSubjectSay')->nullable();
			$table->char('SrchOfficerThreats', 1)->nullable();
			$table->longText('SrchOfficerThreatsDesc')->nullable();
			$table->char('SrchStrip', 1)->nullable();
			$table->string('SrchStripSearchDesc')->nullable();
			$table->char('SrchK9sniff', 1)->nullable();
			$table->char('SrchContrabandDiscovered', 1)->nullable();
			$table->char('SrchOfficerWarrant', 1)->nullable();
			$table->longText('SrchOfficerWarrantSay')->nullable();
			$table->char('SrchSeized', 1)->nullable();
			$table->longText('SrchSeizedDesc')->nullable();
			$table->char('SrchDamage', 1)->nullable();
			$table->longText('SrchDamageDesc')->nullable();
			$table->char('SrchAllegWrongfulSearch', 1)->nullable();
			$table->char('SrchAllegWrongfulProperty', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_SearchContra', function(Blueprint $table)
		{
			$table->increments('SrchConID');
			$table->integer('SrchConSearchID')->unsigned()->nullable();
			$table->foreign('SrchConSearchID')->references('SrchID')->on('OP_Searches');
			$table->integer('SrchConType')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_SearchSeize', function(Blueprint $table)
		{
			$table->increments('SrchSeizID');
			$table->integer('SrchSeizSearchID')->unsigned()->nullable();
			$table->foreign('SrchSeizSearchID')->references('SrchID')->on('OP_Searches');
			$table->integer('SrchSeizType')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Arrests', function(Blueprint $table)
		{
			$table->increments('ArstID');
			$table->integer('ArstEventSequenceID')->unsigned()->nullable();
			$table->foreign('ArstEventSequenceID')->references('EveID')->on('OP_EventSequence');
			$table->char('ArstChargesFiled', 1)->nullable();
			$table->char('ArstStatedReason', 1)->nullable();
			$table->longText('ArstStatedReasonDesc')->nullable();
			$table->char('ArstMiranda', 1)->nullable();
			$table->char('ArstSITA', 1)->nullable();
			$table->string('ArstChargesOther')->nullable();
			$table->char('ArstNoChargesFiled', 1)->nullable();
			$table->char('ArstStrip', 1)->nullable();
			$table->string('ArstStripSearchDesc')->nullable();
			$table->char('ArstAllegWrongfulArrest', 1)->nullable();
			$table->char('ArstAllegRetaliatoryCharges', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Force', function(Blueprint $table)
		{
			$table->increments('ForID');
			$table->integer('ForEventSequenceID')->unsigned()->nullable();
			$table->foreign('ForEventSequenceID')->references('EveID')->on('OP_EventSequence');
			$table->char('ForAgainstAnimal', 1)->nullable();
			$table->string('ForAnimalDesc')->nullable();
			$table->integer('ForType')->unsigned()->nullable();
			$table->string('ForTypeOther')->nullable();
			$table->integer('ForGunAmmoType')->unsigned()->nullable();
			$table->string('ForGunDesc')->nullable();
			$table->integer('ForHowManyTimes')->nullable();
			$table->char('ForOrdersBeforeForce', 1)->nullable();
			$table->longText('ForOrdersSubjectResponse')->nullable();
			$table->char('ForWhileHandcuffed', 1)->nullable();
			$table->char('ForWhileHeldDown', 1)->nullable();
			$table->char('ForAllegUnreasonable', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_ForceSubType', function(Blueprint $table)
		{
			$table->increments('ForceSubID');
			$table->integer('ForceSubForceID')->unsigned()->nullable();
			$table->foreign('ForceSubForceID')->references('ForID')->on('OP_Force');
			$table->integer('ForceSubType')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_CivWeapons', function(Blueprint $table)
		{
			$table->increments('CivWeapID');
			$table->integer('CivWeapComID')->unsigned()->nullable();
			$table->foreign('CivWeapComID')->references('ForID')->on('OP_Force');
			$table->integer('CivWeapBodyWeapon')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_BodyParts', function(Blueprint $table)
		{
			$table->increments('BodyID');
			$table->integer('BodyForceID')->unsigned()->nullable();
			$table->foreign('BodyForceID')->references('ForID')->on('OP_Force');
			$table->integer('BodyInjuryID')->unsigned()->nullable();
			$table->foreign('BodyInjuryID')->references('InjID')->on('OP_Injuries');
			$table->integer('BodyPart')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Orders', function(Blueprint $table)
		{
			$table->increments('OrdID');
			$table->integer('OrdEventSequenceID')->unsigned()->nullable();
			$table->foreign('OrdEventSequenceID')->references('EveID')->on('OP_EventSequence');
			$table->longText('OrdDescription')->nullable();
			$table->char('OrdTroubleUnderYN', 1)->nullable();
			$table->string('OrdTroubleUnderstading')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Charges', function(Blueprint $table)
		{
			$table->increments('ChrgID');
			$table->integer('ChrgEventID')->unsigned()->nullable();
			$table->foreign('ChrgEventID')->references('EveID')->on('OP_EventSequence');
			$table->integer('ChrgCharges')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Injuries', function(Blueprint $table)
		{
			$table->increments('InjID');
			$table->integer('InjSubjectID')->unsigned()->nullable();
			$table->foreign('InjSubjectID')->references('CivID')->on('OP_Civilians');
			$table->integer('InjType')->unsigned()->nullable();
			$table->integer('InjHowManyTimes')->nullable();
			$table->longText('InjDescription')->nullable();
			$table->boolean('InjDone')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_InjuryCare', function(Blueprint $table)
		{
			$table->increments('InjCareID');
			$table->integer('InjCareSubjectID')->unsigned()->nullable();
			$table->foreign('InjCareSubjectID')->references('CivID')->on('OP_Civilians');
			$table->char('InjCareResultInDeath', 1)->nullable();
			$table->dateTime('InjCareTimeOfDeath')->nullable();
			$table->char('InjCareGotMedical', 1)->nullable();
			$table->string('InjCareHospitalTreated')->nullable();
			$table->string('InjCareDoctorNameFirst')->nullable();
			$table->string('InjCareDoctorNameLast')->nullable();
			$table->string('InjCareDoctorEmail')->nullable();
			$table->string('InjCareDoctorPhone')->nullable();
			$table->char('InjCareEmergencyOnScene', 1)->nullable();
			$table->string('InjCareEmergencyNameFirst')->nullable();
			$table->string('InjCareEmergencyNameLast')->nullable();
			$table->string('InjCareEmergencyIDnumber')->nullable();
			$table->string('InjCareEmergencyVehicleNumber')->nullable();
			$table->string('InjCareEmergencyLicenceNumber')->nullable();
			$table->string('InjCareEmergencyDeptName')->nullable();
			$table->boolean('InjCareDone')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Surveys', function(Blueprint $table)
		{
			$table->increments('SurvID');
			$table->integer('SurvComplaintID')->unsigned()->nullable();
		$table->index('SurvComplaintID');
			$table->foreign('SurvComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('SurvAuthUserID')->unsigned()->nullable();
			$table->foreign('SurvAuthUserID')->references('id')->on('users');
			$table->timestamps();
		});
		Schema::create('OP_ComplaintNotes', function(Blueprint $table)
		{
			$table->increments('NoteID');
			$table->integer('NoteComplaintID')->unsigned()->nullable();
		$table->index('NoteComplaintID');
			$table->foreign('NoteComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('NoteUserID')->unsigned()->nullable();
			$table->foreign('NoteUserID')->references('id')->on('users');
			$table->dateTime('NoteTimestamp')->default('NOW()')->nullable();
			$table->longText('NoteContent')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Civilians', function(Blueprint $table)
		{
			$table->increments('CivID');
			$table->integer('CivComplaintID')->unsigned()->nullable();
		$table->index('CivComplaintID');
			$table->foreign('CivComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('CivVictimUseWeapon')->unsigned()->nullable();
			$table->char('CivVictimHadWeapon', 1)->nullable();
			$table->integer('CivVictimWhatWeapon')->unsigned()->nullable();
			$table->integer('CivUserID')->unsigned()->nullable();
			$table->foreign('CivUserID')->references('id')->on('users');
			$table->char('CivIsCreator', 1)->default('N')->nullable();
			$table->string('CivRole', 10)->nullable();
			$table->integer('CivPersonID')->unsigned()->nullable();
			$table->foreign('CivPersonID')->references('PrsnID')->on('OP_PersonContact');
			$table->integer('CivPhysDescID')->unsigned()->nullable();
			$table->foreign('CivPhysDescID')->references('PhysID')->on('OP_PhysicalDesc');
			$table->char('CivGiveName', 1)->nullable();
			$table->char('CivGiveContactInfo', 1)->nullable();
			$table->char('CivGivePhysDesc', 1)->nullable();
			$table->char('CivHadVehicle', 1)->nullable();
			$table->char('CivChase', 1)->nullable();
			$table->char('CivInPreviousVehicle', 1)->nullable();
			$table->char('CivResident', 1)->nullable();
			$table->integer('CivChaseType')->nullable();
			$table->char('CivCameraRecord', 1)->nullable();
			$table->char('CivUsedProfanity', 1)->nullable();
			$table->string('CivOccupation')->nullable();
			$table->char('CivGivenCitation', 1)->nullable();
			$table->char('CivGivenWarning', 1)->nullable();
			$table->char('CivHasInjury', 1)->nullable();
			$table->char('CivHasInjuryCare', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Officers', function(Blueprint $table)
		{
			$table->increments('OffID');
			$table->boolean('OffIsVerified')->nullable();
		$table->index('OffIsVerified');
			$table->integer('OffComplaintID')->unsigned()->nullable();
		$table->index('OffComplaintID');
			$table->foreign('OffComplaintID')->references('ComID')->on('OP_Complaints');
			$table->string('OffRole')->nullable();
			$table->integer('OffDeptID')->unsigned()->nullable();
		$table->index('OffDeptID');
			$table->foreign('OffDeptID')->references('DeptID')->on('OP_Departments');
			$table->integer('OffPersonID')->unsigned()->nullable();
			$table->foreign('OffPersonID')->references('PrsnID')->on('OP_PersonContact');
			$table->integer('OffPhysDescID')->unsigned()->nullable();
			$table->foreign('OffPhysDescID')->references('PhysID')->on('OP_PhysicalDesc');
			$table->char('OffGiveName', 1)->nullable();
			$table->char('OffGivePhysDesc', 1)->nullable();
			$table->char('OffHadVehicle', 1)->nullable();
			$table->char('OffInPreviousVehicle', 1)->nullable();
			$table->string('OffPrecinct')->nullable();
			$table->integer('OffBadgeNumber')->nullable();
			$table->integer('OffIDnumber')->nullable();
			$table->string('OffOfficerRank')->nullable();
			$table->char('OffDashCam', 1)->nullable();
			$table->char('OffBodyCam', 1)->nullable();
			$table->string('OffDutyStatus', 10)->nullable();
			$table->char('OffUniform', 1)->nullable();
			$table->char('OffUsedProfanity', 1)->nullable();
			$table->longText('OffAdditionalDetails')->nullable();
			$table->char('OffGaveCompliment', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_PersonContact', function(Blueprint $table)
		{
			$table->increments('PrsnID');
			$table->string('PrsnNamePrefix', 20)->nullable();
			$table->string('PrsnNameFirst')->nullable();
			$table->string('PrsnNickname')->nullable();
			$table->string('PrsnNameMiddle')->nullable();
			$table->string('PrsnNameLast')->nullable();
			$table->string('PrsnNameSuffix', 20)->nullable();
			$table->string('PrsnEmail')->nullable();
			$table->string('PrsnPhoneHome', 20)->nullable();
			$table->string('PrsnPhoneWork', 20)->nullable();
			$table->string('PrsnPhoneMobile', 20)->nullable();
			$table->string('PrsnAddress')->nullable();
			$table->string('PrsnAddress2')->nullable();
			$table->string('PrsnAddressCity')->nullable();
			$table->string('PrsnAddressState', 2)->nullable();
			$table->string('PrsnAddressZip', 10)->nullable();
			$table->date('PrsnBirthday')->nullable();
			$table->string('PrsnFacebook')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_PhysicalDesc', function(Blueprint $table)
		{
			$table->increments('PhysID');
			$table->char('PhysGender', 1)->nullable();
			$table->string('PhysGenderOther')->nullable();
			$table->integer('PhysAge')->unsigned()->nullable();
			$table->integer('PhysHeight')->nullable();
			$table->integer('PhysBodyType')->unsigned()->nullable();
			$table->string('PhysHairDescription')->nullable();
			$table->string('PhysHairFacialDesc')->nullable();
			$table->string('PhysEyes')->nullable();
			$table->string('PhysDistinguishingMarksDesc')->nullable();
			$table->string('PhysVoiceDesc')->nullable();
			$table->string('PhysClothesDesc')->nullable();
			$table->string('PhysDisabilitiesDesc')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_PhysicalDescRace', function(Blueprint $table)
		{
			$table->increments('PhysRaceID');
			$table->integer('PhysRacePhysDescID')->unsigned()->nullable();
			$table->foreign('PhysRacePhysDescID')->references('PhysID')->on('OP_PhysicalDesc');
			$table->integer('PhysRaceRace')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Vehicles', function(Blueprint $table)
		{
			$table->increments('VehicID');
			$table->integer('VehicTransportation')->unsigned()->nullable();
			$table->char('VehicUnmarked', 1)->nullable();
			$table->string('VehicVehicleMake')->nullable();
			$table->string('VehicVehicleModel')->nullable();
			$table->string('VehicVehicleDesc')->nullable();
			$table->string('VehicVehicleLicence')->nullable();
			$table->string('VehicVehicleNumber', 20)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Departments', function(Blueprint $table)
		{
			$table->increments('DeptID');
			$table->string('DeptName')->nullable();
			$table->string('DeptSlug', 100)->nullable();
			$table->integer('DeptType')->unsigned()->nullable();
			$table->boolean('DeptStatus')->nullable();
			$table->dateTime('DeptVerified')->nullable();
			$table->string('DeptEmail')->nullable();
			$table->string('DeptPhoneWork', 20)->nullable();
			$table->string('DeptAddress')->nullable();
			$table->string('DeptAddress2')->nullable();
			$table->string('DeptAddressCity')->nullable();
			$table->string('DeptAddressState', 2)->nullable();
			$table->string('DeptAddressZip', 10)->nullable();
			$table->string('DeptAddressCounty', 100)->nullable();
			$table->string('DeptScoreOpenness', 11)->nullable();
			$table->integer('DeptTotOfficers')->nullable();
			$table->integer('DeptJurisdictionPopulation')->nullable();
			$table->longText('DeptJurisdictionGPS')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Oversight', function(Blueprint $table)
		{
			$table->increments('OverID');
			$table->integer('OverType')->unsigned()->nullable();
			$table->integer('OverCivModel')->unsigned()->nullable();
			$table->integer('OverUserID')->unsigned()->nullable();
			$table->foreign('OverUserID')->references('id')->on('users');
			$table->integer('OverDeptID')->unsigned()->nullable();
		$table->index('OverDeptID');
			$table->foreign('OverDeptID')->references('DeptID')->on('OP_Departments');
			$table->string('OverAgncName')->nullable();
			$table->dateTime('OverVerified')->nullable();
			$table->string('OverNamePrefix', 20)->nullable();
			$table->string('OverNameFirst')->nullable();
			$table->string('OverNickname')->nullable();
			$table->string('OverNameMiddle', 100)->nullable();
			$table->string('OverNameLast')->nullable();
			$table->string('OverNameSuffix', 20)->nullable();
			$table->string('OverTitle')->nullable();
			$table->string('OverIDnumber', 50)->nullable();
			$table->string('OverWebsite')->nullable();
			$table->string('OverFacebook')->nullable();
			$table->string('OverTwitter')->nullable();
			$table->string('OverYouTube')->nullable();
			$table->char('OverHomepageComplaintLink', 1)->nullable();
			$table->string('OverWebComplaintInfo')->nullable();
			$table->string('OverComplaintPDF')->nullable();
			$table->string('OverComplaintWebForm')->nullable();
			$table->string('OverEmail')->nullable();
			$table->string('OverPhoneWork', 20)->nullable();
			$table->string('OverAddress')->nullable();
			$table->string('OverAddress2')->nullable();
			$table->string('OverAddressCity')->nullable();
			$table->string('OverAddressState', 2)->nullable();
			$table->string('OverAddressZip', 10)->nullable();
			$table->integer('OverSubmitDeadline')->nullable();
			$table->boolean('OverOfficialFormNotReq')->nullable();
			$table->boolean('OverOfficialAnon')->nullable();
			$table->boolean('OverWaySubOnline')->nullable();
			$table->boolean('OverWaySubEmail')->nullable();
			$table->boolean('OverWaySubVerbalPhone')->nullable();
			$table->boolean('OverWaySubPaperMail')->nullable();
			$table->boolean('OverWaySubPaperInPerson')->nullable();
			$table->boolean('OverWaySubNotary')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Partners', function(Blueprint $table)
		{
			$table->increments('PartID');
			$table->integer('PartType')->unsigned()->nullable();
			$table->integer('PartUserID')->unsigned()->nullable();
			$table->foreign('PartUserID')->references('id')->on('users');
			$table->integer('PartPersonID')->unsigned()->nullable();
			$table->foreign('PartPersonID')->references('PrsnID')->on('OP_PersonContact');
			$table->string('PartTitle')->nullable();
			$table->string('PartCompanyName')->nullable();
			$table->string('PartCompanyWebsite')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Administrators', function(Blueprint $table)
		{
			$table->increments('AdmID');
			$table->integer('AdmUserID')->unsigned()->nullable();
			$table->foreign('AdmUserID')->references('id')->on('users');
			$table->integer('AdmPersonID')->unsigned()->nullable();
			$table->foreign('AdmPersonID')->references('PrsnID')->on('OP_PersonContact');
			$table->timestamps();
		});
		Schema::create('OP_LinksComplaintDept', function(Blueprint $table)
		{
			$table->increments('LnkComDeptID');
			$table->integer('LnkComDeptComplaintID')->unsigned()->nullable();
			$table->foreign('LnkComDeptComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('LnkComDeptDeptID')->unsigned()->nullable();
		$table->index('LnkComDeptDeptID');
			$table->foreign('LnkComDeptDeptID')->references('DeptID')->on('OP_Departments');
			$table->timestamps();
		});
		Schema::create('OP_LinksComplaintOversight', function(Blueprint $table)
		{
			$table->increments('LnkComOverID');
			$table->integer('LnkComOverComplaintID')->unsigned()->nullable();
		$table->index('LnkComOverComplaintID');
			$table->foreign('LnkComOverComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('LnkComOverOverID')->unsigned()->nullable();
		$table->index('LnkComOverOverID');
			$table->foreign('LnkComOverOverID')->references('OverID')->on('OP_Oversight');
			$table->dateTime('LnkComOverSubmitted')->default(NULL)->nullable();
			$table->dateTime('LnkComOverStillNoResponse')->nullable();
			$table->dateTime('LnkComOverReceived')->default(NULL)->nullable();
			$table->dateTime('LnkComOverInvestigated')->default(NULL)->nullable();
			$table->dateTime('LnkComOverReportDate')->nullable();
			$table->integer('LnkComOverOversightReportEvidenceID')->unsigned()->nullable();
			$table->foreign('LnkComOverOversightReportEvidenceID')->references('')->on('');
			$table->integer('LnkComOverAgencyComplaintNumber')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_LinksComplimentDept', function(Blueprint $table)
		{
			$table->increments('LnkCompliDeptID');
			$table->integer('LnkCompliDeptComplimentID')->unsigned()->nullable();
			$table->foreign('LnkCompliDeptComplimentID')->references('CompliID')->on('OP_Compliments');
			$table->integer('LnkCompliDeptDeptID')->unsigned()->nullable();
			$table->foreign('LnkCompliDeptDeptID')->references('DeptID')->on('OP_Departments');
			$table->timestamps();
		});
		Schema::create('OP_LinksOfficerAllegations', function(Blueprint $table)
		{
			$table->increments('LnkOffAlleID');
			$table->integer('LnkOffAlleOffID')->unsigned()->nullable();
			$table->foreign('LnkOffAlleOffID')->references('OffID')->on('OP_Officers');
			$table->integer('LnkOffAlleAlleID')->unsigned()->nullable();
			$table->foreign('LnkOffAlleAlleID')->references('AlleID')->on('OP_Allegations');
			$table->timestamps();
		});
		Schema::create('OP_LinksOfficerEvents', function(Blueprint $table)
		{
			$table->increments('LnkOffEveID');
			$table->integer('LnkOffEveOffID')->unsigned()->nullable();
			$table->foreign('LnkOffEveOffID')->references('OffID')->on('OP_Officers');
			$table->integer('LnkOffEveEveID')->unsigned()->nullable();
			$table->foreign('LnkOffEveEveID')->references('EveID')->on('OP_EventSequence');
			$table->timestamps();
		});
		Schema::create('OP_LinksOfficerOrders', function(Blueprint $table)
		{
			$table->increments('LnkOffOrdID');
			$table->integer('LnkOffOrdOffID')->unsigned()->nullable();
			$table->foreign('LnkOffOrdOffID')->references('OffID')->on('OP_Officers');
			$table->integer('LnkOffOrdOrdID')->unsigned()->nullable();
			$table->foreign('LnkOffOrdOrdID')->references('OrdID')->on('OP_Orders');
			$table->timestamps();
		});
		Schema::create('OP_LinksOfficerVehicles', function(Blueprint $table)
		{
			$table->increments('LnkOffVehicID');
			$table->integer('LnkOffVehicOffID')->unsigned()->nullable();
			$table->foreign('LnkOffVehicOffID')->references('OffID')->on('OP_Officers');
			$table->integer('LnkOffVehicVehicID')->unsigned()->nullable();
			$table->foreign('LnkOffVehicVehicID')->references('VehicID')->on('OP_Vehicles');
			$table->timestamps();
		});
		Schema::create('OP_LinksCivilianAllegations', function(Blueprint $table)
		{
			$table->increments('LnkCivAlleID');
			$table->integer('LnkCivAlleCivID')->unsigned()->nullable();
			$table->foreign('LnkCivAlleCivID')->references('CivID')->on('OP_Civilians');
			$table->integer('LnkCivAlleAlleID')->unsigned()->nullable();
			$table->foreign('LnkCivAlleAlleID')->references('AlleID')->on('OP_Allegations');
			$table->timestamps();
		});
		Schema::create('OP_LinksCivilianEvents', function(Blueprint $table)
		{
			$table->increments('LnkCivEveID');
			$table->integer('LnkCivEveCivID')->unsigned()->nullable();
			$table->foreign('LnkCivEveCivID')->references('CivID')->on('OP_Civilians');
			$table->integer('LnkCivEveEveID')->unsigned()->nullable();
			$table->foreign('LnkCivEveEveID')->references('EveID')->on('OP_EventSequence');
			$table->timestamps();
		});
		Schema::create('OP_LinksCivilianOrders', function(Blueprint $table)
		{
			$table->increments('LnkCivOrdID');
			$table->integer('LnkCivOrdCivID')->unsigned()->nullable();
			$table->foreign('LnkCivOrdCivID')->references('CivID')->on('OP_Civilians');
			$table->integer('LnkCivOrdOrdID')->unsigned()->nullable();
			$table->foreign('LnkCivOrdOrdID')->references('OrdID')->on('OP_Orders');
			$table->timestamps();
		});
		Schema::create('OP_LinksCivilianVehicles', function(Blueprint $table)
		{
			$table->increments('LnkCivVehicID');
			$table->integer('LnkCivVehicCivID')->unsigned()->nullable();
			$table->foreign('LnkCivVehicCivID')->references('CivID')->on('OP_Civilians');
			$table->integer('LnkCivVehicVehicID')->unsigned()->nullable();
			$table->foreign('LnkCivVehicVehicID')->references('VehicID')->on('OP_Vehicles');
			$table->string('LnkCivVehicRole', 10)->nullable();
			$table->timestamps();
		});
		Schema::create('OP_Visitors', function(Blueprint $table)
		{
			$table->increments('VisID');
			$table->string('VisVersionAB')->nullable();
			$table->integer('VisSubmissionProgress')->nullable();
			$table->string('VisIPaddy')->nullable();
			$table->string('VisTreeVersion')->nullable();
			$table->string('VisUniqueStr')->nullable();
			$table->integer('VisUserID')->unsigned()->nullable();
			$table->foreign('VisUserID')->references('id')->on('users');
			$table->string('VisIsMobile')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_PrivilegeProfiles', function(Blueprint $table)
		{
			$table->increments('PrivID');
			$table->integer('PrivUserID')->unsigned()->nullable();
			$table->foreign('PrivUserID')->references('id')->on('users');
			$table->integer('PrivComplaintID')->unsigned()->nullable();
		$table->index('PrivComplaintID');
			$table->foreign('PrivComplaintID')->references('ComID')->on('OP_Complaints');
			$table->integer('PrivDeptID')->unsigned()->nullable();
		$table->index('PrivDeptID');
			$table->foreign('PrivDeptID')->references('DeptID')->on('OP_Departments');
			$table->string('PrivAccessLevel')->nullable();
			$table->timestamps();
		});
		Schema::create('OP_AdminActions', function(Blueprint $table)
		{
			$table->increments('AdmActID');
			$table->integer('AdmActUserID')->unsigned()->nullable();
			$table->foreign('AdmActUserID')->references('id')->on('users');
			$table->dateTime('AdmActTimestamp')->default('NOW()')->nullable();
			$table->string('AdmActTable')->nullable();
			$table->integer('AdmActRecordID')->nullable();
			$table->longText('AdmActOldData')->nullable();
			$table->longText('AdmActNewData')->nullable();
			$table->timestamps();
		});
	
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
    	Schema::drop('OP_Complaints');
		Schema::drop('OP_Compliments');
		Schema::drop('OP_Incidents');
		Schema::drop('OP_Scenes');
		Schema::drop('OP_AllegSilver');
		Schema::drop('OP_Allegations');
		Schema::drop('OP_OffCompliments');
		Schema::drop('OP_EventSequence');
		Schema::drop('OP_Stops');
		Schema::drop('OP_StopReasons');
		Schema::drop('OP_Searches');
		Schema::drop('OP_SearchContra');
		Schema::drop('OP_SearchSeize');
		Schema::drop('OP_Arrests');
		Schema::drop('OP_Force');
		Schema::drop('OP_ForceSubType');
		Schema::drop('OP_CivWeapons');
		Schema::drop('OP_BodyParts');
		Schema::drop('OP_Orders');
		Schema::drop('OP_Charges');
		Schema::drop('OP_Injuries');
		Schema::drop('OP_InjuryCare');
		Schema::drop('OP_Surveys');
		Schema::drop('OP_ComplaintNotes');
		Schema::drop('OP_Civilians');
		Schema::drop('OP_Officers');
		Schema::drop('OP_PersonContact');
		Schema::drop('OP_PhysicalDesc');
		Schema::drop('OP_PhysicalDescRace');
		Schema::drop('OP_Vehicles');
		Schema::drop('OP_Departments');
		Schema::drop('OP_Oversight');
		Schema::drop('OP_Partners');
		Schema::drop('OP_Administrators');
		Schema::drop('OP_LinksComplaintDept');
		Schema::drop('OP_LinksComplaintOversight');
		Schema::drop('OP_LinksComplimentDept');
		Schema::drop('OP_LinksOfficerAllegations');
		Schema::drop('OP_LinksOfficerEvents');
		Schema::drop('OP_LinksOfficerOrders');
		Schema::drop('OP_LinksOfficerVehicles');
		Schema::drop('OP_LinksCivilianAllegations');
		Schema::drop('OP_LinksCivilianEvents');
		Schema::drop('OP_LinksCivilianOrders');
		Schema::drop('OP_LinksCivilianVehicles');
		Schema::drop('OP_Visitors');
		Schema::drop('OP_PrivilegeProfiles');
		Schema::drop('OP_AdminActions');
	
    }
}
