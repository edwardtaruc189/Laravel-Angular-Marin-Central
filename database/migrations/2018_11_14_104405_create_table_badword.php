<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBadword extends Migration
{
     public function create_enum($name, $strings) {
        DB::statement("DO $$
        BEGIN
            IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = '" . $name ."') THEN
                CREATE TYPE " .  $name . " AS ENUM
                (
                    " . $strings . "
                );
            END IF;
        END$$;");
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('badword', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word');
            $table->string('status');            
            $table->timestamps();
        });
        $this->create_enum('badword_status',"'0', '1'");
        DB::statement('ALTER TABLE badword ALTER COLUMN status TYPE badword_status  USING (status::badword_status)');
        DB::statement("ALTER TABLE badword ALTER COLUMN status SET DEFAULT '1'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('badword');
    }
}
