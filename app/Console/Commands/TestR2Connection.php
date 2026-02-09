<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\R2StorageService;
use Exception;

class TestR2Connection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r2:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Cloudflare R2 cloud storage connection and configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🧪 Testing Cloudflare R2 Connection...');
        $this->newLine();

        // Step 1: Check Configuration
        $this->info('Step 1: Checking R2 Configuration');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $config = [
            'Account ID' => config('r2.account_id'),
            'Access Key ID' => config('r2.access_key_id') ? '✓ Set (hidden)' : '✗ Not set',
            'Secret Key' => config('r2.secret_access_key') ? '✓ Set (hidden)' : '✗ Not set',
            'Bucket' => config('r2.bucket'),
            'Region' => config('r2.region'),
            'Endpoint' => config('r2.endpoint'),
            'Public URL' => config('r2.public_url'),
        ];

        foreach ($config as $key => $value) {
            if (str_contains($value, '✗')) {
                $this->error("  ✗ {$key}: {$value}");
            } else {
                $this->line("  ✓ {$key}: {$value}");
            }
        }

        // Check if required fields are set
        $requiredFields = ['access_key_id', 'secret_access_key', 'bucket', 'endpoint'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty(config("r2.{$field}"))) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            $this->newLine();
            $this->error('❌ Missing required configuration: ' . implode(', ', $missingFields));
            $this->warn('Please update your .env file with R2 credentials.');
            return Command::FAILURE;
        }

        $this->newLine();

        // Step 2: Test Storage Disk Connection
        $this->info('Step 2: Testing R2 Storage Disk Connection');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            $disk = Storage::disk('r2');
            $this->line('  ✓ R2 disk initialized successfully');
        } catch (Exception $e) {
            $this->error('  ✗ Failed to initialize R2 disk');
            $this->error('  Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->newLine();

        // Step 3: Test Write Permission
        $this->info('Step 3: Testing Write Permission');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $testFileName = 'Courses/test-connection-' . time() . '.txt';
        $testContent = 'R2 connection test - ' . now()->toDateTimeString();
        
        // Enable exception throwing for debugging
        config(['filesystems.disks.r2.throw' => true]);
        $disk = Storage::disk('r2');
        
        try {
            $uploaded = $disk->put($testFileName, $testContent);
            
            if ($uploaded) {
                $this->line("  ✓ Successfully uploaded test file: {$testFileName}");
            } else {
                $this->error('  ✗ Failed to upload test file');
                $this->error('  Upload returned false without exception');
                $this->error('  Check your R2 bucket permissions and API token settings');
                return Command::FAILURE;
            }
        } catch (Exception $e) {
            $this->error('  ✗ Upload failed');
            $this->error('  Exception: ' . get_class($e));
            $this->error('  Message: ' . $e->getMessage());
            $this->newLine();
            $this->warn('  Possible causes:');
            $this->warn('  - API token lacks write permissions');
            $this->warn('  - Bucket name is incorrect');
            $this->warn('  - Endpoint URL is wrong');
            $this->warn('  - Network/firewall blocking connection');
            return Command::FAILURE;
        }

        $this->newLine();

        // Step 4: Test Read Permission
        $this->info('Step 4: Testing Read Permission');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            $exists = $disk->exists($testFileName);
            
            if ($exists) {
                $this->line("  ✓ Test file exists in R2");
                
                $content = $disk->get($testFileName);
                if ($content === $testContent) {
                    $this->line("  ✓ Successfully read test file content");
                } else {
                    $this->warn("  ⚠ File content mismatch");
                }
            } else {
                $this->error('  ✗ Test file not found in R2');
            }
        } catch (Exception $e) {
            $this->error('  ✗ Read failed');
            $this->error('  Error: ' . $e->getMessage());
        }

        $this->newLine();

        // Step 5: Test URL Generation
        $this->info('Step 5: Testing URL Generation');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            $url = $disk->url($testFileName);
            $this->line("  ✓ Public URL generated: {$url}");
        } catch (Exception $e) {
            $this->error('  ✗ URL generation failed');
            $this->error('  Error: ' . $e->getMessage());
        }

        $this->newLine();

        // Step 6: Test File Size
        $this->info('Step 6: Testing File Size Retrieval');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            $size = $disk->size($testFileName);
            $this->line("  ✓ File size: {$size} bytes");
        } catch (Exception $e) {
            $this->error('  ✗ Size retrieval failed');
            $this->error('  Error: ' . $e->getMessage());
        }

        $this->newLine();

        // Step 7: Test R2 Service Class
        $this->info('Step 7: Testing R2StorageService Class');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            $r2Service = new R2StorageService();
            
            // Test file exists
            $exists = $r2Service->fileExists($testFileName);
            $this->line($exists ? '  ✓ Service: fileExists() working' : '  ✗ Service: fileExists() failed');
            
            // Test get URL
            $serviceUrl = $r2Service->getUrl($testFileName);
            $this->line($serviceUrl ? '  ✓ Service: getUrl() working' : '  ✗ Service: getUrl() failed');
            
            // Test get file size
            $serviceSize = $r2Service->getFileSize($testFileName);
            $this->line($serviceSize !== null ? "  ✓ Service: getFileSize() working ({$serviceSize} bytes)" : '  ✗ Service: getFileSize() failed');
            
        } catch (Exception $e) {
            $this->error('  ✗ R2StorageService test failed');
            $this->error('  Error: ' . $e->getMessage());
        }

        $this->newLine();

        // Step 8: Clean up test file
        $this->info('Step 8: Cleaning Up Test File');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            $deleted = $disk->delete($testFileName);
            
            if ($deleted) {
                $this->line("  ✓ Test file deleted successfully");
            } else {
                $this->warn("  ⚠ Could not delete test file: {$testFileName}");
            }
        } catch (Exception $e) {
            $this->warn('  ⚠ Cleanup failed (file may still exist in R2)');
            $this->warn('  Error: ' . $e->getMessage());
        }

        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('✅ R2 Connection Test Complete!');
        $this->newLine();
        $this->info('Your Cloudflare R2 cloud storage is configured correctly and working! 🎉');
        
        return Command::SUCCESS;
    }
}
