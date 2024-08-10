# Changelog

All notable changes to `laravel-workflow` will be documented in this file.

## 1.0.0 - 2024-07-20

### What's Changed

* Added `when`  method for conditional execution by @Safemood in https://github.com/Safemood/laravel-workflow/pull/2

**Full Changelog**: https://github.com/Safemood/laravel-workflow/compare/0.0.4...1.0.0

## 0.0.4 - 2024-07-20

**Release Description:**

- **Refactoring and Updates:**
  
  - Removed `ShouldQueue` type hint from methods in `ActionsTrait`.
  - Updated dispatch logic for actions.
  - Renamed Artisan command `make:action` to `make:workflow-action`.
  - Refactored `DummyJob` to extend `Action`.
  - Added a job stub file and introduced `MakeJob` command.
  - Registered `MakeJob` command in `WorkflowServiceProvider`.
  
- **Testing and Improvements:**
  
  - Updated method names in `ManagesExecutionTest` to reflect new dispatch logic.
  - Refactored `Action` class to use `ActionState` enum and added corresponding tests.
  - Updated `TracksActionStates` to use `ActionState` enum.
  
- **Miscellaneous:**
  
  - Fixed code styling issues.
  - Added method for conditional execution.
  

**Full Changelog**: https://github.com/Safemood/laravel-workflow/compare/0.0.3...0.0.4

## 0.0.3 - 2024-07-17

Updated docs.

## 0.0.2 - 2024-07-15

Making the workflow dumpable for debugging.

## 0.0.1 - 2024-07-14

experimental release
