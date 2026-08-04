<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Jalankan SEKALI setelah deploy perubahan SecureFileService (upload
 * baru sudah otomatis ke disk 'local'/private, tapi file yang SUDAH
 * ke-upload sebelumnya masih nyangkut di disk 'public' lama). Command
 * ini pindahin file fisiknya ke disk 'local' -- TIDAK perlu update
 * kolom database sama sekali, karena path relatif yang tersimpan
 * (mis. "companies/xxxx.jpg") formatnya sama persis di kedua disk.
 *
 * Aman dijalankan berkali-kali (idempotent) -- file yang sudah gak ada
 * lagi di disk lama otomatis dilewati.
 */
class MigrateFilesToPrivateDisk extends Command
{
    protected $signature = 'files:migrate-to-private
        {--dry-run : Cuma tampilkan apa yang AKAN dipindah, tanpa benar-benar memindahkan}';

    protected $description = 'Pindahkan logo company, foto employee, dan foto assignment dari disk public (lama) ke disk local/private (baru).';

    /**
     * Folder yang dipakai SecureFileService -- HARUS sama persis
     * dengan folder yang dioper ke store() di CompanyService,
     * EmployeeService, EmployeeAssignmentService.
     */
    private const FOLDERS = [
        'companies',
        'employees',
        'assignments/completion',
    ];

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('Mode DRY-RUN -- tidak ada file yang benar-benar dipindah.');
        }

        $totalMoved = 0;
        $totalSkipped = 0;

        foreach (self::FOLDERS as $folder) {

            $this->info("Memeriksa folder: {$folder}");

            $files = Storage::disk('public')->allFiles($folder);

            if (empty($files)) {

                $this->line("  (kosong, tidak ada file)");

                continue;

            }

            $bar = $this->output->createProgressBar(count($files));

            foreach ($files as $path) {

                // Sudah ada di disk local -- kemungkinan command ini
                // sebelumnya sempat dijalankan sebagian, atau file-nya
                // memang sudah pernah di-upload lewat SecureFileService
                // yang baru. Lewati, jangan ditimpa.
                if (Storage::disk('local')->exists($path)) {

                    $totalSkipped++;
                    $bar->advance();
                    continue;

                }

                if (! $isDryRun) {

                    $contents = Storage::disk('public')->get($path);

                    Storage::disk('local')->put($path, $contents);

                    Storage::disk('public')->delete($path);

                }

                $totalMoved++;
                $bar->advance();

            }

            $bar->finish();
            $this->newLine();

        }

        $this->newLine();
        $this->info("Selesai. Dipindah: {$totalMoved}. Dilewati (sudah ada): {$totalSkipped}.");

        if ($isDryRun) {
            $this->warn('Ini masih DRY-RUN. Jalankan tanpa --dry-run untuk benar-benar memindahkan file.');
        }

        return self::SUCCESS;
    }
}
