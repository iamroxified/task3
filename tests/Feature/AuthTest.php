<?php 

use App\Models\User;

test('user registration test', function (){

    $response = $this->post('/auth/register', [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'phone' => '1234567890',
    ]);

    $response->assertStatus(201)
    ->assertJsonStructure([
       'status',
       'message',
       'data' => [
           'accessToken',
           'user' => [
               'userId',
               'firstName',
               'lastName',
               'email',
               'phone',
           ],
       ],
    ]);

$this->assertDatabaseHas('users', [
'firstName' => 'John',
'lastName' => 'Doe',
'email' => 'john@example.com',
]);

$this->assertDatabaseHas('organisations', [
'name' => "John's Organisation",
]);


});

test('user logion test', function (){
    
        $user = User::factory()->create();

        $response = $this->postJson('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'accessToken',
                        'user' => [
                            'userId',
                            'firstName',
                            'lastName',
                            'email',
                            'phone',
                        ],
                    ],
                 ]);
    
                 $this->assertAuthenticated();

});

test('user cannot login with invalid credentials test', function (){
    
    $user = User::factory()->create();

    $response = $this->postJson('/auth/login', [
        'email' => $user->email,
        'password' => 'passwd',
    ]);

    $response->assertStatus(401)
             ->assertJsonStructure([
                'status',
                'message',
                'statusCode'
             ]);
             
             $this->assertGuest();

});

test('user create organization', function (){
    
    $user = User::factory()->create();
    $token = auth()->login($user);

    $response = $this->withHeader('Authorization', "Bearer $token")
                     ->postJson('/auth/organisations', [
                        'name' => 'New Organisation',
                        'description' => 'A test organisation',
                     ]);

    $response->assertStatus(404)
             ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'orgId',
                    'name',
                    'description',
                ],
             ]);

});