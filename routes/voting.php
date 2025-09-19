<?php

use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Super Admin routes
Route::middleware(['auth', 'role:super_admin', 'audit'])->prefix('super')->name('super.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // TODO: Add super admin specific routes
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('users.index');
    Route::get('/users', function() { return view('super.users.index', [
            'users' => User::all(),
        ]);
    })->name('users.index');
    Route::get('/features', function() { return view("Hello");
    })->name('features.index');
    Route::get('/settings', function() { return view("Hello");
    })->name('settings.index');

    //Organizations
    // Route::resource('organizations', OrganizationController::class);
});

// Admin/Committee routes
Route::middleware(['auth', 'role:admin,committee', 'audit'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Elections
    Route::resource('elections', ElectionController::class);
    Route::post('elections/{election}/open', [ElectionController::class, 'open'])->name('elections.open');
    Route::post('elections/{election}/close', [ElectionController::class, 'close'])->name('elections.close');
    Route::get('elections/{election}/results', [ElectionController::class, 'results'])->name('elections.results');
    Route::get('elections/{election}/export-results', [ElectionController::class, 'exportResults'])->name('elections.export-results');

    // Voters
    Route::get('elections/{election}/voters', [VoterController::class, 'index'])->name('voters.index');
    Route::get('elections/{election}/voters/import', [VoterController::class, 'import'])->name('voters.import');
    Route::post('elections/{election}/voters/import', [VoterController::class, 'processImport'])->name('voters.process-import');
    Route::post('elections/{election}/voters/send-invitations', [VoterController::class, 'sendInvitations'])->name('voters.send-invitations');

    // Positions
    Route::get('elections/{election}/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::post('elections/{election}/positions', [PositionController::class, 'store'])->name('positions.store');
    Route::put('elections/{election}/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('elections/{election}/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');

    // Candidates
    Route::resource('elections.positions.candidates', CandidateController::class);

    //Organizations
    Route::resource('organizations', OrganizationController::class);
});

// Voter routes
Route::middleware(['auth', 'role:voter'])->prefix('voter')->name('voter.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/status', [VotingController::class, 'status'])->name('status');
});

// Public voting routes (no auth required, token-based)
Route::middleware(['security.headers'])->prefix('vote')->name('vote.')->group(function () {
    Route::get('/{token}', [VotingController::class, 'show'])
        ->middleware('signed')
        ->name('show');

    Route::post('/{token}', [VotingController::class, 'submit'])
        ->middleware(['signed', 'throttle.voting'])
        ->name('submit');

    Route::get('/receipt/{ballotUid}', [VotingController::class, 'receipt'])->name('receipt');
});
