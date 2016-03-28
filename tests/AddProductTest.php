<?php
 
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddProductTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test normal flow of adding new product
     *
     * @return void
     */
    public function testAddNewProduct()
    {
        $user = factory(App\User::class)->create();
        $front = Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile',
            [
                'getClientOriginalName' => 'front.png',
                'getClientOriginalExtension' => 'png',
                'isValid' => true
            ]
        );

        $this->mockStorage()->shouldReceive('put')->once()->andReturn(null);

        $this->actingAs($user)
            ->post('/product/add', [
                'name'          => 'product name',
                'brand'         => 'brand name',
                'price'         => 111,
                'currency'      => 'CAD',
                'description'   => 'a short bio',
                'publish'       => false,
                'front'         => $front
            ])
            ->seeJson([
               'status' => 'ok' 
           ]);
    }

    /**
     * Get a mock object for Storage facade
     */
    protected function mockStorage()
    {
        Storage::extend('mock', function () {
            return \Mockery::mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        });

        Config::set('filesystems.disks.mock', ['driver' => 'mock']);
        Config::set('filesystems.default', 'mock');

        return Storage::disk();
    }
}
