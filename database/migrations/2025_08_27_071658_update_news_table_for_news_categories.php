<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Add new news_category_id column
            $table->unsignedBigInteger('news_category_id')->nullable()->after('admin_id');
            
            // Add foreign key constraint
            $table->foreign('news_category_id')
                  ->references('id')
                  ->on('news_categories')
                  ->onDelete('set null');
            
            // Drop the old kategori_id column if it exists
            if (Schema::hasColumn('news', 'kategori_id')) {
                // Remove any foreign key constraints first
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $table = $sm->listTableDetails('news');
                
                foreach ($table->getForeignKeys() as $fk) {
                    if (in_array('kategori_id', $fk->getLocalColumns())) {
                        $table->dropForeign($fk->getName());
                        break;
                    }
                }
                
                // Now drop the column
                $table->dropColumn('kategori_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['news_category_id']);
            
            // Add back the kategori_id column
            $table->unsignedBigInteger('kategori_id')->nullable()->after('admin_id');
            
            // Drop the news_category_id column
            $table->dropColumn('news_category_id');
        });
    }
};
