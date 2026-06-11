<?php
require __DIR__.'/../paowazay_core/vendor/autoload.php';
$app = require_once __DIR__.'/../paowazay_core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $admin = User::firstOrCreate(
        ['email' => 'nisharulnirob@gmail.com'],
        [
            'name' => 'Super Admin',
            'password' => Hash::make('nirob@9564'),
            'email_verified_at' => now(),
        ]
    );
    
    // Ensure the password is updated if the user already existed
    $admin->password = Hash::make('nirob@9564');
    if (!$admin->hasRole('Super Admin')) {
        $admin->assignRole('Super Admin');
    }
    $admin->save();

    echo "<h1>Admin User Updated Successfully!</h1>";
    echo "<p>Email: nisharulnirob@gmail.com</p>";
    echo "<p>Password: nirob@9564</p>";
    echo "<p style='color:red;'><strong>SECURITY WARNING:</strong> Please delete this file (update_admin.php) from your cPanel public_html folder immediately!</p>";
    echo "<a href='/admin/login'>Go to Admin Login</a>";
} catch (\Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
