<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ContactMessages\Index;
use App\Livewire\Admin\ContactMessages\Show;
use App\Livewire\Frontend\ContactForm;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin untuk testing
        $this->user = User::factory()->create();
    }

    /** @test */
    public function pengunjung_dapat_mengirim_pesan_kontak()
    {
        Livewire::test(ContactForm::class)
            ->set('nama', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('telepon', '081234567890')
            ->set('subjek', 'Pertanyaan tentang Pendaftaran')
            ->set('pesan', 'Saya ingin menanyakan tentang prosedur pendaftaran siswa baru.')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('success', true);

        // Verifikasi pesan tersimpan di database
        $this->assertDatabaseHas('contact_messages', [
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'telepon' => '081234567890',
            'subjek' => 'Pertanyaan tentang Pendaftaran',
            'pesan' => 'Saya ingin menanyakan tentang prosedur pendaftaran siswa baru.',
            'status' => ContactMessage::STATUS_UNREAD,
        ]);
    }

    /** @test */
    public function validasi_form_kontak_berfungsi()
    {
        Livewire::test(ContactForm::class)
            ->set('nama', '') // Nama kosong
            ->set('email', 'bukan-email') // Format email salah
            ->set('subjek', '')
            ->set('pesan', 'Pendek') // Pesan terlalu pendek
            ->call('submit')
            ->assertHasErrors(['nama', 'email', 'subjek', 'pesan']);
    }

    /** @test */
    public function admin_dapat_melihat_daftar_pesan_kontak()
    {
        // Buat beberapa pesan kontak untuk testing
        ContactMessage::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'telepon' => '081234567890',
            'subjek' => 'Pertanyaan 1',
            'pesan' => 'Isi pesan 1',
            'status' => ContactMessage::STATUS_UNREAD,
        ]);

        ContactMessage::create([
            'nama' => 'Jane Smith',
            'email' => 'jane@example.com',
            'telepon' => '089876543210',
            'subjek' => 'Pertanyaan 2',
            'pesan' => 'Isi pesan 2',
            'status' => ContactMessage::STATUS_READ,
        ]);

        // Test halaman index
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->assertSee('John Doe')
            ->assertSee('jane@example.com')
            ->assertSee('Pertanyaan 1')
            ->assertSee('Pertanyaan 2');
    }

    /** @test */
    public function admin_dapat_melihat_detail_pesan_kontak()
    {
        // Buat pesan kontak untuk dilihat detailnya
        $message = ContactMessage::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'telepon' => '081234567890',
            'subjek' => 'Pertanyaan Detail',
            'pesan' => 'Ini adalah isi pesan detail yang panjang.',
            'status' => ContactMessage::STATUS_UNREAD,
        ]);

        // Test halaman show
        Livewire::actingAs($this->user)
            ->test(Show::class, ['contactMessage' => $message->id])
            ->assertSee('John Doe')
            ->assertSee('john@example.com')
            ->assertSee('081234567890')
            ->assertSee('Pertanyaan Detail')
            ->assertSee('Ini adalah isi pesan detail yang panjang.');

        // Verifikasi status pesan berubah menjadi sudah dibaca
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => ContactMessage::STATUS_READ,
        ]);
    }

    /** @test */
    public function admin_dapat_menandai_pesan_sebagai_belum_dibaca()
    {
        // Buat pesan kontak dengan status sudah dibaca
        $message = ContactMessage::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'telepon' => '081234567890',
            'subjek' => 'Pertanyaan Tandai',
            'pesan' => 'Isi pesan tandai.',
            'status' => ContactMessage::STATUS_READ,
        ]);

        // Test fungsi markAsUnread
        Livewire::actingAs($this->user)
            ->test(Show::class, ['contactMessage' => $message->id])
            ->call('markAsUnread');

        // Verifikasi status pesan berubah menjadi belum dibaca
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => ContactMessage::STATUS_UNREAD,
        ]);
    }

    /** @test */
    public function admin_dapat_menghapus_pesan_kontak()
    {
        // Buat pesan kontak untuk dihapus
        $message = ContactMessage::create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'telepon' => '081234567890',
            'subjek' => 'Pertanyaan Hapus',
            'pesan' => 'Isi pesan hapus.',
            'status' => ContactMessage::STATUS_UNREAD,
        ]);

        // Test fungsi delete
        Livewire::actingAs($this->user)
            ->test(Show::class, ['contactMessage' => $message->id])
            ->call('delete');

        // Verifikasi pesan terhapus dari database
        $this->assertDatabaseMissing('contact_messages', [
            'id' => $message->id,
        ]);
    }

    /** @test */
    public function admin_dapat_memfilter_pesan_berdasarkan_status()
    {
        // Buat pesan dengan status berbeda
        ContactMessage::create([
            'nama' => 'Unread User',
            'email' => 'unread@example.com',
            'subjek' => 'Pesan Belum Dibaca',
            'pesan' => 'Isi pesan belum dibaca.',
            'status' => ContactMessage::STATUS_UNREAD,
        ]);

        ContactMessage::create([
            'nama' => 'Read User',
            'email' => 'read@example.com',
            'subjek' => 'Pesan Sudah Dibaca',
            'pesan' => 'Isi pesan sudah dibaca.',
            'status' => ContactMessage::STATUS_READ,
        ]);

        // Test filter status belum dibaca
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('status', ContactMessage::STATUS_UNREAD)
            ->assertSee('Unread User')
            ->assertDontSee('Read User');

        // Test filter status sudah dibaca
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('status', ContactMessage::STATUS_READ)
            ->assertSee('Read User')
            ->assertDontSee('Unread User');
    }
}