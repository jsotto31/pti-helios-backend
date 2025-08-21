<?php

    namespace Tests\Feature;

    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Tests\TestCase;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class FacialLogUploadTest extends TestCase
    {
        use RefreshDatabase;

        public function test_csv_file_upload_and_database_insertion()
        {
            Storage::fake('local');

            // Sample CSV content
            $csvContent = <<<CSV
    person_id,time,device_id,site
    1,2025-08-01 12:00:00,ABC123,MainGate
    2,2025-08-02 14:00:00,XYZ456,BackDoor
    CSV;

            // Store the fake file in the storage
            $filePath = 'test.csv';
            Storage::disk('local')->put($filePath, $csvContent);
            $uploadedFile = new UploadedFile(
                Storage::disk('local')->path($filePath),
                'test.csv',
                'text/csv',
                null,
                true
            );

            $response = $this->postJson('/api/upload-logs', [
                'file' => $uploadedFile,
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'status' => 'success',
                'message' => 'CSV import complete.',
            ]);

            $this->assertDatabaseCount('facial_logs', 2);
        }

        public function test_upload_fails_with_invalid_file_type()
        {
            $file = UploadedFile::fake()->create('not_a_csv.pdf', 100, 'application/pdf');

            $response = $this->postJson('/api/upload-logs', [
                'file' => $file,
            ]);

            $response->assertStatus(422); // Laravel validation
            $response->assertJsonValidationErrors(['file']);
        }

        public function test_upload_fails_if_too_many_rows()
        {
            $rows = "person_id,time,device_id,site\n";
            for ($i = 0; $i < 5001; $i++) {
                $rows .= "1,2025-08-01 12:00:00,ABC123,MainGate\n";
            }

            Storage::fake('local');
            Storage::disk('local')->put('bigfile.csv', $rows);
            $file = new UploadedFile(
                Storage::disk('local')->path('bigfile.csv'),
                'bigfile.csv',
                'text/csv',
                null,
                true
            );

            $response = $this->postJson('/api/upload-logs', [
                'file' => $file,
            ]);

            $response->assertStatus(413);
            $response->assertJson([
                'status' => 'error',
            ]);
        }

        public function test_csv_row_is_parsed_correctly()
        {
            $row = ['1', '2025-08-01 12:00:00', 'ABC123', 'MainGate'];
            $headers = ['person_id', 'time', 'device_id', 'site'];

            $data = array_combine($headers, $row);
            $data['time'] = date('Y-m-d H:i:s', strtotime($data['time']));

            $this->assertEquals('2025-08-01 12:00:00', $data['time']);
        }

        public function test_upload_fails_when_file_is_missing()
        {
            $response = $this->postJson('/api/upload-logs', []); // No file sent

            $response->assertStatus(422); // Laravel returns 422 for validation errors
            $response->assertJsonValidationErrors('file'); // Asserts 'file' field has validation error
        }

    }
