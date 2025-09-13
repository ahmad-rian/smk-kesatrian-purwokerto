<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use App\Livewire\Admin\SiteSettings\Index as SiteSettingsIndex;
use App\Livewire\Admin\SiteSettings\Create as SiteSettingsCreate;
use App\Livewire\Admin\SiteSettings\Edit as SiteSettingsEdit;
use App\Livewire\Admin\StudyPrograms\Index as StudyProgramsIndex;
use App\Livewire\Admin\StudyPrograms\Create as StudyProgramsCreate;
use App\Livewire\Admin\StudyPrograms\Edit as StudyProgramsEdit;
use App\Livewire\Admin\SchoolActivities\Index as SchoolActivitiesIndex;
use App\Livewire\Admin\SchoolActivities\Create as SchoolActivitiesCreate;
use App\Livewire\Admin\SchoolActivities\Edit as SchoolActivitiesEdit;
use App\Livewire\Admin\Facilities\Index as FacilitiesIndex;
use App\Livewire\Admin\Facilities\Create as FacilitiesCreate;
use App\Livewire\Admin\Facilities\Edit as FacilitiesEdit;
use App\Livewire\Admin\Galleries\Index as GalleriesIndex;
use App\Livewire\Admin\Galleries\Create as GalleriesCreate;
use App\Livewire\Admin\Galleries\Edit as GalleriesEdit;
use App\Livewire\Admin\GalleryImages\Index as GalleryImagesIndex;
use App\Livewire\Admin\HomeCarousels\Index as HomeCarouselsIndex;
use App\Livewire\Admin\HomeCarousels\Create as HomeCarouselsCreate;
use App\Livewire\Admin\HomeCarousels\Edit as HomeCarouselsEdit;
use App\Livewire\Admin\ContactMessages\Index as ContactMessagesIndex;
use App\Livewire\Admin\ContactMessages\Show as ContactMessagesShow;
use App\Http\Controllers\ActivityController;

// Home route - menggunakan Livewire Welcome component
Route::get('/', \App\Livewire\Frontend\Welcome::class)->name('home');

// Halaman kegiatan
Route::get('/kegiatan', [\App\Http\Controllers\ActivityController::class, 'index'])
    ->name('kegiatan');

// Route untuk detail kegiatan sekolah
Route::get('/kegiatan/{id}', [\App\Http\Controllers\ActivityController::class, 'show'])
    ->where('id', '[0-9A-Za-z]{26}')
    ->name('activity.detail');

// Halaman berita
Route::get('/berita', [\App\Http\Controllers\NewsController::class, 'index'])
    ->name('berita');

// Detail berita
Route::get('/berita/{slug}', [\App\Http\Controllers\NewsController::class, 'show'])
    ->name('berita.detail');

// Dashboard route alias untuk kompatibilitas tes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)
        ->name('dashboard');

    // Settings routes untuk kompatibilitas tes
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)
        ->name('settings.profile');
    Route::get('settings/password', Password::class)
        ->name('settings.password');
    Route::get('settings/appearance', Appearance::class)
        ->name('settings.appearance');
});

// Admin routes dengan SPA support
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', Dashboard::class)
        ->name('admin.dashboard');

    // Settings redirect
    Route::redirect('/settings', '/admin/settings/profile');

    // Settings routes menggunakan Livewire Components untuk SPA
    Route::get('/settings/profile', Profile::class)
        ->name('admin.settings.profile');
    Route::get('/settings/password', Password::class)
        ->name('admin.settings.password');
    Route::get('/settings/appearance', Appearance::class)
        ->name('admin.settings.appearance');

    // Site Settings Management
    Route::get('/site-settings', SiteSettingsIndex::class)
        ->name('admin.site-settings');
    Route::get('/site-settings/create', SiteSettingsCreate::class)
        ->name('admin.site-settings.create');
    Route::get('/site-settings/{siteSetting}/edit', SiteSettingsEdit::class)
        ->name('admin.site-settings.edit');

    // Study Programs Management
    Route::get('/study-programs', StudyProgramsIndex::class)
        ->name('admin.study-programs.index');
    Route::get('/study-programs/create', StudyProgramsCreate::class)
        ->name('admin.study-programs.create');
    Route::get('/study-programs/{studyProgram}/edit', StudyProgramsEdit::class)
        ->name('admin.study-programs.edit');

    // School Activities Management
    Route::get('/school-activities', SchoolActivitiesIndex::class)
        ->name('admin.school-activities.index');
    Route::get('/school-activities/create', SchoolActivitiesCreate::class)
        ->name('admin.school-activities.create');
    Route::get('/school-activities/{schoolActivity}/edit', SchoolActivitiesEdit::class)
        ->name('admin.school-activities.edit');

    // News Management
    Route::get('/news', \App\Livewire\Admin\News\Index::class)
        ->name('admin.news.index');
    Route::get('/news/create', \App\Livewire\Admin\News\Create::class)
        ->name('admin.news.create');
    Route::get('/news/{id}/edit', \App\Livewire\Admin\News\Edit::class)
        ->where('id', '[0-9]+')
        ->name('admin.news.edit');

    // Facilities Management
    Route::get('/facilities', FacilitiesIndex::class)
        ->name('admin.facilities.index');
    Route::get('/facilities/create', FacilitiesCreate::class)
        ->name('admin.facilities.create');
    Route::get('/facilities/{facility}/edit', FacilitiesEdit::class)
        ->name('admin.facilities.edit');

    // Galleries Management
    Route::get('/galleries', GalleriesIndex::class)
        ->name('admin.galleries.index');
    Route::get('/galleries/create', GalleriesCreate::class)
        ->name('admin.galleries.create');
    Route::get('/galleries/{gallery}/edit', GalleriesEdit::class)
        ->name('admin.galleries.edit');

    // Home Carousels Management
    Route::get('/home-carousels', HomeCarouselsIndex::class)
        ->name('admin.home-carousels.index');
    Route::get('/home-carousels/create', HomeCarouselsCreate::class)
        ->name('admin.home-carousels.create');
    Route::get('/home-carousels/{homeCarousel}/edit', HomeCarouselsEdit::class)
        ->name('admin.home-carousels.edit');

    // Contact Messages Management
    Route::get('/contact-messages', ContactMessagesIndex::class)
        ->name('admin.contact-messages.index');
    Route::get('/contact-messages/{contactMessage}/show', ContactMessagesShow::class)
        ->name('admin.contact-messages.show');
    Route::get('/galleries/{gallery}/images', GalleryImagesIndex::class)
        ->name('admin.gallery-images.index');
});

require __DIR__ . '/auth.php';
