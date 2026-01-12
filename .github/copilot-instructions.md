# GitHub Copilot Instructions - Poliklinik Al-Huda

## Project Overview

This is a **Laravel 12** clinic management system for Poliklinik Al-Huda, supporting both Malay (MS) and English (EN) languages. The application manages medicine inventory, patient records, appointments, and prescriptions.

## Architecture Pattern

We follow a **layered architecture** with strict separation of concerns:

```
Route Attributes → Controller → FormRequest → Service → Repository → Model → Database
```

### Layer Responsibilities

1. **Route Attributes**: Define routes using Spatie Route Attributes in controllers
2. **Controllers**: Handle HTTP requests/responses only
3. **FormRequests**: Validate incoming data
4. **Services**: Contain business logic
5. **Repositories**: Handle data access and database queries
6. **Models**: Eloquent ORM with relationships, scopes, and accessors

## Code Standards

### Always Follow These Patterns

#### 1. Creating New Controllers

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Services\ResourceService;
use App\Traits\HandlesApiResponses;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('admin/resource')]
#[Middleware(['web', 'auth'])]
class ResourceController extends Controller
{
    use HandlesApiResponses;

    protected ResourceService $service;

    public function __construct(ResourceService $service)
    {
        $this->service = $service;
    }

    #[Get('/', name: 'admin.resource.index')]
    public function index()
    {
        $resources = $this->service->getAllResources();
        return view('admin.resource.index', compact('resources'));
    }

    #[Post('/', name: 'admin.resource.store')]
    public function store(StoreResourceRequest $request)
    {
        try {
            $this->service->createResource($request->validated());
            return $this->successRedirect('admin.resource.index', __('messages.created'));
        } catch (\Exception $e) {
            Log::error('Resource creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);
            return $this->errorRedirect(__('messages.create_failed'));
        }
    }

    #[Patch('/{resource}', name: 'admin.resource.update')]
    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        try {
            $this->service->updateResource($resource->id, $request->validated());
            return $this->successRedirect('admin.resource.index', __('messages.updated'));
        } catch (\Exception $e) {
            Log::error('Resource update failed', [
                'id' => $resource->id,
                'error' => $e->getMessage()
            ]);
            return $this->errorRedirect(__('messages.update_failed'));
        }
    }

    #[Delete('/{resource}', name: 'admin.resource.destroy')]
    public function destroy(Resource $resource)
    {
        try {
            $this->service->deleteResource($resource->id);
            return $this->successRedirect('admin.resource.index', __('messages.deleted'));
        } catch (\Exception $e) {
            Log::error('Resource deletion failed', [
                'id' => $resource->id,
                'error' => $e->getMessage()
            ]);
            return $this->errorRedirect(__('messages.delete_failed'));
        }
    }
}
```

**Key Points:**
- ✅ Always use Route Attributes (never add routes to `routes/web.php`)
- ✅ Always use `HandlesApiResponses` trait
- ✅ Always inject Service via constructor
- ✅ Always wrap mutations in try-catch blocks
- ✅ Always log errors with context
- ✅ Always use FormRequests for validation

#### 2. Creating Services

```php
<?php

namespace App\Services;

use App\Models\Resource;
use App\Repositories\ResourceRepository;
use Illuminate\Support\Collection;

class ResourceService
{
    protected ResourceRepository $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllResources(): Collection
    {
        return $this->repository->all();
    }

    public function createResource(array $data): Resource
    {
        // Add business logic here (calculations, validations, etc.)
        return $this->repository->create($data);
    }

    public function updateResource(int $id, array $data): bool
    {
        // Add business logic here
        return $this->repository->update($id, $data);
    }

    public function deleteResource(int $id): bool
    {
        return $this->repository->delete($id);
    }

    // Add custom business methods here
    public function getActiveResources(): Collection
    {
        return $this->repository->getActive();
    }
}
```

**Key Points:**
- ✅ Business logic belongs here, NOT in controllers
- ✅ Always inject Repository via constructor
- ✅ Use type hints for all parameters and return types

#### 3. Creating Repositories

```php
<?php

namespace App\Repositories;

use App\Models\Resource;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ResourceRepository
{
    protected Resource $model;

    public function __construct(Resource $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Resource
    {
        return $this->model->find($id);
    }

    public function create(array $data): Resource
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $resource = $this->findById($id);

        if (!$resource) {
            return false;
        }

        return $resource->update($data);
    }

    public function delete(int $id): bool
    {
        $resource = $this->findById($id);

        if (!$resource) {
            return false;
        }

        return $resource->delete();
    }

    public function getActive(): Collection
    {
        return $this->model->where('status', 'active')->get();
    }
}
```

**Key Points:**
- ✅ All database queries go here
- ✅ No business logic (that belongs in Services)
- ✅ Return types must be specific (Collection, Model, bool, etc.)

#### 4. Creating FormRequests

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or add authorization logic
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', config('resource.categories')),
            'status' => 'nullable|in:' . implode(',', config('resource.statuses')),
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('resource.name')]),
            'category.required' => __('validation.required', ['attribute' => __('resource.category')]),
            'category.in' => __('validation.in', ['attribute' => __('resource.category')]),
        ];
    }
}
```

**Key Points:**
- ✅ Use configuration for validation rules (e.g., `config('resource.categories')`)
- ✅ Use translation keys for error messages
- ✅ Never validate in controllers

#### 5. Creating Models

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'status',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= minimum_quantity');
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = config('resource.status_badges', []);
        return $badges[$this->status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    public function getCategoryLabelAttribute(): string
    {
        $labels = config('resource.category_labels', []);
        return $labels[$this->category] ?? $this->category;
    }

    /**
     * Relationships
     */
    public function items()
    {
        return $this->hasMany(ResourceItem::class);
    }
}
```

**Key Points:**
- ✅ Use scopes for common queries
- ✅ Use accessors for computed attributes
- ✅ Reference configuration files for labels and badges
- ✅ Define relationships clearly

#### 6. Creating Configuration Files

```php
<?php
// config/resource.php

return [
    'categories' => [
        'type1',
        'type2',
        'type3',
    ],

    'category_labels' => [
        'type1' => 'Type 1',
        'type2' => 'Type 2',
        'type3' => 'Type 3',
    ],

    'statuses' => [
        'active',
        'inactive',
        'archived',
    ],

    'status_labels' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'archived' => 'Archived',
    ],

    'status_badges' => [
        'active' => '<span class="badge badge-success">Active</span>',
        'inactive' => '<span class="badge badge-secondary">Inactive</span>',
        'archived' => '<span class="badge badge-warning">Archived</span>',
    ],

    'settings' => [
        'default_per_page' => 15,
        'max_upload_size' => 2048, // KB
    ],
];
```

**Key Points:**
- ✅ Never hardcode categories, statuses, or constants in code
- ✅ Always create a config file for each module
- ✅ Use descriptive key names

## Existing Modules

### Medicine Module (Reference Implementation)
- **Controller**: `app/Http/Controllers/Admin/MedicineController.php`
- **Service**: `app/Services/MedicineService.php`
- **Repository**: `app/Repositories/MedicineRepository.php`
- **Model**: `app/Models/Medicine.php`
- **FormRequests**: `app/Http/Requests/StoreMedicineRequest.php`, `UpdateMedicineRequest.php`, `UpdateStockRequest.php`
- **Config**: `config/medicine.php`

**Use this as a reference for all new modules!**

## Important Rules

### ❌ NEVER DO THIS:

1. **NEVER add routes to `routes/web.php`**
   - Always use Route Attributes in controllers

2. **NEVER put business logic in controllers**
   - Controllers should only handle HTTP request/response
   - Business logic goes in Services

3. **NEVER put database queries in controllers**
   - All queries go in Repositories

4. **NEVER validate in controllers**
   - Always use FormRequests

5. **NEVER hardcode categories, statuses, or constants**
   - Always use configuration files

6. **NEVER ignore errors**
   - Always wrap mutations in try-catch
   - Always log errors with context

7. **NEVER use `dd()`, `dump()`, or `var_dump()` in production code**
   - Use `Log::debug()` instead

8. **NEVER use static methods for business logic**
   - Use dependency injection for testability

### ✅ ALWAYS DO THIS:

1. **ALWAYS use dependency injection in constructors**
   ```php
   public function __construct(ResourceService $service)
   {
       $this->service = $service;
   }
   ```

2. **ALWAYS use type hints**
   ```php
   public function create(array $data): Resource
   ```

3. **ALWAYS log errors with context**
   ```php
   Log::error('Operation failed', [
       'id' => $id,
       'error' => $e->getMessage(),
       'trace' => $e->getTraceAsString()
   ]);
   ```

4. **ALWAYS use translation keys**
   ```php
   return $this->successRedirect('route', __('messages.success'));
   ```

5. **ALWAYS pre-calculate data in controllers, not in views**
   ```php
   // Good
   $stats = $this->service->getStatistics();
   return view('page', compact('stats'));

   // Bad
   // Computing in Blade view
   ```

6. **ALWAYS use `$request->validated()` instead of `$request->all()`**
   ```php
   $this->service->create($request->validated());
   ```

## Multi-Language Support

This application supports Malay (MS) and English (EN).

### Translation Files Location:
- `resources/lang/ms/` - Malay translations
- `resources/lang/en/` - English translations

### Using Translations:

```php
// In controllers
__('medicine.success_created')
__('validation.required', ['attribute' => __('medicine.name')])

// In views
{{ __('medicine.medicine_inventory') }}
{{ __('messages.delete_confirm') }}
```

### Creating New Translation Keys:

Always add translations for both languages:

```php
// resources/lang/ms/resource.php
return [
    'title' => 'Sumber',
    'name' => 'Nama',
    'created' => 'Sumber berjaya dicipta',
];

// resources/lang/en/resource.php
return [
    'title' => 'Resource',
    'name' => 'Name',
    'created' => 'Resource successfully created',
];
```

## Database Conventions

### Migration Naming:
```
YYYY_MM_DD_HHMMSS_create_resources_table.php
YYYY_MM_DD_HHMMSS_add_column_to_resources_table.php
```

### Column Naming:
- Use `snake_case`
- Foreign keys: `resource_id`
- Timestamps: `created_at`, `updated_at`
- Soft deletes: `deleted_at`
- Status columns: `status` (use ENUM or string with validation)

### Always Include:
```php
$table->id();
$table->string('status')->default('active');
$table->timestamps();
$table->softDeletes(); // if needed
```

## View Conventions

### Directory Structure:
```
resources/views/
├── admin/
│   ├── resource/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── dashboard.blade.php
├── portal/
│   ├── home.blade.php
│   └── about.blade.php
└── layouts/
    ├── admin.blade.php
    └── guest.blade.php
```

### View Best Practices:

1. **Use layouts**:
   ```blade
   @extends('layouts.admin')

   @section('title', 'Page Title')

   @section('content')
       <!-- content here -->
   @endsection
   ```

2. **Never put business logic in views**:
   ```blade
   <!-- Bad -->
   {{ $resources->filter(fn($r) => $r->isActive())->count() }}

   <!-- Good -->
   {{ $stats['active_count'] }}
   ```

3. **Use components for reusable elements**:
   ```blade
   <x-admin-sidebar />
   <x-language-switcher />
   ```

## Testing Guidelines

### Service Tests:
```php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ResourceService;
use App\Repositories\ResourceRepository;

class ResourceServiceTest extends TestCase
{
    public function test_can_create_resource()
    {
        $repository = $this->mock(ResourceRepository::class);
        $repository->shouldReceive('create')
            ->once()
            ->andReturn(new Resource(['name' => 'Test']));

        $service = new ResourceService($repository);
        $result = $service->createResource(['name' => 'Test']);

        $this->assertInstanceOf(Resource::class, $result);
    }
}
```

### Controller Tests:
```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ResourceControllerTest extends TestCase
{
    public function test_can_view_resources()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.resource.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.resource.index');
    }
}
```

## Error Handling

### Custom Exceptions:

Create specific exceptions for each module:

```php
// app/Exceptions/ResourceException.php
<?php

namespace App\Exceptions;

use Exception;

class ResourceException extends Exception
{
    public static function notFound(int $id): self
    {
        return new self("Resource with ID {$id} not found.", 404);
    }

    public static function insufficientQuantity(string $name, int $available, int $requested): self
    {
        return new self(
            "Insufficient quantity for {$name}. Available: {$available}, Requested: {$requested}",
            422
        );
    }
}
```

### Using Exceptions:

```php
use App\Exceptions\ResourceException;

public function updateQuantity(int $id, int $quantity)
{
    $resource = $this->repository->findById($id);

    if (!$resource) {
        throw ResourceException::notFound($id);
    }

    if ($resource->quantity < $quantity) {
        throw ResourceException::insufficientQuantity(
            $resource->name,
            $resource->quantity,
            $quantity
        );
    }

    // Process...
}
```

## Security Best Practices

1. **Always validate user input** - Use FormRequests
2. **Use CSRF protection** - Enabled by default in forms
3. **Sanitize output** - Use `{{ }}` in Blade (auto-escapes)
4. **Use parameterized queries** - Eloquent does this automatically
5. **Check authorization** - Implement in FormRequest `authorize()` method
6. **Never commit sensitive data** - Use `.env` for credentials

## Performance Guidelines

1. **Eager load relationships** to avoid N+1 queries:
   ```php
   $resources = Resource::with('items')->get();
   ```

2. **Use query scopes** for common filters:
   ```php
   Resource::active()->lowStock()->get();
   ```

3. **Cache expensive operations**:
   ```php
   Cache::remember('stats', 3600, function () {
       return $this->repository->getStatistics();
   });
   ```

4. **Paginate large datasets**:
   ```php
   $resources = $this->repository->paginate(15);
   ```

## Git Commit Messages

Follow conventional commits:

```
feat: add supplier management module
fix: resolve medicine code generation bug
refactor: extract service layer for inventory
docs: update developer guide
test: add unit tests for MedicineService
chore: update dependencies
```

## Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# List all routes
php artisan route:list

# Run tests
php artisan test

# Run specific test
php artisan test --filter ResourceServiceTest

# Format code (if using Laravel Pint)
./vendor/bin/pint

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
```

## Documentation References

- **Project Documentation**: `REFACTORING_SUMMARY.md`
- **Developer Guide**: `DEVELOPER_GUIDE.md`
- **Laravel Docs**: https://laravel.com/docs/11.x
- **Spatie Route Attributes**: https://github.com/spatie/laravel-route-attributes

## Code Review Checklist

Before suggesting code, verify:

- [ ] Does it follow the layered architecture?
- [ ] Are Route Attributes used instead of `routes/web.php`?
- [ ] Is validation in FormRequest?
- [ ] Is business logic in Service?
- [ ] Are database queries in Repository?
- [ ] Are errors handled with try-catch and logging?
- [ ] Are type hints used?
- [ ] Are configuration files used instead of hardcoded values?
- [ ] Are translations used for user-facing text?
- [ ] Is the code testable?

---

**Remember**: The Medicine module (`MedicineController`, `MedicineService`, `MedicineRepository`) is the reference implementation. Always follow its patterns when creating new modules!
