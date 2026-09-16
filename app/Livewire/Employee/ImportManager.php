<?php

namespace App\Livewire\Employee;

use App\Services\Employee\EmployeeImportService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportManager extends Component
{
    use WithFileUploads;

    #[Validate('required|file|mimes:csv,txt|max:5120')]
    public $file;

    public bool $isProcessing = false;

    public ?array $results = null;

    public function import(EmployeeImportService $importService): void
    {
        $this->resetErrorBag();
        $this->validate();

        $this->isProcessing = true;
        $this->dispatch('action-loading');

        try {
            $path = $this->file->getRealPath();

            if (! $path) {
                throw ValidationException::withMessages([
                    'file' => 'File sementara tidak dapat dibaca. Silakan pilih ulang file CSV.',
                ]);
            }

            $this->results = $importService->importFromFile($path);
            $this->file = null;
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
        } catch (\Throwable $exception) {
            report($exception);
            $this->addError('file', 'Import tidak dapat diproses. Pastikan CSV mengikuti template resmi.');
        } finally {
            $this->isProcessing = false;
            $this->dispatch('action-complete');
        }

    }

    public function reset_(): void
    {
        $this->reset(['file', 'results', 'isProcessing']);
        $this->resetErrorBag();
    }

    public function getSuccessCountProperty(): int
    {
        return collect($this->results ?? [])
            ->where('status', EmployeeImportService::RESULT_SUCCESS)
            ->count();
    }

    public function getFailedCountProperty(): int
    {
        return collect($this->results ?? [])
            ->where('status', EmployeeImportService::RESULT_FAILED)
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Download Hasil Import (CSV, termasuk password)
    |--------------------------------------------------------------------------
    */

    public function downloadResult()
    {
        $rows = $this->results ?? [];

        return response()->streamDownload(function () use ($rows) {

            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'Row',
                'Status',
                'Message',
                'Employee Number',
                'Full Name',
                'Email',
                'Phone',
                'Gender',
                'Birth Place',
                'Birth Date',
                'Address',
                'Department',
                'Position',
                'Team',
                'Username',
                'Password',
            ]);

            foreach ($rows as $row) {

                fputcsv($out, [
                    $row['row'] ?? '',
                    $row['status'] ?? '',
                    $row['message'] ?? '',
                    $row['employee_number'] ?? '',
                    $row['full_name'] ?? '',
                    $row['email'] ?? '',
                    $row['phone'] ?? '',
                    $row['gender'] ?? '',
                    $row['birth_place'] ?? '',
                    $row['birth_date'] ?? '',
                    $row['address'] ?? '',
                    $row['department'] ?? '',
                    $row['position'] ?? '',
                    $row['team'] ?? '',
                    $row['username'] ?? '',
                    $row['password'] ?? '',
                ]);

            }

            fclose($out);

        }, 'employee-import-result-'.now()->format('Ymd-His').'.csv');
    }

    /*
    |--------------------------------------------------------------------------
    | Download Template CSV
    |--------------------------------------------------------------------------
    */

    public function downloadTemplate()
    {
        return response()->streamDownload(function () {

            $out = fopen('php://output', 'w');

            fputcsv($out, EmployeeImportService::HEADERS);

            fputcsv($out, EmployeeImportService::EXAMPLE_ROW);

            fclose($out);

        }, 'employee-import-template.csv');
    }

    public function render()
    {
        return view('livewire.employee.import-manager');
    }
}
