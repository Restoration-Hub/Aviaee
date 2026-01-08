<?php

use App\Domain\UseCases\FetchUserForLogin;
use App\Domain\Interfaces\IUserRepository;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->userRepo = $this->mock(IUserRepository::class);
    $this->useCase = new FetchUserForLogin($this->userRepo);
});

test('login succeeds for valid credentials', function () {
    $email = 'test@example.com';
    $password = 'secret123';
    $hashedPassword = Hash::make($password);
    $userId = 1;

    // Mock the repository to return user data
    $this->userRepo
        ->shouldReceive('findByEmail')
        ->with($email)
        ->once() 
        ->andReturn([
            'id' => $userId,
            'password' => $hashedPassword,
        ]);

    $result = $this->useCase->execute($email, $password);

    expect($result)->toBe($userId);
});

test('login fails for invalid password', function () {
    $email = 'test@example.com';
    $password = 'wrong-password';
    $hashedPassword = Hash::make('correct-password');

    $this->userRepo
        ->shouldReceive('findByEmail')
        ->with($email)
        ->once()
        ->andReturn([
            'id' => 1,
            'password' => $hashedPassword,
        ]);

    $result = $this->useCase->execute($email, $password);

    expect($result)->toBeNull();
});

test('login fails for non-existing user', function () {
    $email = 'nonexistent@example.com';
    $password = 'anything';

    $this->userRepo
        ->shouldReceive('findByEmail')
        ->with($email)
        ->once()
        ->andReturnNull();

    $result = $this->useCase->execute($email, $password);

    expect($result)->toBeNull();
});
