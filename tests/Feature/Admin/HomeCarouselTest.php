<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\HomeCarousels\Create;
use App\Livewire\Admin\HomeCarousels\Edit;
use App\Livewire\Admin\HomeCarousels\Index;
use App\Models\HomeCarousel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HomeCarouselTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin untuk testing
        $this->user = User::factory()->create();

        // Setup storage fake
        Storage::fake('public');
    }

    /** @test */
    public function admin_dapat_melihat_daftar_carousel()
    {
        // Buat beberapa carousel untuk testing
        HomeCarousel::create([
            'judul' => 'Carousel 1',
            'gambar' => 'public/carousel/carousel1.jpg',
            'aktif' => true,
            'urutan' => 1,
        ]);

        HomeCarousel::create([
            'judul' => 'Carousel 2',
            'gambar' => 'public/carousel/carousel2.jpg',
            'aktif' => false,
            'urutan' => 2,
        ]);

        // Test halaman index
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->assertSee('Carousel 1')
            ->assertSee('Carousel 2');
    }

    /** @test */
    public function admin_dapat_membuat_carousel_baru()
    {
        $gambar = UploadedFile::fake()->image('carousel.jpg');

        Livewire::actingAs($this->user)
            ->test(Create::class)
            ->set('judul', 'Carousel Baru')
            ->set('gambar', $gambar)
            ->set('aktif', true)
            ->set('urutan', 3)
            ->call('save')
            ->assertHasNoErrors();

        // Verifikasi carousel tersimpan di database
        $this->assertDatabaseHas('home_carousels', [
            'judul' => 'Carousel Baru',
            'aktif' => true,
            'urutan' => 3,
        ]);

        // Verifikasi gambar tersimpan
        $carousel = HomeCarousel::where('judul', 'Carousel Baru')->first();
        $this->assertTrue(Storage::disk('public')->exists(str_replace('public/', '', $carousel->gambar)));
    }

    /** @test */
    public function admin_dapat_mengedit_carousel()
    {
        // Buat carousel untuk diedit
        $carousel = HomeCarousel::create([
            'judul' => 'Carousel Lama',
            'gambar' => 'public/carousel/carousel_lama.jpg',
            'aktif' => true,
            'urutan' => 1,
        ]);

        // Simulasikan file gambar baru
        $gambarBaru = UploadedFile::fake()->image('carousel_baru.jpg');

        Livewire::actingAs($this->user)
            ->test(Edit::class, ['homeCarousel' => $carousel->id])
            ->set('judul', 'Carousel Diperbarui')
            ->set('gambar', $gambarBaru)
            ->set('aktif', false)
            ->set('urutan', 2)
            ->call('update')
            ->assertHasNoErrors();

        // Verifikasi carousel diperbarui di database
        $this->assertDatabaseHas('home_carousels', [
            'id' => $carousel->id,
            'judul' => 'Carousel Diperbarui',
            'aktif' => false,
            'urutan' => 2,
        ]);
    }

    /** @test */
    public function admin_dapat_menghapus_carousel()
    {
        // Buat carousel untuk dihapus
        $carousel = HomeCarousel::create([
            'judul' => 'Carousel Hapus',
            'gambar' => 'public/carousel/carousel_hapus.jpg',
            'aktif' => true,
            'urutan' => 1,
        ]);

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->call('delete', $carousel->id);

        // Verifikasi carousel terhapus dari database
        $this->assertDatabaseMissing('home_carousels', [
            'id' => $carousel->id,
        ]);
    }

    /** @test */
    public function validasi_form_carousel_berfungsi()
    {
        Livewire::actingAs($this->user)
            ->test(Create::class)
            ->set('judul', '') // Judul kosong
            ->set('urutan', 0) // Urutan di bawah minimum
            ->call('save')
            ->assertHasErrors(['judul', 'gambar', 'urutan']);
    }
}
