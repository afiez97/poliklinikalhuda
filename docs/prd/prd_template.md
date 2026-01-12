# PRD: [Feature Name]

**PRD ID:** PRYYYY-NN-feature-name  
**Created:** [Date]  
**Author:** [Name]  
**Last Updated:** [Date]

### Format Breakdown

- **`<KOD_MODULE-MODULE_NAME>`** - SPSM module code and name (e.g., `SPSM01-TadbirUrus`, `SPSM13-Penggajian`)
- **`<SUBMODULE_NAME>`** - Sub-module name if applicable (optional, use if nested)
- **`PR`** - Fixed prefix for "Product Requirement"
- **`YYYY`** - Four-digit year (e.g., 2026)
- **`NN`** - Two-digit sequential number starting from 01 for each module-submodule combination per year
- **`feature-name`** - Descriptive kebab-case name
---

## 1. Overview

### 1.1 Feature Summary
[Provide a brief 2-3 sentence summary of what this feature does and why it's being built]

### 1.2 Metadata
- **Feature Name:** [Full descriptive name]
- **Module/SubModule:** [e.g., Admin/UserManagement/SuperAdmin/UserList]
- **Target Roles:** [e.g., SuperAdmin, Admin, User]
- **Priority:** [High / Medium / Low]
- **Status:** [Planning / In Progress / Review / Complete]
- **Estimated Effort:** [Small / Medium / Large]

---

## 2. Problem Statement

### 2.1 Current Situation
[Describe the current state and what pain points or limitations exist]

### 2.2 Desired Outcome
[Describe the ideal state after this feature is implemented]

### 2.3 Success Metrics
[How will we measure success? User adoption, time saved, error reduction, etc.]

---

## 3. User Stories

### 3.1 Primary User Stories
- **As a (Sebagai)** [role], **I want to (saya mahu)** [action] **so that (oleh yang demikian)** [benefit]
- **As a (Sebagai)** [role], **I want to (saya mahu)** [action] **so that (oleh yang demikian)** [benefit]

### 3.2 Edge Cases & Secondary Stories
- **As a (Sebagai)** [role], **when (bila)** [condition], **I should (saya sepatutnya)** [expected behavior]

---

## 4. Functional Requirements

### 4.1 Core Features
- [ ] **FR-1:** [Requirement description]
- [ ] **FR-2:** [Requirement description]
- [ ] **FR-3:** [Requirement description]

### 4.2 Permissions & Access Control
- **Required Roles:** [List roles that can access this feature]
- **Required Permissions:** [List specific permissions needed, e.g., 'users.create', 'users.edit']
- **Policy/Gate Logic:** [Describe any complex authorization rules]

### 4.3 Badge System Integration
- [ ] **Does this feature need notification badges?** [Yes/No]
- **Badge Count Logic:** [e.g., "Count of pending approvals for current user"]
- **Badge Update Trigger:** [When should badge count refresh? e.g., on create, update, delete]
- **Badge Display Location:** [Where should badge appear? Module icon, SubModule icon, both]

### 4.4 Data Validation
- **Required Fields:** [List required form fields]
- **Validation Rules:** [Describe validation logic, e.g., unique email, min/max length]
- **Business Rules:** [Any special business logic, e.g., "Cannot delete user with active sessions"]

---

## 5. Technical Specifications

### 5.1 Architecture

#### Module Structure
```
ModuleName/
  └── SubModuleName/
      └── RoleName/
          └── ComponentName/
              ├── ComponentName.php (Livewire)
              ├── component-name.blade.php
              └── ComponentNameTest.php
```

- **Module Path:** [e.g., Admin/UserManagement/SuperAdmin/UserList]
- **Livewire Component Type:** [Class-based / Functional Volt]
- **Component Name:** [e.g., UserList, CreateUser]

#### Command to Generate
```bash
php artisan make:controller-core [ModuleName]/[SubModuleName]/[RoleName]/[ComponentName]
```

### 5.2 Database Schema

#### New Tables
- [ ] **Table Name:** `table_name`
  - **Columns:**
    - `id` - string(26)
    - `name` - string(255)
    - `created_at` - timestamp
    - `updated_at` - timestamp
  - **Indexes:** [List indexes]
  - **Foreign Keys:** [List relationships]

#### Existing Table Modifications
- [ ] **Table:** `existing_table`
  - **Changes:** [Add/modify/remove columns]

### 5.3 Eloquent Models

#### New Models
- [ ] **Model:** `ModelName`
  - **Relationships:**
    - `belongsTo()` - [Related model]
    - `hasMany()` - [Related model]
  - **Casts:** [e.g., 'is_active' => 'boolean']
  - **Fillable:** [List fillable attributes]
  - **Factory:** [Yes/No]
  - **Seeder:** [Yes/No]

### 5.4 Routes
- [ ] `Route::get('/path', ComponentName::class)->name('route.name');`
- [ ] `Route::post('/path', [Controller::class, 'method'])->name('route.name');`

**Route Middleware:** [auth, permission:permission-name]

### 5.5 UI Components

#### Layout
- **Page Type:** [Full page / Modal / Slide-over / Inline section]
- **Navigation:** [Add to menu? Yes/No - specify menu location]

#### Flux UI Components
List specific Flux UI Pro components to use:
- [ ] `<flux:button variant="primary">` - [Purpose]
- [ ] `<flux:input wire:model="form.name">` - [Purpose]
- [ ] `<flux:table>` - [Purpose]
- [ ] `<flux:modal>` - [Purpose]
- [ ] [Other components...]

#### Icons
- **Module Icon:** [e.g., heroicon-o-users]
- **SubModule Icon:** [e.g., heroicon-o-user-group]

#### Tailwind CSS
- **Custom Styles:** [Any custom Tailwind classes or theme extensions needed]
- **Dark Mode Support:** [Yes/No - specify dark mode classes]

### 5.6 Form Requests & Validation
- [ ] **Form Request:** `StoreUserRequest`
  - **Validation Rules:**
    ```php
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    ```
  - **Custom Error Messages:** [List any custom messages]

---

## 6. Business Logic & Workflows

### 6.1 Main User Flow
1. User navigates to [location]
2. User clicks [action]
3. System validates [conditions]
4. System performs [operations]
5. User sees [feedback/result]

### 6.2 State Management
- **Livewire Properties:** [List public properties, e.g., `public $search = ''`]
- **Computed Properties:** [List computed properties, e.g., `$users = computed(fn() => ...)`]

### 6.3 Event Handling
- **Events Dispatched:** [e.g., `$this->dispatch('user-created')`]
- **Events Listened:** [e.g., `#[On('refresh-list')]`]

### 6.4 Background Jobs (if applicable)
- [ ] **Job Name:** `ProcessUserImport`
  - **Trigger:** [When is job queued?]
  - **Queue:** [Queue name, e.g., 'default', 'emails']
  - **Failure Handling:** [Retry logic, notifications]

---

## 7. Testing Requirements

### 7.1 Feature Tests
Create tests in `tests/Feature/[ModuleName]/[ComponentName]Test.php`:

- [ ] **Test:** User can view [feature]
  ```php
  test('authorized user can view component', function () {
      $user = User::factory()->create();
      $user->givePermissionTo('permission.name');
      
      $this->actingAs($user)
          ->get(route('route.name'))
          ->assertOk()
          ->assertSeeLivewire(ComponentName::class);
  });
  ```

- [ ] **Test:** User can create [resource]
- [ ] **Test:** User can update [resource]
- [ ] **Test:** User can delete [resource]
- [ ] **Test:** Unauthorized user cannot access [feature]
- [ ] **Test:** Validation rules are enforced
- [ ] **Test:** Badge count updates correctly (if applicable)

### 7.2 Unit Tests
Create tests in `tests/Unit/[Feature]Test.php`:

- [ ] **Test:** Model relationships work correctly
- [ ] **Test:** Business logic methods return expected results
- [ ] **Test:** Helper functions behave as expected

### 7.3 Test Data
- **Factories:** [List factories needed]
- **Seeders:** [List seeders needed for testing]

### 7.4 Test Coverage Goals
- [ ] All happy paths tested
- [ ] All validation rules tested
- [ ] All authorization checks tested
- [ ] Edge cases covered

---

## 8. Implementation Steps

### 8.1 Pre-Implementation Checklist
- [ ] PRD reviewed and approved
- [ ] Design mockups ready (if UI-heavy feature)
- [ ] Dependencies identified and available
- [ ] Database schema reviewed

### 8.2 Implementation Order
1. **Database Setup**
   ```bash
   php artisan make:migration create_table_name_table
   php artisan make:model ModelName -mf
   ```

2. **Generate Component Structure**
   ```bash
   php artisan make:controller-core [ModuleName]/[SubModuleName]/[RoleName]/[ComponentName]
   ```

3. **Create Form Requests**
   ```bash
   php artisan make:request StoreResourceRequest
   php artisan make:request UpdateResourceRequest
   ```

4. **Implement Business Logic**
   - Model relationships and methods
   - Livewire component logic
   - Form validation
   - Authorization (policies/gates)

5. **Build UI**
   - Blade templates with Flux UI components
   - Tailwind styling with dark mode support
   - Icons and navigation integration

6. **Badge Integration (if applicable)**
   - Implement badge count logic in BadgeService
   - Update menu definitions in MenuService
   - Test badge updates

7. **Write Tests**
   - Feature tests for all user flows
   - Unit tests for business logic
   - Run tests: `php artisan test --filter=ComponentName`

8. **Code Formatting**
   ```bash
   vendor/bin/pint --dirty
   ```

9. **Final Review**
   - All tests passing
   - Code formatted
   - Documentation updated
   - PRD acceptance criteria met

---

## 9. Dependencies

### 9.1 External Packages
- [ ] **Package Name:** [e.g., spatie/laravel-permission]
  - **Version:** [Specify version if critical]
  - **Purpose:** [Why is this needed?]

### 9.2 Related Features/Modules
- **Depends On:** [List features that must exist first]
- **Impacts:** [List features that will be affected by this change]

### 9.3 Third-Party Integrations
- [ ] **Service:** [e.g., Email service, Payment gateway]
  - **Configuration Required:** [Environment variables, API keys]

---

## 10. Acceptance Criteria

### 10.1 Functional Acceptance
- [ ] All functional requirements (FR-1, FR-2, etc.) are implemented
- [ ] All user stories can be completed successfully
- [ ] Permissions and authorization work as specified
- [ ] Badge system integrated correctly (if applicable)
- [ ] Data validation enforces all rules
- [ ] Error handling provides clear feedback

### 10.2 Technical Acceptance
- [ ] All feature tests pass
- [ ] All unit tests pass
- [ ] Code follows Laravel conventions from copilot-instructions.md
- [ ] Code formatted with `vendor/bin/pint`
- [ ] No N+1 query problems (eager loading used)
- [ ] Livewire component has single root element
- [ ] Dark mode supported (if UI feature)

### 10.3 Quality Acceptance
- [ ] Code reviewed by peer
- [ ] Manual testing completed
- [ ] No console errors or warnings
- [ ] Responsive design works on mobile/tablet
- [ ] Accessibility considerations addressed

### 10.4 Documentation Acceptance
- [ ] PRD updated with final implementation notes
- [ ] BADGES.md updated (if badge added)
- [ ] README.md updated (if user-facing feature)
- [ ] Inline code comments for complex logic

---

## 11. Notes & Considerations

### 11.1 Future Enhancements
[List potential improvements or features to add later]

### 11.2 Known Limitations
[Document any known constraints or trade-offs]

### 11.3 Migration Considerations
[If modifying existing feature, how will existing data/users be handled?]

### 11.4 Performance Considerations
[Any caching, queueing, or optimization strategies needed?]

### 11.5 Security Considerations
[Any security concerns? XSS, CSRF, SQL injection prevention?]

---

## 12. Appendix

### 12.1 References
- [Link to related PRDs]
- [Link to design mockups]
- [Link to API documentation]

### 12.2 Change Log
| Date | Author | Change |
|------|--------|--------|
| [Date] | [Name] | Initial PRD creation |
| [Date] | [Name] | [Description of change] |

### 12.3 Approval
- [ ] **Product Owner:** [Name] - [Date]
- [ ] **Tech Lead:** [Name] - [Date]
- [ ] **Stakeholders:** [Names] - [Date]

---

**Implementation Status:** [Not Started / In Progress / Completed]  
**Completion Date:** [Date when feature shipped to production]
