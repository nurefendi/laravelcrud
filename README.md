# Laravel CRUD

- laravel 10.x.x
- php 8.2
- Mysql

## 1. Install laravel, murni tanpa tambahan library apapun.
``` shell
composer create-project --prefer-dist laravel/laravel laravelcrud
```
- `create-project`: perintah composer untuk membuat proyek baru.
- `--prefer-dist`: opsi untuk mengunduh dependensi proyek dalam format arsip yang diunggah ke server, sehingga lebih cepat dan menghemat bandwidth.
- `laravel/laravel`: argumen pertama yang menunjukkan nama paket yang akan diinstal.
- `laravelcrud`: argumen kedua yang menunjukkan nama direktori proyek baru yang akan dibuat.


## 2. Buat database, dan koneksikan.
- buat database
``` sql
create database laravelcrud;
```
- koneksikan

ubah `.env` pada project seperti contoh berikut

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelcrud
DB_USERNAME=root # disini saya menggunakan username root
DB_PASSWORD= # password saya biarkan kosong karena database saya tidak ku set password
```

## 3. Buat model bernama Category beserta migrationnya, dengan field: id, name, is_publish (boolean), created_at, updated_at.


buat migration dan model "Category" dengan menjalankan perintah berikut:
``` shell
php artisan make:model Category -m
```

Perintah ini akan membuat model "Category" beserta migration-nya. Tambahkan field-field yang diperlukan di dalam file migration. 
``` php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->boolean('is_publish')->default(false);
    $table->timestamps();
});
```

dan didalam model category tambahkan field berikut 
``` php
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_publish'];

    // Optional: Define table name if not using default
    protected $table = 'categories';

    // Optional: Define timestamps if not using default
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
```

## 4. Buat factory dan seeder, masukkan 100 data faker.
``` shell
php artisan make:factory CategoryFactory --model=Category
php artisan make:seeder CategorySeeder
```
Buka file "CategoryFactory.php" dan tambahkan definisi faker data yang dibutuhkan.
```php
public function definition()
{
    return [
        'name' => $this->faker->unique()->word(),
        'is_publish' => $this->faker->boolean(),
    ];
}
```
Buka file "CategorySeeder.php" dan gunakan factory untuk membuat 100 data faker. 
```php
public function run()
{
    Category::factory()->count(100)->create();
}
```
selanjutnya jalankan perintah migrasi berikut
``` shel
php artisan migrate
```

## 5. Buat list data kategori + pagination + search
jalankan perintah untuk membuat controller berikut
```shell
php artisan make:controller CategoryController --resource
```
Perintah ini akan membuat controller "CategoryController" dengan resourceful methods.

Buka file "CategoryController.php" dan tambahkan method untuk menampilkan list data 
```php
public function index()
{
    $categories = Category::query();

    if (request()->has('search')) {
        $search = request('search');
        $categories->where('name', 'like', "%$search%");
    }

    $categories = $categories->paginate(10);

    return response()->json($categories);
}
```

## 6. Buat form create & edit category, beserta validasinya (buat form request secara terpisah), tampilkan error jika ada error, dan value input sebelumnya tidak hilang jika ada gagal validasi.

- ### buat form request secara terpisah
jalankan perintah berikut
```shell
php artisan make:request CategoryRequest
```
buka file `CategoryRequest.php` dan tambahkan field berikut
```php
class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'is_publish' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama kategori harus diisi.',
            'name.string' => 'Nama kategori harus berupa string.',
            'name.max' => 'Panjang nama kategori maksimal 255 karakter.',
            'is_publish.boolean' => 'Nilai is_publish harus berupa boolean.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama Kategori',
            'is_publish' => 'Status Publish',
        ];
    }
}
```

- ## Buat form create & edit category dan delete

Buka file "CategoryController.php" dan tambahkan method untuk create seperti berikut
```php
// form create
public function store(CategoryRequest $request)
{
    $category = new Category();
    $category->fill($request->validated());
    $category->save();
    return response()->json(['status'=> 'SUCCESS', 'data' => $category]);
}

// form edit
public function update(CategoryRequest $request, string $id)
{
    $category = Category::find($id);
    if (!$category) 
        return response()->json(['status'=> 'BAD_REQUEST', 'message' => 'Data tidak ditemukan.'], 400);

    $category->fill($request->validated());
    $category->save();
    return response()->json(['status'=> 'SUCCESS', 'data' => $category]);
}

// form delete
public function destroy(string $id)
{
    $category = Category::find($id);
    if (!$category) 
        return response()->json(['status'=> 'BAD_REQUEST', 'message' => 'Data tidak ditemukan.'], 400);
    $category->delete();
    return response()->json(['status'=> 'SUCCESS', 'data' => ['id' => $id]]);
}
``` 

selanjutnya buat routes di `routes/api.php` seperti berikut
```php
Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
```