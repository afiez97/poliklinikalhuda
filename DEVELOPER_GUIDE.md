# Developer Guide - Refactored Architecture

## Quick Start

This guide helps developers understand the new architecture and how to work with the refactored codebase.

---

## Architecture Overview

### Layered Architecture

```
┌─────────────────────────────────────────┐
│   Route Attributes (in Controllers)     │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│   Controllers (HTTP Layer)              │
│   - Handle requests/responses           │
│   - Use FormRequests for validation     │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│   Services (Business Logic Layer)       │
│   - Encapsulate business rules          │
│   - Orchestrate operations              │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│   Repositories (Data Access Layer)      │
│   - Database queries                    │
│   - Data retrieval/persistence          │
└─────────────────┬───────────────────────┘
                  ↓
┌─────────────────────────────────────────┐
│   Models (ORM Layer)                    │
│   - Eloquent models                     │
│   - Relationships, scopes, accessors    │
└─────────────────┬───────────────────────┘
                  ↓
            DATABASE
```

---

## How to Add a New Feature

### Example: Adding a "Supplier" Module

#### Step 1: Create the Model
```bash
php artisan make:model Supplier -m
```

Edit the migration and model with necessary fields.

#### Step 2: Create the Repository
```php
// app/Repositories/SupplierRepository.php
<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Support\Collection;

class SupplierRepository
{
    protected Supplier $model;

    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?Supplier
    {
        return $this->model->find($id);
    }

    public function create(array $data): Supplier
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $supplier = $this->findById($id);
        return $supplier ? $supplier->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $supplier = $this->findById($id);
        return $supplier ? $supplier->delete() : false;
    }
}
```

#### Step 3: Create the Service
```php
// app/Services/SupplierService.php
<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\SupplierRepository;

class SupplierService
{
    protected SupplierRepository $repository;

    public function __construct(SupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllSuppliers()
    {
        return $this->repository->all();
    }

    public function createSupplier(array $data): Supplier
    {
        // Add any business logic here
        return $this->repository->create($data);
    }

    // ... other methods
}
```

#### Step 4: Create FormRequests
```bash
php artisan make:request StoreSupplierRequest
php artisan make:request UpdateSupplierRequest
```

```php
// app/Http/Requests/StoreSupplierRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ];
    }
}
```

#### Step 5: Create the Controller
```php
// app/Http/Controllers/Admin/SupplierController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Services\SupplierService;
use App\Traits\HandlesApiResponses;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('admin/supplier')]
#[Middleware(['web', 'auth'])]
class SupplierController extends Controller
{
    use HandlesApiResponses;

    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    #[Get('/', name: 'admin.supplier.index')]
    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();
        return view('admin.supplier.index', compact('suppliers'));
    }

    #[Get('/create', name: 'admin.supplier.create')]
    public function create()
    {
        return view('admin.supplier.create');
    }

    #[Post('/', name: 'admin.supplier.store')]
    public function store(StoreSupplierRequest $request)
    {
        try {
            $this->supplierService->createSupplier($request->validated());
            return $this->successRedirect('admin.supplier.index', 'Supplier created successfully');
        } catch (\Exception $e) {
            Log::error('Supplier creation failed', ['error' => $e->getMessage()]);
            return $this->errorRedirect('Failed to create supplier');
        }
    }

    #[Get('/{supplier}', name: 'admin.supplier.show')]
    public function show(Supplier $supplier)
    {
        return view('admin.supplier.show', compact('supplier'));
    }

    #[Get('/{supplier}/edit', name: 'admin.supplier.edit')]
    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    #[Patch('/{supplier}', name: 'admin.supplier.update')]
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        try {
            $this->supplierService->updateSupplier($supplier->id, $request->validated());
            return $this->successRedirect('admin.supplier.index', 'Supplier updated successfully');
        } catch (\Exception $e) {
            Log::error('Supplier update failed', ['id' => $supplier->id, 'error' => $e->getMessage()]);
            return $this->errorRedirect('Failed to update supplier');
        }
    }

    #[Delete('/{supplier}', name: 'admin.supplier.destroy')]
    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierService->deleteSupplier($supplier->id);
            return $this->successRedirect('admin.supplier.index', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            Log::error('Supplier deletion failed', ['id' => $supplier->id, 'error' => $e->getMessage()]);
            return $this->errorRedirect('Failed to delete supplier');
        }
    }
}
```

#### Step 6: Clear Route Cache
```bash
php artisan route:clear
```

That's it! Your new module follows the same architecture patterns.

---

## Common Patterns

### 1. Controller Pattern
```php
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
        // Get data from service
        // Return view
    }

    #[Post('/', name: 'admin.resource.store')]
    public function store(StoreResourceRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return $this->successRedirect('admin.resource.index', 'Success message');
        } catch (\Exception $e) {
            Log::error('Operation failed', ['error' => $e->getMessage()]);
            return $this->errorRedirect('Error message');
        }
    }
}
```

### 2. Service Pattern
```php
class ResourceService
{
    protected ResourceRepository $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        // Business logic here
        // Validation, calculations, etc.
        return $this->repository->create($data);
    }
}
```

### 3. Repository Pattern
```php
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

    public function create(array $data): Resource
    {
        return $this->model->create($data);
    }
}
```

### 4. FormRequest Pattern
```php
class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or check permissions
    }

    public function rules(): array
    {
        return [
            'field' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'field.required' => 'Custom error message',
        ];
    }
}
```

---

## Configuration Files

### Creating a New Config File

Create `config/yourmodule.php`:
```php
<?php

return [
    'setting_name' => 'value',
    'categories' => ['option1', 'option2'],
    'labels' => [
        'option1' => 'Label 1',
        'option2' => 'Label 2',
    ],
];
```

Access in code:
```php
$value = config('yourmodule.setting_name');
$categories = config('yourmodule.categories');
```

Use in validation:
```php
'field' => 'required|in:' . implode(',', config('yourmodule.categories'))
```

---

## Error Handling

### Using HandlesApiResponses Trait

```php
use App\Traits\HandlesApiResponses;

class YourController extends Controller
{
    use HandlesApiResponses;

    public function store(Request $request)
    {
        try {
            // Your logic here
            return $this->successRedirect('route.name', 'Success message');
        } catch (\Exception $e) {
            Log::error('Description', ['context' => 'data']);
            return $this->errorRedirect('Error message');
        }
    }
}
```

### Creating Custom Exceptions

```php
// app/Exceptions/YourModuleException.php
<?php

namespace App\Exceptions;

use Exception;

class YourModuleException extends Exception
{
    public static function notFound(int $id): self
    {
        return new self("Resource with ID {$id} not found.", 404);
    }

    public static function customError(string $reason): self
    {
        return new self("Error: {$reason}", 422);
    }
}
```

Use in controller:
```php
use App\Exceptions\YourModuleException;

if (!$resource) {
    throw YourModuleException::notFound($id);
}
```

---

## Route Attributes Reference

### Available Attributes
```php
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Put;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Where;
```

### Examples
```php
// Simple GET route
#[Get('/users', name: 'users.index')]

// POST route
#[Post('/users', name: 'users.store')]

// Route with parameter
#[Get('/users/{user}', name: 'users.show')]

// Route with parameter constraint
#[Get('/users/{id}', name: 'users.show')]
#[Where('id', '[0-9]+')]

// Class-level prefix and middleware
#[Prefix('admin')]
#[Middleware(['web', 'auth'])]
class AdminController extends Controller { }

// Multiple middlewares
#[Middleware(['auth', 'verified'])]
```

---

## Testing

### Service Layer Testing
```php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\MedicineService;
use App\Repositories\MedicineRepository;
use App\Models\Medicine;

class MedicineServiceTest extends TestCase
{
    public function test_can_create_medicine()
    {
        $repository = $this->mock(MedicineRepository::class);
        $repository->shouldReceive('create')
            ->once()
            ->with(['name' => 'Test Medicine'])
            ->andReturn(new Medicine(['name' => 'Test Medicine']));

        $service = new MedicineService($repository);
        $medicine = $service->createMedicine(['name' => 'Test Medicine']);

        $this->assertEquals('Test Medicine', $medicine->name);
    }
}
```

### Controller Testing
```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class MedicineControllerTest extends TestCase
{
    public function test_authenticated_user_can_view_medicines()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.medicine.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.medicine.index');
    }
}
```

---

## Best Practices

### ✅ DO:
- Use dependency injection in constructors
- Keep controllers thin (delegate to services)
- Use FormRequests for validation
- Log all errors with context
- Use try-catch blocks for operations that can fail
- Use configuration files for constants
- Write descriptive method names
- Add docblocks for complex methods

### ❌ DON'T:
- Put business logic in controllers
- Put database queries in controllers
- Hardcode values (use config files)
- Ignore exceptions
- Mix validation with business logic
- Use static methods for testability
- Forget to clear route cache after adding routes

---

## Useful Commands

```bash
# Clear all caches
php artisan optimize:clear

# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# List all routes
php artisan route:list

# Run tests
php artisan test

# Format code (if using Pint)
./vendor/bin/pint
```

---

## Need Help?

1. Check `REFACTORING_SUMMARY.md` for architecture overview
2. Look at `MedicineController` as a reference implementation
3. Review existing Service and Repository classes
4. Check Laravel documentation: https://laravel.com/docs

---

## Code Style

Follow Laravel conventions:
- PSR-12 coding standard
- StudlyCaps for class names
- camelCase for method names
- snake_case for database columns
- Use type hints for parameters and return types
- Use strict types: `declare(strict_types=1);` (optional)
