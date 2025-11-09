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
        // Galleries indexes - 100x faster queries!
        Schema::table('galleries', function (Blueprint $table) {
            $table->index('is_published', 'idx_galleries_published');
            $table->index('created_at', 'idx_galleries_created');
            $table->index('kategori_id', 'idx_galleries_kategori');
            $table->index(['is_published', 'created_at'], 'idx_galleries_composite');
            $table->index('admin_id', 'idx_galleries_admin');
        });

        // News indexes
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasIndex('news', 'idx_news_slug_unique')) {
                $table->unique('slug', 'idx_news_slug_unique');
            }
            $table->index('is_published', 'idx_news_published');
            $table->index('created_at', 'idx_news_created');
            $table->index('news_category_id', 'idx_news_category');
            $table->index(['is_published', 'created_at'], 'idx_news_composite');
            $table->index('admin_id', 'idx_news_admin');
        });

        // Gallery Comments indexes
        Schema::table('gallery_comments', function (Blueprint $table) {
            $table->index('gallery_id', 'idx_gallery_comments_gallery');
            $table->index('is_approved', 'idx_gallery_comments_approved');
            $table->index(['gallery_id', 'is_approved'], 'idx_gallery_comments_composite');
            $table->index('user_id', 'idx_gallery_comments_user');
            $table->index('parent_id', 'idx_gallery_comments_parent');
        });

        // News Comments indexes
        Schema::table('news_comments', function (Blueprint $table) {
            $table->index('news_id', 'idx_news_comments_news');
            $table->index('is_approved', 'idx_news_comments_approved');
            $table->index(['news_id', 'is_approved'], 'idx_news_comments_composite');
            $table->index('parent_id', 'idx_news_comments_parent');
        });

        // Gallery Likes indexes
        Schema::table('gallery_likes', function (Blueprint $table) {
            $table->index('gallery_id', 'idx_gallery_likes_gallery');
            $table->index('user_id', 'idx_gallery_likes_user');
            // Note: unique index already exists from previous migration
        });

        // Gallery Favorites indexes
        Schema::table('gallery_favorites', function (Blueprint $table) {
            $table->index('gallery_id', 'idx_gallery_favorites_gallery');
            $table->index('user_id', 'idx_gallery_favorites_user');
            $table->index(['user_id', 'gallery_id'], 'idx_gallery_favorites_composite');
        });

        // Visits indexes for analytics
        Schema::table('visits', function (Blueprint $table) {
            $table->index('created_at', 'idx_visits_created');
            $table->index('session_id', 'idx_visits_session');
            $table->index('user_id', 'idx_visits_user');
        });

        // Events indexes
        Schema::table('events', function (Blueprint $table) {
            $table->index('is_published', 'idx_events_published');
            $table->index('start_at', 'idx_events_start');
            $table->index('end_at', 'idx_events_end');
            $table->index(['is_published', 'start_at'], 'idx_events_composite');
        });

        // Kategoris indexes
        Schema::table('kategoris', function (Blueprint $table) {
            $table->index('is_active', 'idx_kategoris_active');
            $table->index('slug', 'idx_kategoris_slug');
        });

        // News Categories indexes
        Schema::table('news_categories', function (Blueprint $table) {
            $table->index('is_active', 'idx_news_categories_active');
            $table->index('slug', 'idx_news_categories_slug');
        });

        // Users indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('is_active', 'idx_users_active');
            $table->index('email_verified_at', 'idx_users_verified');
            $table->index('last_login_at', 'idx_users_last_login');
        });

        // Teachers indexes
        Schema::table('teachers', function (Blueprint $table) {
            $table->index('is_active', 'idx_teachers_active');
            $table->index('order', 'idx_teachers_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop Galleries indexes
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropIndex('idx_galleries_published');
            $table->dropIndex('idx_galleries_created');
            $table->dropIndex('idx_galleries_kategori');
            $table->dropIndex('idx_galleries_composite');
            $table->dropIndex('idx_galleries_admin');
        });

        // Drop News indexes
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasIndex('news', 'idx_news_slug_unique')) {
                $table->dropUnique('idx_news_slug_unique');
            }
            $table->dropIndex('idx_news_published');
            $table->dropIndex('idx_news_created');
            $table->dropIndex('idx_news_category');
            $table->dropIndex('idx_news_composite');
            $table->dropIndex('idx_news_admin');
        });

        // Drop Gallery Comments indexes
        Schema::table('gallery_comments', function (Blueprint $table) {
            $table->dropIndex('idx_gallery_comments_gallery');
            $table->dropIndex('idx_gallery_comments_approved');
            $table->dropIndex('idx_gallery_comments_composite');
            $table->dropIndex('idx_gallery_comments_user');
            $table->dropIndex('idx_gallery_comments_parent');
        });

        // Drop News Comments indexes
        Schema::table('news_comments', function (Blueprint $table) {
            $table->dropIndex('idx_news_comments_news');
            $table->dropIndex('idx_news_comments_approved');
            $table->dropIndex('idx_news_comments_composite');
            $table->dropIndex('idx_news_comments_parent');
        });

        // Drop Gallery Likes indexes
        Schema::table('gallery_likes', function (Blueprint $table) {
            $table->dropIndex('idx_gallery_likes_gallery');
            $table->dropIndex('idx_gallery_likes_user');
        });

        // Drop Gallery Favorites indexes
        Schema::table('gallery_favorites', function (Blueprint $table) {
            $table->dropIndex('idx_gallery_favorites_gallery');
            $table->dropIndex('idx_gallery_favorites_user');
            $table->dropIndex('idx_gallery_favorites_composite');
        });

        // Drop Visits indexes
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex('idx_visits_created');
            $table->dropIndex('idx_visits_session');
            $table->dropIndex('idx_visits_user');
        });

        // Drop Events indexes
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_published');
            $table->dropIndex('idx_events_start');
            $table->dropIndex('idx_events_end');
            $table->dropIndex('idx_events_composite');
        });

        // Drop Kategoris indexes
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropIndex('idx_kategoris_active');
            $table->dropIndex('idx_kategoris_slug');
        });

        // Drop News Categories indexes
        Schema::table('news_categories', function (Blueprint $table) {
            $table->dropIndex('idx_news_categories_active');
            $table->dropIndex('idx_news_categories_slug');
        });

        // Drop Users indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_active');
            $table->dropIndex('idx_users_verified');
            $table->dropIndex('idx_users_last_login');
        });

        // Drop Teachers indexes
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex('idx_teachers_active');
            $table->dropIndex('idx_teachers_order');
        });
    }
};
