# Product Requirements Document (PRD) System

This directory contains all Product Requirements Documents (PRDs) for features and enhancements in the SPSM application.

---

## Purpose

Every new feature, significant enhancement, or module addition **must** have a corresponding PRD document before implementation begins. This ensures:

- Clear requirements and specifications before coding starts
- Proper planning of database schema, UI components, and testing
- Alignment with project architecture (Laravel 12, Livewire 3, Volt, Flux UI Pro)
- Documentation of decisions and acceptance criteria
- Traceability for future maintenance and enhancements

---

## Naming Convention

All PRD files follow this standardized naming format that aligns with SPSM module structure:

```
<KOD_MODULE-MODULE_NAME>-<SUBMODULE_NAME>-PRYYYY-NN-feature-name.md
```

### Format Breakdown

- **`<KOD_MODULE-MODULE_NAME>`** - SPSM module code and name (e.g., `SPSM01-TadbirUrus`, `SPSM13-Penggajian`)
- **`<SUBMODULE_NAME>`** - Sub-module name if applicable (optional, use if nested)
- **`PR`** - Fixed prefix for "Product Requirement"
- **`YYYY`** - Four-digit year (e.g., 2026)
- **`NN`** - Two-digit sequential number starting from 01 for each module-submodule combination per year
- **`feature-name`** - Descriptive kebab-case name

### Examples

**For main module features:**
```
SPSM01-TadbirUrus-PR2026-01-audit-trail-system.md
SPSM11-PengurusanCuti-PR2026-01-cuti-rehat-workflow.md
SPSM13-Penggajian-PR2026-01-salary-calculator.md
SPSM15-Latihan-PR2026-01-training-approval-system.md
```

**For sub-module features (if nested structure exists):**
```
SPSM13-Penggajian-Gaji-PR2026-01-bonus-calculation.md
SPSM15-Latihan-Internal-PR2026-01-internal-course-registration.md
```

**For features without specific module (general system features):**
```
GENERAL-SystemConfig-PR2026-01-notification-system.md
GENERAL-Admin-PR2026-01-user-management-enhancement.md
```

### SPSM Module Reference

| Kod | Modul | Example PRD Prefix |
|-----|-------|-------------------|
| **SPSM-01** | Tadbir Urus | `SPSM01-TadbirUrus-` |
| **SPSM-02** | Perancangan Sumber Manusia | `SPSM02-PerancanganSM-` |
| **SPSM-03** | Pengambilan | `SPSM03-Pengambilan-` |
| **SPSM-04** | Pelantikan | `SPSM04-Pelantikan-` |
| **SPSM-05** | Pertukaran & Penempatan | `SPSM05-Pertukaran-` |
| **SPSM-06** | Kemudahan Staf | `SPSM06-KemudahanStaf-` |
| **SPSM-07** | Kebajikan Staf | `SPSM07-KebajikanStaf-` |
| **SPSM-08** | Perubatan | `SPSM08-Perubatan-` |
| **SPSM-09** | Integriti | `SPSM09-Integriti-` |
| **SPSM-10** | Pengurusan Waktu Bekerja | `SPSM10-WaktuBekerja-` |
| **SPSM-11** | Pengurusan Cuti | `SPSM11-PengurusanCuti-` |
| **SPSM-12** | Prestasi | `SPSM12-Prestasi-` |
| **SPSM-13** | Penggajian | `SPSM13-Penggajian-` |
| **SPSM-14** | Personel | `SPSM14-Personel-` |
| **SPSM-15** | Latihan | `SPSM15-Latihan-` |
| **SPSM-16** | Kompetensi | `SPSM16-Kompetensi-` |
| **SPSM-17** | Pelan Penggantian | `SPSM17-PelanPenggantian-` |
| **SPSM-18** | Pengesahan Perkhidmatan | `SPSM18-PengesahanPerkhidmatan-` |
| **SPSM-19** | Kemudahan Cuti Belajar | `SPSM19-CutiBelajar-` |
| **SPSM-20** | Peningkatan Kerjaya | `SPSM20-PeningkatanKerjaya-` |
| **SPSM-21** | Peperiksaan | `SPSM21-Peperiksaan-` |
| **SPSM-22** | Rekod Kenyataan Perkhidmatan (RKP) | `SPSM22-RKP-` |
| **SPSM-23** | Meninggalkan Perkhidmatan | `SPSM23-MeninggalkanPerkhidmatan-` |

### Sequence Numbering

- Numbers **reset to 01** at the start of each year **for each module**
- Numbers are **module-specific** (e.g., SPSM01-TadbirUrus-PR2026-01 and SPSM13-Penggajian-PR2026-01 can both exist)
- Numbers are **auto-incremented** by scanning existing PRDs for the specific module
- If a module has no PRDs for the current year yet, start with 01
- Find the highest number for the specific module-year combination and increment by 1

### Rationale for Module-Based Naming

1. **Traceability** - PRDs are immediately identifiable by SPSM module
2. **Organization** - Features grouped by business domain (Tadbir Urus, Penggajian, etc.)
3. **Scalability** - Multiple teams can work on different modules without numbering conflicts
4. **Compliance** - Aligns with SPSM module structure for audit and reporting
5. **Search** - Easy to find all PRDs related to a specific module

---

## Creating a New PRD

### Automated Process (Recommended)

When you request a new feature from GitHub Copilot, it will:

1. Automatically scan the `docs/prd/` directory
2. Identify which SPSM module the feature belongs to
3. Find the highest sequence number for that module and current year
4. Offer to create a PRD using the next available number
5. Use the template from `prd_template.md`
6. Fill in relevant sections based on your feature request
7. Save the PRD with the correct module-based naming format

**Example dialogue:**
```
You: "I need to add salary slip generation for Penggajian module"

Copilot: "I'll create a PRD first. This feature belongs to SPSM-13 Penggajian."
         "Scanning existing PRDs for SPSM13-Penggajian..."
         "Next available: SPSM13-Penggajian-PR2026-02-salary-slip-generator.md"
         "Creating PRD document..."
```

### Manual Process

If creating a PRD manually:

1. Identify which SPSM module your feature belongs to (e.g., SPSM-11 for leave management)
2. Check existing PRD files in this directory for that module
3. Find the highest number for the module-year combination (e.g., `SPSM11-PengurusanCuti-PR2026-03-...` → next is `04`)
4. Copy the template from `prd_template.md`
5. Name your file: `SPSM11-PengurusanCuti-PR2026-04-your-feature-name.md`
6. Fill in all sections of the template
7. Commit the PRD before starting implementation

---

## PRD Template

The comprehensive PRD template is located at:

```
docs/prd/prd_template.md
```

### Template Sections

The template includes 12 major sections tailored to this Laravel/Livewire project:

1. **Overview** - Feature summary, metadata, status
2. **Problem Statement** - Current situation, desired outcome, success metrics
3. **User Stories** - Role-based user stories and edge cases
4. **Functional Requirements** - Core features, permissions, badge integration
5. **Technical Specifications** - Module structure, database, models, routes, UI components
6. **Business Logic & Workflows** - User flows, state management, events
7. **Testing Requirements** - Feature tests, unit tests, coverage goals
8. **Implementation Steps** - Pre-implementation checklist, step-by-step guide
9. **Dependencies** - External packages, related features, integrations
10. **Acceptance Criteria** - Functional, technical, quality, and documentation criteria
11. **Notes & Considerations** - Future enhancements, limitations, security
12. **Appendix** - References, change log, approvals

---

## Workflow

### 1. Feature Request
User requests a new feature or enhancement.

### 2. PRD Creation
- Copilot offers to create PRD using the template
- PRD is saved with proper naming convention
- PRD is committed to repository

### 3. PRD Review & Approval
- Team reviews PRD for completeness
- Stakeholders approve requirements
- Technical lead validates architecture decisions
- PRD status updated to "Approved"

### 4. Implementation
- Only **after** PRD approval should implementation begin
- Follow implementation steps from PRD section 8
- Reference PRD for requirements, specs, and acceptance criteria

### 5. Testing & Validation
- Write tests as specified in PRD section 7
- Validate against acceptance criteria in PRD section 10
- Update PRD with any deviations or changes during implementation

### 6. Completion
- Mark PRD status as "Complete"
- Update change log with completion date
- Update related documentation (BADGES.md, README.md if needed)

---

## PRD Status Values

PRDs use these standardized status values:

- **Planning** - Initial draft, requirements gathering
- **In Progress** - Feature is being implemented
- **Review** - Implementation complete, awaiting review
- **Complete** - Feature shipped to production

---

## Best Practices

### Do's ✅
**Identify the correct SPSM module** before naming the PRD
- Fill in all relevant sections thoroughly
- Update PRD during implementation if requirements change
- Reference PRD ID in commit messages and pull requests (e.g., "SPSM13-Penggajian-PR2026-01")
- Keep PRD up-to-date as source of truth
- Use module-specific numbering to avoid conflicts

### Don'ts ❌

- Don't skip PRD creation for "small" features - they often grow
- Don't implement features without approved PRD
- Don't use generic numbering - always include module prefix
- Don't create PRDs with arbitrary numbering - use auto-increment per module
- Don't use spaces or underscores in filename - use kebab-case and hyphens
- Don't leave sections blank - write "N/A" if truly not applicable
- Don't forget to update PRD status as work progresses
- Don't mix module codes - keep PRDs organized by SPSM moduleo-increment
- Don't use spaces or underscores in filename - use kebab-case
- Don't leave sections blank - write "N/A" if truly not applicable
- Don't forget to update PRD status as work progresses

---

## Project-Specific Considerations

This Laravel/Livewire project has unique architectural patterns that PRDs must address:

### Module Structure
```
ModuleName/SubModuleName/RoleName/ComponentName/
```
PRDs must specify the full module path for proper scaffolding.

### Component Generation
```bash
php artisan make:controller-core [ModuleName] [SubModuleName] [RoleName] [ComponentName]
```
PRDs should include the exact command to generate components.

### Livewire Component Type
PRDs must specify whether to use:
- **Class-based Volt** components
- **Functional Volt** components

Check existing components to maintain consistency.

### Badge System Integration
PRDs must consider if feature needs badge notifications:
- Badge count logic
- Badge update triggers
- Badge display location

### Flux UI Pro Components
PRDs should specify which Flux UI components to use:
- Leverage Pro components when available
- Follow existing UI patterns
- Ensure dark mode support

### Testing Requirements
All PRDs must include:
- Feature tests for all user flows
- Unit tests for business logic
- Test coverage for authorization and validation

### Code Formatting
All implementations must run:
```bash
vendor/bin/pint --dirty
```
This is part of acceptance criteria.

---

## Directory Structure                      # This file
├── prd_template.md                                              # Template for new PRDs
│
├── SPSM01-TadbirUrus-PR2026-01-audit-trail.md                  # Tadbir Urus module
├── SPSM01-TadbirUrus-PR2026-02-compliance-checker.md
│
├── SPSM11-PengurusanCuti-PR2026-01-cuti-rehat-workflow.md      # Pengurusan Cuti module
├── SPSM11-PengurusanCuti-PR2026-02-cuti-emergency-approval.md
│
├── SPSM13-Penggajian-PR2026-01-salary-calculator.md            # Penggajian module
├── SPSM13-Penggajian-PR2026-02-bonus-computation.md
│
├── SPSM15-Latihan-PR2026-01-training-registration.md           # Latihan module
│`<MODULE>-<SUBMODULE>-PRYYYY-NN-feature-name.md` format
- **Module identification** - Refer to SPSM Module Reference table above
- **Template sections** - See prd_template.md for detailed guidance
- **Implementation workflow** - Follow the 6-step workflow above

For issues or improvements to the PRD system itself, update this README and the template accordingly.

---

**Last Updated:** January 4, 2026  
**Version:** 2.0 (Updated for SPSM module-based naming)n be indicated in the second segment if needed prd_template.md                        # Template for new PRDs
├── PR2026-01-feature-name.md              # Example PRD
├── PR2026-02-another-feature.md           # Example PRD
└── ...                                    # More PRDs as numbered
```

---

## Questions & Support

If you have questions about:
- **PRD creation process** - Refer to this README and the template
- **Naming conventions** - Follow PRYYYY-NN-feature-name.md format
- **Template sections** - See prd_template.md for detailed guidance
- **Implementation workflow** - Follow the 6-step workflow above

For issues or improvements to the PRD system itself, update this README and the template accordingly.

---

**Last Updated:** January 3, 2026  
**Version:** 1.0
