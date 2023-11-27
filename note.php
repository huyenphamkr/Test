<?php
use IlluminateDatabaseMigrationsMigration;
use IlluminateDatabaseSchemaBlueprint;
use IlluminateSupportFacadesSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('info_users_ver_his', function (Blueprint $table) {
                $table->id()->comment('入職者情報歴史ID');
                $table->bigInteger('info_users_cd')->comment("入職者情報cd");
                $table->integer('ver_his')->nullable()->comment("リビジョン歴史");
                $table->string('first_name' ,60)->nullable()->comment("氏名（姓）");
                $table->string('last_name' ,60)->nullable()->comment("氏名（名）");
                $table->string('first_furigana' ,60)->nullable()->comment("フリガナ（姓）");
                $table->string('last_furigana' ,60)->nullable()->comment("フリガナ（名）");
                $table->string('contract_class' ,20)->nullable()->comment("契約区分 : 【MST_CLASS】.「item_type」= 'CONTRACT_CLASS'");
                $table->string('gender' ,20)->nullable()->comment("性別 : 【MST_CLASS】.「item_type」= 'GENDER'");
                $table->string('tel_number' ,20)->nullable()->comment("電話番号");
                $table->string('post_code' ,7)->nullable()->comment("郵便番号");
                $table->string('adress1' ,40)->nullable()->comment("住所1");
                $table->string('adress2' ,40)->nullable()->comment("住所2");
                $table->string('adress3' ,40)->nullable()->comment("住所3");
                $table->string('adress_furigana1' ,40)->nullable()->comment("住所1（フリガナ）");
                $table->string('adress_furigana2' ,40)->nullable()->comment("住所2（フリガナ）");
                $table->string('adress_furigana3' ,40)->nullable()->comment("住所3（フリガナ）");
                $table->timestamp('birthday')->nullable()->comment("生年月日");
                $table->string('prev_job' ,100)->nullable()->comment("前職（会社名）");
                $table->string('my_number' ,12)->nullable()->comment("マイナンバー");
                $table->string('object_person' ,20)->nullable()->comment("該当者 : 【MST_CLASS】.「item_type」= 'OBJECT_PERSON'");
                $table->string('jion_insurance' ,20)->nullable()->comment("社会保険加入 : 【MST_CLASS】.「item_type」= 'YES_NO'");
                $table->timestamp('insurance_date')->nullable()->comment("社会保険加入日");
                $table->string('insurance_social' ,20)->nullable()->comment("雇用保険加入 : 【MST_CLASS】.「item_type」= 'YES_NO'");
                $table->timestamp('insurance_social_date')->nullable()->comment("雇用保険加入日");
                $table->integer('dependent_number')->nullable()->comment("扶養家族");
                $table->string('bank_code' ,4)->nullable()->comment("銀行コード");
                $table->string('branch_bank_code' ,3)->nullable()->comment("支店コード");
                $table->string('bank_name' ,40)->nullable()->comment("銀行名・振興金庫名・組合名");
                $table->string('branch_bank_name' ,40)->nullable()->comment("支店名・出張所/代理店名");
                $table->string('branch_bank_number' ,6)->nullable()->comment("支店番号");
                $table->string('bank_number' ,14)->nullable()->comment("口座番号");
                $table->string('bank_user_name' ,60)->nullable()->comment("口座氏名");
                $table->string('bank_user_furigana' ,60)->nullable()->comment("口座フリガナ");
                $table->integer('private_car')->nullable()->comment("自家用車");
                $table->string('transport_type' ,20)->nullable()->comment("その他 : 【MST_CLASS】.「item_type」= 'TRANSPORT_TYPE'");
                $table->timestamp('created_at')->nullable()->useCurrent()->comment("登録日時");
                $table->integer('created_id')->nullable()->comment("登録ユーザー");
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate()->comment("更新日時");
                $table->integer('updated_id')->nullable()->comment("更新ユーザー");
                $table->integer('revision')->nullable()->comment("リビジョン番号");
                
    });

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('info_users_ver_his');
    }
};
