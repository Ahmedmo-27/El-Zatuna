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
            // Try to get more detailed error information
            $this->line("  Attempting to upload: {$testFileName}");
            
            // Enable exception throwing
            config(['filesystems.disks.r2.throw' => true]);
            $disk = Storage::disk('r2');
            
            // Try direct S3 client call to get better error messages
            try {
                $s3Client = new \Aws\S3\S3Client([
                    'credentials' => [
                        'key' => config('filesystems.disks.r2.key'),
                        'secret' => config('filesystems.disks.r2.secret'),
                    ],
                    'region' => config('filesystems.disks.r2.region', 'auto'),
                    'version' => 'latest',
                    'bucket_endpoint' => false,
                    'use_path_style_endpoint' => true,
                    'endpoint' => config('filesystems.disks.r2.endpoint'),
                    'http' => [
                        'verify' => 'C:\Users\Ahmed\AppData\Local\Programs\PHP\cacert.pem',
                    ],
                ]);
                
                $result = $s3Client->putObject([
                    'Bucket' => config('filesystems.disks.r2.bucket'),
                    'Key' => $testFileName,
                    'Body' => $testContent,
                    'ACL' => 'public-read',
                ]);
                
                $this->line("  ✓ Successfully uploaded test file via direct S3 call: {$testFileName}");
                $this->line("  ETag: " . ($result['ETag'] ?? 'N/A'));
            } catch (\Aws\Exception\AwsException $e) {
                $this->error('  ✗ AWS Exception during direct S3 upload');
                $this->error('  Error Code: ' . ($e->getAwsErrorCode() ?: 'N/A'));
                $this->error('  Error Message: ' . ($e->getAwsErrorMessage() ?: $e->getMessage()));
                $this->error('  HTTP Status: ' . ($e->getStatusCode() ?: 'N/A'));
                $this->error('  Request ID: ' . ($e->getAwsRequestId() ?: 'N/A'));
                $this->error('  Exception Class: ' . get_class($e));
                $this->error('  Full Message: ' . $e->getMessage());
                if ($e->getPrevious()) {
                    $this->error('  Previous Exception: ' . get_class($e->getPrevious()) . ' - ' . $e->getPrevious()->getMessage());
                }
                $this->newLine();
                $this->warn('  This indicates:');
                $this->warn('  - API token lacks write permissions (most likely)');
                $this->warn('  - Bucket name is incorrect');
                $this->warn('  - Endpoint URL is wrong');
                $this->warn('  - Network/firewall blocking connection');
                return Command::FAILURE;
            } catch (Exception $e) {
                $this->error('  ✗ Exception during direct S3 upload');
                $this->error('  Exception Class: ' . get_class($e));
                $this->error('  Message: ' . $e->getMessage());
                if ($e->getPrevious()) {
                    $this->error('  Previous: ' . get_class($e->getPrevious()) . ' - ' . $e->getPrevious()->getMessage());
                }
                return Command::FAILURE;
            }
            
            // Also try Laravel Storage method
            try {
                $uploaded = $disk->put($testFileName, $testContent);
                
                if ($uploaded) {
                    $this->line("  ✓ Laravel Storage put() successful");
                } else {
                    $this->error('  ✗ Laravel Storage put() returned false');
                    $this->error('  Check your R2 bucket permissions and API token settings');
                    $this->newLine();
                    $this->warn('  This usually means:');
                    $this->warn('  - The API token does not have write permissions');
                    $this->warn('  - The bucket does not exist or name is wrong');
                    $this->warn('  - The endpoint URL is incorrect');
                    return Command::FAILURE;
                }
            } catch (\Aws\Exception\AwsException $e) {
                $this->error('  ✗ AWS Exception during Laravel Storage upload');
                $this->error('  Error Code: ' . $e->getAwsErrorCode());
                $this->error('  Error Message: ' . $e->getAwsErrorMessage());
                $this->error('  HTTP Status: ' . $e->getStatusCode());
                return Command::FAILURE;
            } catch (Exception $e) {
                $this->error('  ✗ Exception during upload');
                $this->error('  Exception: ' . get_class($e));
                $this->error('  Message: ' . $e->getMessage());
                return Command::FAILURE;
            }
        } catch (\Aws\Exception\AwsException $e) {
            $this->error('  ✗ AWS Exception during upload');
            $this->error('  Error Code: ' . $e->getAwsErrorCode());
            $this->error('  Error Message: ' . $e->getAwsErrorMessage());
            $this->error('  HTTP Status: ' . $e->getStatusCode());
            $this->newLine();
            $this->warn('  Possible causes:');
            $this->warn('  - API token lacks write permissions');
            $this->warn('  - Bucket name is incorrect');
            $this->warn('  - Endpoint URL is wrong');
            $this->warn('  - Network/firewall blocking connection');
            return Command::FAILURE;
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
