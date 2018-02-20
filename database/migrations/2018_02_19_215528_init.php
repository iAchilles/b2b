<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ddl = file_get_contents( __DIR__ . '/../schema/schema.sql');
        DB::unprepared($ddl);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_name <> 'migrations'");
        foreach ($tables as $table) {
            $name = $table->table_name;
            DB::unprepared("DROP table \"${name}\" CASCADE");
        }
    }
}