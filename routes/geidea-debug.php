<?php

// Temporary debug endpoint - access via: https://elzatuna.com/geidea-debug
Route::get('/geidea-debug', function() {
    // Read file content directly - avoids constructor issues
    $filePath = app_path('Http/Middleware/VerifyCsrfToken.php');
    $fileContent = file_get_contents($filePath);
    
    // Check if '/payments/verify/Geidea' exists in the file
    $hasFix = str_contains($fileContent, "'/payments/verify/Geidea'");
    
    // Extract the $except array lines for display
    preg_match('/protected\s+\$except\s*=\s*\[(.*?)\];/s', $fileContent, $matches);
    $exceptArray = isset($matches[1]) ? trim($matches[1]) : 'Could not parse';
    
    $branch = '';
    $commit = '';
    exec('git branch --show-current 2>&1', $branchOutput);
    exec('git log -1 --oneline 2>&1', $commitOutput);
    $branch = isset($branchOutput[0]) ? $branchOutput[0] : 'unknown';
    $commit = isset($commitOutput[0]) ? $commitOutput[0] : 'unknown';
    
    return response()->json([
        'csrf_fix_deployed' => $hasFix,
        'except_array_content' => $exceptArray,
        'git_branch' => $branch,
        'last_commit' => $commit,
        'action_needed' => $hasFix 
            ? '✅ CSRF fix is active! Test payments now.' 
            : '❌ CRITICAL: Run: git pull && php artisan optimize:clear'
    ]);
})->name('geidea.debug');
