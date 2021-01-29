<?php 
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-gen-migration.blade.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOpenPoliceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        DB::statement('SET SESSION sql_require_primary_key=0');
    	Schema::create('op_complaints', function(Blueprint $table)
		{
			$table->increments('com_id');
			$table->integer('com_user_id')->nullable();
			$table->integer('com_public_id')->nullable();
			$table->integer('com_status')->unsigned()->nullable();
			$table->index('com_status');
			$table->integer('com_type')->unsigned()->nullable();
			$table->index('com_type');
			$table->integer('com_incident_id')->unsigned()->nullable();
			$table->integer('com_scene_id')->unsigned()->nullable();
			$table->integer('com_publish_officer_name')->nullable();
			$table->integer('com_publish_user_name')->nullable();
			$table->boolean('com_anon')->nullable();
			$table->integer('com_privacy')->unsigned()->nullable();
			$table->string('com_award_medallion', 10)->nullable();
			$table->char('com_all_charges_resolved', 1)->nullable();
			$table->char('com_anyone_charged', 1)->nullable();
			$table->char('com_attorney_want', 1)->nullable();
			$table->char('com_attorney_has', 1)->nullable();
			$table->char('com_attorney_oked', 1)->nullable();
			$table->integer('com_unresolved_charges_actions')->unsigned()->default('0')->nullable();
			$table->char('com_file_lawsuit', 1)->nullable();
			$table->longText('com_alleg_list')->nullable();
			$table->longText('com_summary')->nullable();
			$table->char('com_officer_injured', 1)->nullable();
			$table->string('com_officer_injured_desc')->nullable();
			$table->char('com_tried_other_ways', 1)->nullable();
			$table->longText('com_tried_other_ways_desc')->nullable();
			$table->string('com_how_hear')->nullable();
			$table->longText('com_feedback')->nullable();
			$table->boolean('com_share_data')->nullable();
			$table->char('com_officer_disciplined', 1)->nullable();
			$table->integer('com_officer_discipline_type')->unsigned()->nullable();
			$table->longText('com_media_links')->nullable();
			$table->integer('com_admin_id')->unsigned()->nullable();
			$table->index('com_admin_id');
			$table->integer('com_att_id')->unsigned()->nullable();
			$table->longText('com_notes')->nullable();
			$table->string('com_slug', 255)->nullable();
			$table->dateTime('com_record_submitted')->nullable();
			$table->index('com_record_submitted');
			$table->string('com_submission_progress')->nullable();
			$table->string('com_version_ab')->nullable();
			$table->string('com_tree_version', 50)->nullable();
			$table->string('com_honey_pot')->nullable();
			$table->boolean('com_is_mobile')->nullable();
			$table->string('com_unique_str', 20)->nullable();
			$table->string('com_ip_addy')->nullable();
			$table->boolean('com_is_demo')->default(0)->nullable();
			$table->boolean('com_want_attorney_but_file')->nullable();
			$table->timestamps();
		});
		Schema::create('op_incidents', function(Blueprint $table)
		{
			$table->increments('inc_id');
			$table->integer('inc_complaint_id')->unsigned()->nullable();
			$table->index('inc_complaint_id');
			$table->string('inc_address')->nullable();
			$table->string('inc_address2')->nullable();
			$table->integer('inc_borough')->unsigned()->nullable();
			$table->string('inc_address_city')->nullable();
			$table->string('inc_address_state', 2)->nullable();
			$table->string('inc_address_zip', 10)->nullable();
			$table->double('inc_address_lat')->nullable();
			$table->double('inc_address_lng')->nullable();
			$table->string('inc_landmarks')->nullable();
			$table->dateTime('inc_time_start')->nullable();
			$table->dateTime('inc_time_end')->nullable();
			$table->integer('inc_duration')->nullable();
			$table->boolean('inc_public')->nullable();
			$table->timestamps();
		});
		Schema::create('op_scenes', function(Blueprint $table)
		{
			$table->increments('scn_id');
			$table->char('scn_is_vehicle', 1)->nullable();
			$table->integer('scn_type')->unsigned()->nullable();
			$table->longText('scn_description')->nullable();
			$table->char('scn_forcible_entry', 1)->nullable();
			$table->char('scn_cctv', 1)->nullable();
			$table->longText('scn_cctv_desc')->nullable();
			$table->char('scn_is_vehicle_accident', 1)->nullable();
			$table->integer('scn_how_feel')->unsigned()->nullable();
			$table->integer('scn_desires_officers')->unsigned()->nullable();
			$table->string('scn_desires_officers_other')->nullable();
			$table->integer('scn_desires_depts')->unsigned()->nullable();
			$table->string('scn_desires_depts_other')->nullable();
			$table->string('scn_attorney_first_name')->nullable();
			$table->string('scn_attorney_last_name')->nullable();
			$table->string('scn_attorney_email')->nullable();
			$table->integer('scn_why_no_officers')->unsigned()->nullable();
			$table->longText('scn_why_no_officers_other')->nullable();
			$table->timestamps();
		});
		Schema::create('op_alleg_silver', function(Blueprint $table)
		{
			$table->increments('alle_sil_id');
			$table->integer('alle_sil_complaint_id')->unsigned()->nullable();
			$table->char('alle_sil_stop_yn', 1)->nullable();
			$table->char('alle_sil_stop_wrongful', 1)->nullable();
			$table->char('alle_sil_officer_id', 1)->nullable();
			$table->char('alle_sil_officer_refuse_id', 1)->nullable();
			$table->char('alle_sil_search_yn', 1)->nullable();
			$table->char('alle_sil_search_wrongful', 1)->nullable();
			$table->char('alle_sil_force_yn', 1)->nullable();
			$table->char('alle_sil_force_unreason', 1)->nullable();
			$table->char('alle_sil_property_yn', 1)->nullable();
			$table->char('alle_sil_property_wrongful', 1)->nullable();
			$table->char('alle_sil_arrest_yn', 1)->nullable();
			$table->char('alle_sil_property_damage', 1)->nullable();
			$table->char('alle_sil_arrest_wrongful', 1)->nullable();
			$table->char('alle_sil_arrest_retaliatory', 1)->nullable();
			$table->char('alle_sil_arrest_miranda', 1)->nullable();
			$table->char('alle_sil_citation_yn', 1)->nullable();
			$table->char('alle_sil_citation_excessive', 1)->nullable();
			$table->char('alle_sil_procedure', 1)->nullable();
			$table->char('alle_sil_neglect_duty', 1)->nullable();
			$table->char('alle_sil_bias', 1)->nullable();
			$table->char('alle_sil_sexual_harass', 1)->nullable();
			$table->char('alle_sil_sexual_assault', 1)->nullable();
			$table->integer('alle_sil_intimidating_weapon')->unsigned()->nullable();
			$table->integer('alle_sil_intimidating_weapon_type')->unsigned()->nullable();
			$table->char('alle_sil_wrongful_entry', 1)->nullable();
			$table->char('alle_sil_repeat_contact', 1)->nullable();
			$table->char('alle_sil_repeat_harass', 1)->nullable();
			$table->char('alle_sil_unbecoming', 1)->nullable();
			$table->char('alle_sil_discourteous', 1)->nullable();
			$table->char('alle_sil_animal_force', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('op_allegations', function(Blueprint $table)
		{
			$table->increments('alle_id');
			$table->integer('alle_complaint_id')->unsigned()->nullable();
			$table->index('alle_complaint_id');
			$table->integer('alle_type')->unsigned()->nullable();
			$table->integer('alle_event_sequence_id')->unsigned()->nullable();
			$table->longText('alle_description')->nullable();
			$table->integer('alle_findings')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_event_sequence', function(Blueprint $table)
		{
			$table->increments('eve_id');
			$table->integer('eve_complaint_id')->unsigned()->nullable();
			$table->index('eve_complaint_id');
			$table->string('eve_type')->nullable();
			$table->timestamps();
		});
		Schema::create('op_stops', function(Blueprint $table)
		{
			$table->increments('stop_id');
			$table->integer('stop_com_id')->unsigned()->nullable();
			$table->integer('stop_event_sequence_id')->unsigned()->nullable();
			$table->longText('stop_stated_reason_desc')->nullable();
			$table->char('stop_subject_asked_to_leave', 1)->nullable();
			$table->longText('stop_subject_statements_desc')->nullable();
			$table->char('stop_enter_private_property', 1)->nullable();
			$table->string('stop_enter_private_property_desc')->nullable();
			$table->char('stop_permission_enter', 1)->nullable();
			$table->char('stop_permission_enter_granted', 1)->nullable();
			$table->char('stop_request_id', 1)->nullable();
			$table->char('stop_refuse_id', 1)->nullable();
			$table->char('stop_request_officer_id', 1)->nullable();
			$table->char('stop_officer_refuse_id', 1)->nullable();
			$table->char('stop_subject_frisk', 1)->nullable();
			$table->char('stop_subject_handcuffed', 1)->nullable();
			$table->char('stop_subject_handcuff_inj_yn', 1)->nullable();
			$table->integer('stop_subject_handcuff_injury')->unsigned()->nullable();
			$table->integer('stop_duration')->nullable();
			$table->char('stop_breath_alcohol', 1)->nullable();
			$table->char('stop_breath_alcohol_failed', 1)->nullable();
			$table->char('stop_breath_cannabis', 1)->nullable();
			$table->char('stop_breath_cannabis_failed', 1)->nullable();
			$table->char('stop_saliva_test', 1)->nullable();
			$table->char('stop_sobriety_other', 1)->nullable();
			$table->string('stop_sobriety_other_describe')->nullable();
			$table->timestamps();
		});
		Schema::create('op_stop_reasons', function(Blueprint $table)
		{
			$table->increments('stop_reas_id');
			$table->integer('stop_reas_stop_id')->unsigned()->nullable();
			$table->integer('stop_reas_reason')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_searches', function(Blueprint $table)
		{
			$table->increments('srch_id');
			$table->integer('srch_com_id')->unsigned()->nullable();
			$table->integer('srch_event_sequence_id')->unsigned()->nullable();
			$table->char('srch_stated_reason', 1)->nullable();
			$table->longText('srch_stated_reason_desc')->nullable();
			$table->char('srch_officer_request', 1)->nullable();
			$table->longText('srch_officer_request_desc')->nullable();
			$table->char('srch_subject_consent', 1)->nullable();
			$table->longText('srch_subject_say')->nullable();
			$table->char('srch_officer_threats', 1)->nullable();
			$table->longText('srch_officer_threats_desc')->nullable();
			$table->char('srch_strip', 1)->nullable();
			$table->string('srch_strip_search_desc')->nullable();
			$table->char('srch_k9_sniff', 1)->nullable();
			$table->char('srch_contraband_discovered', 1)->nullable();
			$table->char('srch_officer_warrant', 1)->nullable();
			$table->longText('srch_officer_warrant_say')->nullable();
			$table->char('srch_seized', 1)->nullable();
			$table->longText('srch_seized_desc')->nullable();
			$table->char('srch_damage', 1)->nullable();
			$table->longText('srch_damage_desc')->nullable();
			$table->timestamps();
		});
		Schema::create('op_search_contra', function(Blueprint $table)
		{
			$table->increments('srch_con_id');
			$table->integer('srch_con_search_id')->unsigned()->nullable();
			$table->integer('srch_con_type')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_search_seize', function(Blueprint $table)
		{
			$table->increments('srch_seiz_id');
			$table->integer('srch_seiz_search_id')->unsigned()->nullable();
			$table->integer('srch_seiz_type')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_arrests', function(Blueprint $table)
		{
			$table->increments('arst_id');
			$table->integer('arst_com_id')->unsigned()->nullable();
			$table->integer('arst_event_sequence_id')->unsigned()->nullable();
			$table->char('arst_charges_filed', 1)->nullable();
			$table->char('arst_stated_reason', 1)->nullable();
			$table->longText('arst_stated_reason_desc')->nullable();
			$table->char('arst_miranda', 1)->nullable();
			$table->char('arst_sita', 1)->nullable();
			$table->char('arst_no_charges_filed', 1)->nullable();
			$table->char('arst_strip', 1)->nullable();
			$table->string('arst_strip_search_desc')->nullable();
			$table->longText('arst_charges_other')->nullable();
			$table->timestamps();
		});
		Schema::create('op_force', function(Blueprint $table)
		{
			$table->increments('for_id');
			$table->integer('for_com_id')->unsigned()->nullable();
			$table->integer('for_event_sequence_id')->unsigned()->nullable();
			$table->char('for_against_animal', 1)->nullable();
			$table->string('for_animal_desc')->nullable();
			$table->integer('for_type')->unsigned()->nullable();
			$table->string('for_type_other')->nullable();
			$table->integer('for_gun_ammo_type')->unsigned()->nullable();
			$table->string('for_gun_desc')->nullable();
			$table->integer('for_how_many_times')->nullable();
			$table->char('for_orders_before_force', 1)->nullable();
			$table->longText('for_orders_subject_response')->nullable();
			$table->char('for_while_handcuffed', 1)->nullable();
			$table->char('for_while_held_down', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('op_force_sub_type', function(Blueprint $table)
		{
			$table->increments('force_sub_id');
			$table->integer('force_sub_force_id')->unsigned()->nullable();
			$table->integer('force_sub_type')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_force_body_parts', function(Blueprint $table)
		{
			$table->increments('frc_bdy_id');
			$table->integer('frc_bdy_force_id')->unsigned()->nullable();
			$table->integer('frc_bdy_part')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_civ_weapons', function(Blueprint $table)
		{
			$table->increments('civ_weap_id');
			$table->integer('civ_weap_com_id')->unsigned()->nullable();
			$table->integer('civ_weap_body_weapon')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_charges', function(Blueprint $table)
		{
			$table->increments('chrg_id');
			$table->integer('chrg_civ_id')->unsigned()->nullable();
			$table->integer('chrg_charges')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_injuries', function(Blueprint $table)
		{
			$table->increments('inj_id');
			$table->integer('inj_subject_id')->unsigned()->nullable();
			$table->integer('inj_type')->unsigned()->nullable();
			$table->integer('inj_how_many_times')->nullable();
			$table->longText('inj_description')->nullable();
			$table->boolean('inj_done')->nullable();
			$table->timestamps();
		});
		Schema::create('op_injury_body_parts', function(Blueprint $table)
		{
			$table->increments('inj_bdy_id');
			$table->integer('inj_bdy_injury_id')->unsigned()->nullable();
			$table->integer('inj_bdy_part')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_injury_care', function(Blueprint $table)
		{
			$table->increments('inj_care_id');
			$table->integer('inj_care_subject_id')->unsigned()->nullable();
			$table->char('inj_care_result_in_death', 1)->nullable();
			$table->dateTime('inj_care_time_of_death')->nullable();
			$table->char('inj_care_got_medical', 1)->nullable();
			$table->string('inj_care_hospital_treated')->nullable();
			$table->string('inj_care_doctor_name_first')->nullable();
			$table->string('inj_care_doctor_name_last')->nullable();
			$table->string('inj_care_doctor_email')->nullable();
			$table->string('inj_care_doctor_phone')->nullable();
			$table->char('inj_care_emergency_on_scene', 1)->nullable();
			$table->string('inj_care_emergency_name_first')->nullable();
			$table->string('inj_care_emergency_name_last')->nullable();
			$table->string('inj_care_emergency_id_number')->nullable();
			$table->string('inj_care_emergency_vehicle_number')->nullable();
			$table->string('inj_care_emergency_licence_number')->nullable();
			$table->string('inj_care_emergency_dept_name')->nullable();
			$table->boolean('inj_care_done')->nullable();
			$table->timestamps();
		});
		Schema::create('op_surveys', function(Blueprint $table)
		{
			$table->increments('surv_id');
			$table->integer('surv_complaint_id')->unsigned()->nullable();
			$table->index('surv_complaint_id');
			$table->integer('surv_auth_user_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_complaint_notes', function(Blueprint $table)
		{
			$table->increments('note_id');
			$table->integer('note_complaint_id')->unsigned()->nullable();
			$table->index('note_complaint_id');
			$table->integer('note_user_id')->unsigned()->nullable();
			$table->longText('note_content')->nullable();
			$table->timestamps();
		});
		Schema::create('op_civilians', function(Blueprint $table)
		{
			$table->increments('civ_id');
			$table->integer('civ_complaint_id')->unsigned()->nullable();
			$table->index('civ_complaint_id');
			$table->integer('civ_user_id')->unsigned()->nullable();
			$table->char('civ_is_creator', 1)->default('N')->nullable();
			$table->string('civ_role', 10)->nullable();
			$table->integer('civ_person_id')->unsigned()->nullable();
			$table->integer('civ_phys_desc_id')->unsigned()->nullable();
			$table->char('civ_give_name', 1)->nullable();
			$table->char('civ_give_contact_info', 1)->nullable();
			$table->char('civ_resident', 1)->nullable();
			$table->string('civ_occupation')->nullable();
			$table->char('civ_had_vehicle', 1)->nullable();
			$table->char('civ_chase', 1)->nullable();
			$table->integer('civ_chase_type')->unsigned()->nullable();
			$table->integer('civ_victim_what_weapon')->unsigned()->nullable();
			$table->integer('civ_victim_use_weapon')->unsigned()->nullable();
			$table->char('civ_camera_record', 1)->nullable();
			$table->char('civ_used_profanity', 1)->nullable();
			$table->char('civ_has_injury', 1)->nullable();
			$table->char('civ_has_injury_care', 1)->nullable();
			$table->char('civ_given_citation', 1)->nullable();
			$table->char('civ_given_warning', 1)->nullable();
			$table->string('civ_citation_number', 25)->nullable();
			$table->longText('civ_charges_other')->nullable();
			$table->char('civ_no_charges_filed', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('op_officers', function(Blueprint $table)
		{
			$table->increments('off_id');
			$table->integer('off_verified_id')->unsigned()->nullable();
			$table->integer('off_complaint_id')->unsigned()->nullable();
			$table->index('off_complaint_id');
			$table->string('off_role')->nullable();
			$table->integer('off_dept_id')->unsigned()->nullable();
			$table->index('off_dept_id');
			$table->integer('off_person_id')->unsigned()->nullable();
			$table->integer('off_phys_desc_id')->unsigned()->nullable();
			$table->char('off_give_name', 1)->nullable();
			$table->char('off_had_vehicle', 1)->nullable();
			$table->string('off_precinct')->nullable();
			$table->string('off_badge_number', 255)->nullable();
			$table->integer('off_id_number')->nullable();
			$table->string('off_officer_rank')->nullable();
			$table->char('off_dash_cam', 1)->nullable();
			$table->char('off_body_cam', 1)->nullable();
			$table->string('off_duty_status', 10)->nullable();
			$table->char('off_uniform', 1)->nullable();
			$table->char('off_used_profanity', 1)->nullable();
			$table->longText('off_additional_details')->nullable();
			$table->char('off_gave_compliment', 1)->nullable();
			$table->timestamps();
		});
		Schema::create('op_officers_verified', function(Blueprint $table)
		{
			$table->increments('off_ver_id');
			$table->integer('off_ver_status')->unsigned()->nullable();
			$table->integer('off_ver_person_id')->unsigned()->nullable();
			$table->integer('off_ver_cnt_complaints')->default('0')->nullable();
			$table->integer('off_ver_cnt_allegations')->default('0')->nullable();
			$table->integer('off_ver_cnt_compliments')->default('0')->nullable();
			$table->integer('off_ver_cnt_commends')->default('0')->nullable();
			$table->string('off_ver_unique_str')->nullable();
			$table->integer('off_ver_submission_progress')->nullable();
			$table->string('off_ver_version_ab')->nullable();
			$table->string('off_ver_tree_version')->nullable();
			$table->string('off_ver_ip_addy')->nullable();
			$table->string('off_ver_is_mobile')->nullable();
			$table->integer('off_ver_user_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_person_contact', function(Blueprint $table)
		{
			$table->increments('prsn_id');
			$table->string('prsn_name_prefix', 20)->nullable();
			$table->string('prsn_name_first')->nullable();
			$table->string('prsn_nickname')->nullable();
			$table->string('prsn_name_middle')->nullable();
			$table->string('prsn_name_last')->nullable();
			$table->string('prsn_name_suffix', 20)->nullable();
			$table->string('prsn_email')->nullable();
			$table->string('prsn_phone_home', 20)->nullable();
			$table->string('prsn_phone_work', 20)->nullable();
			$table->string('prsn_phone_mobile', 20)->nullable();
			$table->string('prsn_address')->nullable();
			$table->string('prsn_address2')->nullable();
			$table->string('prsn_address_city')->nullable();
			$table->string('prsn_address_state', 2)->nullable();
			$table->string('prsn_address_zip', 10)->nullable();
			$table->date('prsn_birthday')->nullable();
			$table->string('prsn_facebook')->nullable();
			$table->integer('prsn_user_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_physical_desc', function(Blueprint $table)
		{
			$table->increments('phys_id');
			$table->char('phys_gender', 1)->nullable();
			$table->string('phys_gender_other')->nullable();
			$table->integer('phys_age')->unsigned()->nullable();
			$table->integer('phys_height')->nullable();
			$table->integer('phys_body_type')->unsigned()->nullable();
			$table->string('phys_general_desc')->nullable();
			$table->timestamps();
		});
		Schema::create('op_physical_desc_race', function(Blueprint $table)
		{
			$table->increments('phys_race_id');
			$table->integer('phys_race_phys_desc_id')->unsigned()->nullable();
			$table->integer('phys_race_race')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_vehicles', function(Blueprint $table)
		{
			$table->increments('vehic_id');
			$table->integer('vehic_complaint_id')->unsigned()->nullable();
			$table->boolean('vehic_is_civilian')->nullable();
			$table->integer('vehic_transportation')->unsigned()->nullable();
			$table->char('vehic_unmarked', 1)->nullable();
			$table->string('vehic_vehicle_make')->nullable();
			$table->string('vehic_vehicle_model')->nullable();
			$table->string('vehic_vehicle_desc')->nullable();
			$table->string('vehic_vehicle_licence')->nullable();
			$table->string('vehic_vehicle_number', 20)->nullable();
			$table->timestamps();
		});
		Schema::create('op_departments', function(Blueprint $table)
		{
			$table->increments('dept_id');
			$table->string('dept_name')->nullable();
			$table->string('dept_slug', 100)->nullable();
			$table->integer('dept_type')->unsigned()->nullable();
			$table->integer('dept_status')->unsigned()->nullable();
			$table->dateTime('dept_verified')->nullable();
			$table->string('dept_email')->nullable();
			$table->string('dept_phone_work', 20)->nullable();
			$table->string('dept_address')->nullable();
			$table->string('dept_address2')->nullable();
			$table->string('dept_address_city')->nullable();
			$table->string('dept_address_state', 2)->nullable();
			$table->string('dept_address_zip', 10)->nullable();
			$table->string('dept_address_county', 100)->nullable();
			$table->string('dept_score_openness', 11)->nullable();
			$table->integer('dept_tot_officers')->nullable();
			$table->integer('dept_jurisdiction_population')->nullable();
			$table->longText('dept_jurisdiction_gps')->nullable();
			$table->string('dept_version_ab')->nullable();
			$table->integer('dept_submission_progress')->nullable();
			$table->string('dept_ip_addy')->nullable();
			$table->string('dept_tree_version')->nullable();
			$table->string('dept_unique_str')->nullable();
			$table->integer('dept_user_id')->unsigned()->nullable();
			$table->string('dept_is_mobile')->nullable();
			$table->double('dept_address_lat')->nullable();
			$table->double('dept_address_lng')->nullable();
			$table->boolean('dept_op_compliant')->nullable();
			$table->timestamps();
		});
		Schema::create('op_oversight', function(Blueprint $table)
		{
			$table->increments('over_id');
			$table->integer('over_type')->unsigned()->nullable();
			$table->integer('over_civ_model')->unsigned()->nullable();
			$table->integer('over_user_id')->unsigned()->nullable();
			$table->integer('over_dept_id')->unsigned()->nullable();
			$table->index('over_dept_id');
			$table->string('over_agnc_name')->nullable();
			$table->dateTime('over_verified')->nullable();
			$table->string('over_name_prefix', 20)->nullable();
			$table->string('over_name_first')->nullable();
			$table->string('over_nickname')->nullable();
			$table->string('over_name_middle', 100)->nullable();
			$table->string('over_name_last')->nullable();
			$table->string('over_name_suffix', 20)->nullable();
			$table->string('over_title')->nullable();
			$table->string('over_id_number', 50)->nullable();
			$table->string('over_website')->nullable();
			$table->string('over_facebook')->nullable();
			$table->string('over_twitter')->nullable();
			$table->string('over_youtube')->nullable();
			$table->char('over_homepage_complaint_link', 1)->nullable();
			$table->string('over_web_complaint_info')->nullable();
			$table->string('over_complaint_pdf')->nullable();
			$table->string('over_complaint_web_form')->nullable();
			$table->string('over_email')->nullable();
			$table->string('over_phone_work', 20)->nullable();
			$table->string('over_address')->nullable();
			$table->string('over_address2')->nullable();
			$table->string('over_address_city')->nullable();
			$table->string('over_address_county', 100)->nullable();
			$table->string('over_address_state', 2)->nullable();
			$table->string('over_address_zip', 10)->nullable();
			$table->integer('over_submit_deadline')->nullable();
			$table->boolean('over_official_form_not_req')->nullable();
			$table->boolean('over_official_anon')->nullable();
			$table->boolean('over_way_sub_online')->nullable();
			$table->boolean('over_way_sub_email')->nullable();
			$table->boolean('over_way_sub_verbal_phone')->nullable();
			$table->boolean('over_way_sub_paper_mail')->nullable();
			$table->boolean('over_way_sub_paper_in_person')->nullable();
			$table->boolean('over_way_sub_notary')->nullable();
			$table->boolean('over_keep_email_private')->default(0)->nullable();
			$table->timestamps();
		});
		Schema::create('op_oversight_models', function(Blueprint $table)
		{
			$table->increments('over_mod_id');
			$table->integer('over_mod_oversight_id')->nullable();
			$table->integer('over_mod_civ_model')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_partners', function(Blueprint $table)
		{
			$table->increments('part_id');
			$table->integer('part_type')->unsigned()->nullable();
			$table->boolean('part_status')->default(1)->nullable();
			$table->integer('part_user_id')->unsigned()->nullable();
			$table->integer('part_person_id')->unsigned()->nullable();
			$table->string('part_bio')->nullable();
			$table->string('part_slug')->nullable();
			$table->string('part_company_name')->nullable();
			$table->string('part_title')->nullable();
			$table->string('part_company_website')->nullable();
			$table->longText('part_bio_url')->nullable();
			$table->longText('part_help_reqs')->nullable();
			$table->string('part_geo_desc')->nullable();
			$table->string('part_photo_url')->nullable();
			$table->integer('part_alerts')->unsigned()->nullable();
			$table->string('part_version_ab')->nullable();
			$table->integer('part_submission_progress')->nullable();
			$table->string('part_ip_addy')->nullable();
			$table->string('part_tree_version')->nullable();
			$table->string('part_unique_str')->nullable();
			$table->string('part_is_mobile')->nullable();
			$table->timestamps();
		});
		Schema::create('op_partner_states', function(Blueprint $table)
		{
			$table->increments('prt_sta_id');
			$table->integer('prt_sta_part_id')->unsigned()->nullable();
			$table->string('prt_sta_state', 2)->nullable();
			$table->timestamps();
		});
		Schema::create('op_partner_capac', function(Blueprint $table)
		{
			$table->increments('prt_cap_id');
			$table->integer('prt_cap_part_id')->unsigned()->nullable();
			$table->integer('prt_cap_capacity')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_partner_case_types', function(Blueprint $table)
		{
			$table->increments('prt_cas_id');
			$table->integer('prt_cas_partner_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_partner_filters', function(Blueprint $table)
		{
			$table->increments('prt_flt_id');
			$table->integer('prt_flt_case_id')->unsigned()->nullable();
			$table->integer('prt_flt_filter')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_tester_beta', function(Blueprint $table)
		{
			$table->increments('beta_id');
			$table->string('beta_email')->nullable();
			$table->string('beta_name')->nullable();
			$table->string('beta_last_name')->nullable();
			$table->integer('beta_year')->nullable();
			$table->longText('beta_narrative')->nullable();
			$table->string('beta_how_hear')->nullable();
			$table->date('beta_invited')->nullable();
			$table->integer('beta_user_id')->unsigned()->nullable();
			$table->string('beta_version_ab')->nullable();
			$table->integer('beta_submission_progress')->nullable();
			$table->string('beta_ip_addy')->nullable();
			$table->string('beta_tree_version')->nullable();
			$table->string('beta_unique_str')->nullable();
			$table->string('beta_is_mobile')->nullable();
			$table->timestamps();
		});
		Schema::create('op_administrators', function(Blueprint $table)
		{
			$table->increments('adm_id');
			$table->integer('adm_user_id')->unsigned()->nullable();
			$table->integer('adm_person_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_officer_dept', function(Blueprint $table)
		{
			$table->increments('lnk_off_dept_id');
			$table->integer('lnk_off_dept_officer_id')->unsigned()->nullable();
			$table->integer('lnk_off_dept_dept_id')->unsigned()->nullable();
			$table->date('lnk_off_dept_date_verified')->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_complaint_dept', function(Blueprint $table)
		{
			$table->increments('lnk_com_dept_id');
			$table->integer('lnk_com_dept_complaint_id')->unsigned()->nullable();
			$table->integer('lnk_com_dept_dept_id')->unsigned()->nullable();
			$table->index('lnk_com_dept_dept_id');
			$table->timestamps();
		});
		Schema::create('op_links_complaint_oversight', function(Blueprint $table)
		{
			$table->increments('lnk_com_over_id');
			$table->integer('lnk_com_over_complaint_id')->unsigned()->nullable();
			$table->index('lnk_com_over_complaint_id');
			$table->integer('lnk_com_over_dept_id')->unsigned()->nullable();
			$table->integer('lnk_com_over_over_id')->unsigned()->nullable();
			$table->index('lnk_com_over_over_id');
			$table->dateTime('lnk_com_over_submitted')->default(NULL)->nullable();
			$table->dateTime('lnk_com_over_still_no_response')->nullable();
			$table->dateTime('lnk_com_over_received')->default(NULL)->nullable();
			$table->dateTime('lnk_com_over_investigated')->default(NULL)->nullable();
			$table->dateTime('lnk_com_over_report_date')->nullable();
			$table->integer('lnk_com_over_oversight_report_evidence_id')->nullable();
			$table->integer('lnk_com_over_agency_complaint_number')->nullable();
			$table->dateTime('lnk_com_over_declined')->nullable();
			$table->integer('lnk_com_over_declined_evidence_id')->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_officer_allegations', function(Blueprint $table)
		{
			$table->increments('lnk_off_alle_id');
			$table->integer('lnk_off_alle_off_id')->unsigned()->nullable();
			$table->integer('lnk_off_alle_alle_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_officer_events', function(Blueprint $table)
		{
			$table->increments('lnk_off_eve_id');
			$table->integer('lnk_off_eve_off_id')->unsigned()->nullable();
			$table->integer('lnk_off_eve_eve_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_civilian_force', function(Blueprint $table)
		{
			$table->increments('lnk_civ_frc_id');
			$table->integer('lnk_civ_frc_civ_id')->unsigned()->nullable();
			$table->integer('lnk_civ_frc_force_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_officer_vehicles', function(Blueprint $table)
		{
			$table->increments('lnk_off_vehic_id');
			$table->integer('lnk_off_vehic_off_id')->unsigned()->nullable();
			$table->integer('lnk_off_vehic_vehic_id')->unsigned()->nullable();
			$table->integer('lnk_off_vehic_role')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_civilian_allegations', function(Blueprint $table)
		{
			$table->increments('lnk_civ_alle_id');
			$table->integer('lnk_civ_alle_civ_id')->unsigned()->nullable();
			$table->integer('lnk_civ_alle_alle_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_civilian_events', function(Blueprint $table)
		{
			$table->increments('lnk_civ_eve_id');
			$table->integer('lnk_civ_eve_civ_id')->unsigned()->nullable();
			$table->integer('lnk_civ_eve_eve_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_officer_force', function(Blueprint $table)
		{
			$table->increments('lnk_off_frc_id');
			$table->integer('lnk_off_frc_off_id')->unsigned()->nullable();
			$table->integer('lnk_off_frc_force_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_civilian_vehicles', function(Blueprint $table)
		{
			$table->increments('lnk_civ_vehic_id');
			$table->integer('lnk_civ_vehic_civ_id')->unsigned()->nullable();
			$table->integer('lnk_civ_vehic_vehic_id')->unsigned()->nullable();
			$table->integer('lnk_civ_vehic_role')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_compliments', function(Blueprint $table)
		{
			$table->increments('compli_id');
			$table->integer('compli_user_id')->unsigned()->nullable();
			$table->integer('compli_status')->unsigned()->nullable();
			$table->integer('compli_type')->nullable();
			$table->integer('compli_incident_id')->unsigned()->nullable();
			$table->integer('compli_scene_id')->unsigned()->nullable();
			$table->integer('compli_privacy')->unsigned()->nullable();
			$table->longText('compli_summary')->nullable();
			$table->string('compli_how_hear')->nullable();
			$table->longText('compli_feedback')->nullable();
			$table->string('compli_slug')->nullable();
			$table->longText('compli_notes')->nullable();
			$table->dateTime('compli_record_submitted')->nullable();
			$table->string('compli_submission_progress')->nullable();
			$table->string('compli_version_ab')->nullable();
			$table->string('compli_tree_version', 50)->nullable();
			$table->string('compli_honey_pot')->nullable();
			$table->boolean('compli_is_mobile')->nullable();
			$table->string('compli_unique_str', 20)->nullable();
			$table->string('compli_ip_addy')->nullable();
			$table->integer('compli_public_id')->nullable();
			$table->boolean('compli_is_demo')->default(0)->nullable();
			$table->boolean('compli_share_data')->nullable();
			$table->timestamps();
		});
		Schema::create('op_civ_compliment', function(Blueprint $table)
		{
			$table->increments('civ_comp_id');
			$table->integer('civ_comp_compliment_id')->unsigned()->nullable();
			$table->integer('civ_comp_user_id')->unsigned()->nullable();
			$table->char('civ_comp_is_creator', 1)->default('N')->nullable();
			$table->string('civ_comp_role', 10)->nullable();
			$table->integer('civ_comp_person_id')->unsigned()->nullable();
			$table->integer('civ_comp_phys_desc_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_compliment_dept', function(Blueprint $table)
		{
			$table->increments('lnk_compli_dept_id');
			$table->integer('lnk_compli_dept_compliment_id')->unsigned()->nullable();
			$table->integer('lnk_compli_dept_dept_id')->unsigned()->nullable();
			$table->timestamps();
		});
		Schema::create('op_links_compliment_oversight', function(Blueprint $table)
		{
			$table->increments('lnk_compli_over_id');
			$table->integer('lnk_compli_over_compliment_id')->unsigned()->nullable();
			$table->integer('lnk_compli_over_dept_id')->unsigned()->nullable();
			$table->integer('lnk_compli_over_over_id')->unsigned()->nullable();
			$table->dateTime('lnk_compli_over_submitted')->nullable();
			$table->dateTime('lnk_compli_over_received')->nullable();
			$table->timestamps();
		});
		Schema::create('op_visitors', function(Blueprint $table)
		{
			$table->increments('vis_id');
			$table->string('vis_version_ab')->nullable();
			$table->integer('vis_submission_progress')->nullable();
			$table->string('vis_ip_addy')->nullable();
			$table->string('vis_tree_version')->nullable();
			$table->string('vis_unique_str')->nullable();
			$table->integer('vis_user_id')->unsigned()->nullable();
			$table->string('vis_is_mobile')->nullable();
			$table->timestamps();
		});
		Schema::create('op_privilege_profiles', function(Blueprint $table)
		{
			$table->increments('priv_id');
			$table->integer('priv_user_id')->unsigned()->nullable();
			$table->integer('priv_complaint_id')->unsigned()->nullable();
			$table->index('priv_complaint_id');
			$table->integer('priv_dept_id')->unsigned()->nullable();
			$table->index('priv_dept_id');
			$table->string('priv_access_level')->nullable();
			$table->timestamps();
		});
		Schema::create('op_admin_actions', function(Blueprint $table)
		{
			$table->increments('adm_act_id');
			$table->integer('adm_act_user_id')->unsigned()->nullable();
			$table->string('adm_act_table')->nullable();
			$table->integer('adm_act_record_id')->nullable();
			$table->longText('adm_act_old_data')->nullable();
			$table->longText('adm_act_new_data')->nullable();
			$table->timestamps();
		});
		Schema::create('op_z_edit_departments', function(Blueprint $table)
		{
			$table->increments('zed_dept_id');
			$table->integer('zed_dept_user_id')->nullable();
			$table->integer('zed_dept_duration')->nullable();
			$table->integer('zed_dept_dept_ID')->unsigned()->nullable();
			$table->string('zed_dept_dept_name')->nullable();
			$table->string('zed_dept_dept_slug', 100)->nullable();
			$table->integer('zed_dept_dept_type')->unsigned()->nullable();
			$table->integer('zed_dept_dept_status')->unsigned()->nullable();
			$table->dateTime('zed_dept_dept_verified')->nullable();
			$table->string('zed_dept_dept_email')->nullable();
			$table->string('zed_dept_dept_phone_work', 20)->nullable();
			$table->string('zed_dept_dept_address')->nullable();
			$table->string('zed_dept_dept_address2')->nullable();
			$table->string('zed_dept_dept_address_city')->nullable();
			$table->string('zed_dept_dept_address_state', 2)->nullable();
			$table->string('zed_dept_dept_address_zip', 10)->nullable();
			$table->string('zed_dept_dept_address_county', 100)->nullable();
			$table->string('zed_dept_dept_score_openness', 11)->nullable();
			$table->integer('zed_dept_dept_tot_officers')->nullable();
			$table->integer('zed_dept_dept_jurisdiction_population')->nullable();
			$table->longText('zed_dept_dept_jurisdiction_gps')->nullable();
			$table->integer('zed_dept_dept_user_id')->unsigned()->nullable();
			$table->integer('zed_dept_dept_submission_progress')->nullable();
			$table->string('zed_dept_dept_tree_version')->nullable();
			$table->string('zed_dept_dept_version_ab')->nullable();
			$table->string('zed_dept_dept_unique_str')->nullable();
			$table->string('zed_dept_dept_ip_addy')->nullable();
			$table->string('zed_dept_dept_is_mobile')->nullable();
			$table->double('zed_dept_dept_address_lat')->nullable();
			$table->double('zed_dept_dept_address_lng')->nullable();
			$table->boolean('zed_dept_dept_op_compliant')->nullable();
			$table->timestamps();
		});
		Schema::create('op_z_edit_oversight', function(Blueprint $table)
		{
			$table->increments('zed_over_id');
			$table->integer('zed_over_zed_dept_id')->unsigned()->nullable();
			$table->boolean('zed_over_online_research')->nullable();
			$table->boolean('zed_over_made_dept_call')->nullable();
			$table->boolean('zed_over_made_ia_call')->nullable();
			$table->longText('zed_over_notes')->nullable();
			$table->integer('zed_over_over_ID')->unsigned()->nullable();
			$table->integer('zed_over_over_type')->unsigned()->nullable();
			$table->integer('zed_over_over_civ_model')->unsigned()->nullable();
			$table->integer('zed_over_over_user_id')->unsigned()->nullable();
			$table->integer('zed_over_over_dept_id')->unsigned()->nullable();
			$table->index('zed_over_over_dept_id');
			$table->string('zed_over_over_agnc_name')->nullable();
			$table->dateTime('zed_over_over_verified')->nullable();
			$table->string('zed_over_over_name_prefix', 20)->nullable();
			$table->string('zed_over_over_name_first')->nullable();
			$table->string('zed_over_over_nickname')->nullable();
			$table->string('zed_over_over_name_middle', 100)->nullable();
			$table->string('zed_over_over_name_last')->nullable();
			$table->string('zed_over_over_name_suffix', 20)->nullable();
			$table->string('zed_over_over_title')->nullable();
			$table->string('zed_over_over_id_number', 50)->nullable();
			$table->string('zed_over_over_website')->nullable();
			$table->string('zed_over_over_facebook')->nullable();
			$table->string('zed_over_over_twitter')->nullable();
			$table->string('zed_over_over_youtube')->nullable();
			$table->char('zed_over_over_homepage_complaint_link', 1)->nullable();
			$table->string('zed_over_over_web_complaint_info')->nullable();
			$table->string('zed_over_over_complaint_pdf')->nullable();
			$table->string('zed_over_over_complaint_web_form')->nullable();
			$table->string('zed_over_over_email')->nullable();
			$table->string('zed_over_over_phone_work', 20)->nullable();
			$table->string('zed_over_over_address')->nullable();
			$table->string('zed_over_over_address2')->nullable();
			$table->string('zed_over_over_address_city')->nullable();
			$table->string('zed_over_over_address_county', 100)->nullable();
			$table->string('zed_over_over_address_state', 2)->nullable();
			$table->string('zed_over_over_address_zip', 10)->nullable();
			$table->integer('zed_over_over_submit_deadline')->nullable();
			$table->boolean('zed_over_over_official_form_not_req')->nullable();
			$table->boolean('zed_over_over_official_anon')->nullable();
			$table->boolean('zed_over_over_way_sub_online')->nullable();
			$table->boolean('zed_over_over_way_sub_email')->nullable();
			$table->boolean('zed_over_over_way_sub_verbal_phone')->nullable();
			$table->boolean('zed_over_over_way_sub_paper_mail')->nullable();
			$table->boolean('zed_over_over_way_sub_paper_in_person')->nullable();
			$table->boolean('zed_over_over_way_sub_notary')->nullable();
			$table->boolean('zed_over_over_keep_email_private')->default(0)->nullable();
			$table->timestamps();
		});
		Schema::create('op_z_volun_stat_days', function(Blueprint $table)
		{
			$table->increments('volun_stat_id');
			$table->date('volun_stat_date')->nullable();
			$table->integer('volun_stat_signups')->default('0')->nullable();
			$table->integer('volun_stat_logins')->default('0')->nullable();
			$table->integer('volun_stat_users_unique')->default('0')->nullable();
			$table->integer('volun_stat_depts_unique')->default('0')->nullable();
			$table->integer('volun_stat_online_research')->default('0')->nullable();
			$table->integer('volun_stat_calls_dept')->default('0')->nullable();
			$table->integer('volun_stat_calls_ia')->default('0')->nullable();
			$table->integer('volun_stat_calls_tot')->default('0')->nullable();
			$table->integer('volun_stat_total_edits')->default('0')->nullable();
			$table->integer('volun_stat_online_research_v')->default('0')->nullable();
			$table->integer('volun_stat_calls_dept_v')->default('0')->nullable();
			$table->integer('volun_stat_calls_ia_v')->default('0')->nullable();
			$table->integer('volun_stat_calls_tot_v')->default('0')->nullable();
			$table->integer('volun_stat_total_edits_v')->default('0')->nullable();
			$table->timestamps();
		});
		Schema::create('op_z_volun_user_info', function(Blueprint $table)
		{
			$table->increments('user_info_id');
			$table->integer('user_info_user_id')->unsigned()->nullable();
			$table->integer('user_info_person_contact_id')->unsigned()->nullable();
			$table->integer('user_info_stars')->default('0')->nullable();
			$table->integer('user_info_stars1')->default('0')->nullable();
			$table->integer('user_info_stars2')->default('0')->nullable();
			$table->integer('user_info_stars3')->default('0')->nullable();
			$table->integer('user_info_depts')->default('0')->nullable();
			$table->integer('user_info_avg_time_dept')->default('0')->nullable();
			$table->timestamps();
		});
		Schema::create('op_z_complaint_reviews', function(Blueprint $table)
		{
			$table->increments('com_rev_id');
			$table->integer('com_rev_complaint')->unsigned()->nullable();
			$table->integer('com_rev_user')->unsigned()->nullable();
			$table->date('com_rev_date')->nullable();
			$table->string('com_rev_type', 10)->nullable();
			$table->integer('com_rev_complaint_type')->unsigned()->nullable();
			$table->string('com_rev_status', 50)->nullable();
			$table->string('com_rev_next_action')->nullable();
			$table->longText('com_rev_note')->nullable();
			$table->boolean('com_rev_one_incident')->nullable();
			$table->boolean('com_rev_civilian_contact')->nullable();
			$table->boolean('com_rev_one_officer')->nullable();
			$table->boolean('com_rev_one_allegation')->nullable();
			$table->boolean('com_rev_evidence_uploaded')->nullable();
			$table->integer('com_rev_english_skill')->nullable();
			$table->integer('com_rev_readability')->nullable();
			$table->integer('com_rev_consistency')->nullable();
			$table->integer('com_rev_realistic')->nullable();
			$table->integer('com_rev_outrage')->nullable();
			$table->boolean('com_rev_explicit_lang')->nullable();
			$table->boolean('com_rev_graphic_content')->nullable();
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
    	Schema::drop('op_complaints');
		Schema::drop('op_incidents');
		Schema::drop('op_scenes');
		Schema::drop('op_alleg_silver');
		Schema::drop('op_allegations');
		Schema::drop('op_event_sequence');
		Schema::drop('op_stops');
		Schema::drop('op_stop_reasons');
		Schema::drop('op_searches');
		Schema::drop('op_search_contra');
		Schema::drop('op_search_seize');
		Schema::drop('op_arrests');
		Schema::drop('op_force');
		Schema::drop('op_force_sub_type');
		Schema::drop('op_force_body_parts');
		Schema::drop('op_civ_weapons');
		Schema::drop('op_charges');
		Schema::drop('op_injuries');
		Schema::drop('op_injury_body_parts');
		Schema::drop('op_injury_care');
		Schema::drop('op_surveys');
		Schema::drop('op_complaint_notes');
		Schema::drop('op_civilians');
		Schema::drop('op_officers');
		Schema::drop('op_officers_verified');
		Schema::drop('op_person_contact');
		Schema::drop('op_physical_desc');
		Schema::drop('op_physical_desc_race');
		Schema::drop('op_vehicles');
		Schema::drop('op_departments');
		Schema::drop('op_oversight');
		Schema::drop('op_oversight_models');
		Schema::drop('op_partners');
		Schema::drop('op_partner_states');
		Schema::drop('op_partner_capac');
		Schema::drop('op_partner_case_types');
		Schema::drop('op_partner_filters');
		Schema::drop('op_tester_beta');
		Schema::drop('op_administrators');
		Schema::drop('op_links_officer_dept');
		Schema::drop('op_links_complaint_dept');
		Schema::drop('op_links_complaint_oversight');
		Schema::drop('op_links_officer_allegations');
		Schema::drop('op_links_officer_events');
		Schema::drop('op_links_civilian_force');
		Schema::drop('op_links_officer_vehicles');
		Schema::drop('op_links_civilian_allegations');
		Schema::drop('op_links_civilian_events');
		Schema::drop('op_links_officer_force');
		Schema::drop('op_links_civilian_vehicles');
		Schema::drop('op_compliments');
		Schema::drop('op_civ_compliment');
		Schema::drop('op_links_compliment_dept');
		Schema::drop('op_links_compliment_oversight');
		Schema::drop('op_visitors');
		Schema::drop('op_privilege_profiles');
		Schema::drop('op_admin_actions');
		Schema::drop('op_z_edit_departments');
		Schema::drop('op_z_edit_oversight');
		Schema::drop('op_z_volun_stat_days');
		Schema::drop('op_z_volun_user_info');
		Schema::drop('op_z_complaint_reviews');
	
    }
}
