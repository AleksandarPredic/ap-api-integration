# AP API Integration Plugin - Coding Standards & Guidelines

## PHP Version Requirements

**Minimum PHP Version:** 8.3+

**Required PHP 8.3+ Features:**
- Typed properties for all class properties
- Return type declarations for all methods
- Constructor property promotion where applicable
- Null coalescing operators for default values
- Union types where needed
- Named arguments for improved readability

---

## Critical Errors to Avoid

### 1. **Type System Violations**
- Never use incorrect syntax for typed constants
- Ensure all type declarations follow PHP 8.0+ syntax
- All properties must have explicit type declarations
- All method parameters must have type hints
- All methods must have return type declarations

### 2. **Method and Property Access**
- Never call methods that don't exist on an object
- Always check instance type before calling specific methods
- Verify method availability before calling on parent/interface types

### 3. **Required Configuration Validation**
- Always validate configuration values before use
- Throw descriptive exceptions for missing required configuration
- Check all dependencies are available before proceeding with operations

### 4. **Database and Options**
- Always provide default values when retrieving options
- Validate data exists before processing
- Handle null/empty cases explicitly

---

## Code Quality Standards

### 1. **DRY Principle (Don't Repeat Yourself)**
- Never duplicate logic across multiple methods
- Extract common functionality into reusable methods
- Use traits for shared functionality across classes
- Reference existing methods instead of rewriting logic
- Create helper methods for repeated operations

### 2. **Variable and Data Handling**
- Store retrieved values in variables for reuse
- Never fetch the same data multiple times
- Cache expensive operations
- Avoid redundant null checks and operators

### 3. **Data Normalization**
- Normalize data at storage time, not retrieval time
- Store data in its final/canonical format
- Keep retrieval methods simple (return as-is from storage)
- Use sanitization callbacks to normalize during save operations

### 4. **Method Scope and Size**
- Keep methods focused on a single responsibility
- Extract complex logic into separate methods
- Methods should be short and readable
- Avoid deep nesting (max 3 levels)

### 5. **Empty Code Blocks**
- Remove empty constructors that serve no purpose
- Remove empty methods unless required by interface
- Don't include placeholder methods without functionality

### 6. **Consistent Code Style**
- Use consistent spacing and indentation (4 spaces for indentation)
- Follow PSR-12 coding standards for braces and spacing
- Keep array formatting consistent
- Group related code together

---

## Documentation Standards

### 1. **Class Documentation**
- Every class must have a PHPDoc block
- Describe the class purpose and responsibility
- Document any important class-level behaviors

### 2. **Method Documentation**
- Document all public and protected methods
- Include @param for all parameters with descriptions
- Include @return with type and description
- Include @throws for all exceptions that can be thrown
- For array returns, specify structure: array{key: type, ...}
- Use {@inheritDoc} when inheriting from interface/parent with docs

### 3. **Property Documentation**
- Document all class properties
- Include @var with type
- Add description of what the property stores/represents

### 4. **Inline Comments**
- Use comments to explain "why", not "what"
- Comment complex logic or non-obvious code
- Keep comments concise and relevant

---

## WordPress Best Practices

### 1. **WordPress Functions Over PHP**
- Use current_time('timestamp') instead of time()
- Use wp_remote_post() instead of curl or file_get_contents()
- Use WordPress database functions instead of direct SQL
- Use WordPress filesystem functions instead of PHP file functions

### 2. **Internationalization (i18n)**
- All user-facing strings must be translatable
- Use esc_html__() for translated strings in PHP
- Use esc_attr__() for translated strings in HTML attributes
- Always include text domain as second parameter
- Text domain must match plugin slug

### 3. **Security**

#### Direct File Access Prevention
- Include ABSPATH check at the top of every PHP file
- Exit if ABSPATH is not defined
- ABSPATH should go after the namespace if we have a namespace, and after the use statements if we have them. This should go right above the class declaration.

#### Capability Checks
- Always verify user permissions before sensitive operations
- Use current_user_can() to check capabilities
- Return early if user lacks permission

#### Data Sanitization
- Sanitize all input data
- Use appropriate WordPress sanitization functions:
  - sanitize_text_field() for text
  - sanitize_email() for emails
  - esc_url_raw() for URLs
  - absint() for positive integers
  - Custom functions for complex data

#### Output Escaping
- Escape all output going to browser
- Use appropriate escaping functions:
  - esc_html() for HTML content
  - esc_attr() for HTML attributes
  - esc_url() for URLs
  - wp_kses_post() for post content

### 4. **Settings and Options API**
- Use Settings API for admin settings pages
- Register all settings with proper type and sanitization
- Set autoload to false for large or rarely-used options
- Always provide default values when getting options
- Group related settings logically

### 5. **Hooks and Filters**
- Use descriptive hook names
- Specify priority when order matters (default is 10)
- Keep callback methods focused and single-purpose
- Document expected parameters and return types

---

## Exception Handling

### 1. **Exception Types**
- Use custom exception classes for domain-specific errors
- Extend base Exception class appropriately
- Include context data with exceptions

### 2. **Catching Exceptions**
- Catch specific exception types first
- Catch general exceptions last
- Re-throw specific exceptions as-is to preserve context
- Only wrap when adding additional context
- Never swallow exceptions silently

### 3. **Exception Messages**
- Provide clear, actionable error messages
- Include context information in exception data
- Localize exception messages for user-facing errors
- Log technical details separately from user messages

---

## Code Organization

### 1. **Directory Structure**
- Group related classes in subdirectories
- Use Contracts/ for interfaces
- Use Traits/ for reusable trait code
- Use feature-based directory names
- Keep flat structure when possible (avoid deep nesting)

### 2. **Naming Conventions**

#### Classes and Interfaces
- PascalCase for class names
- Descriptive names indicating purpose
- Interfaces should end with Contract
- Traits should end with Trait (optional but recommended)
- Abstract classes should start with Abstract (optional)

#### Methods
- camelCase for method names
- Start with verb (get, set, render, validate, sanitize, update, delete, etc.)
- Be descriptive and clear about purpose
- Boolean methods should start with is, has, can, should

#### Properties
- camelCase for property names
- Use descriptive names
- Avoid abbreviations unless widely understood
- Private properties can be prefixed with underscore (optional)

#### Constants
- SCREAMING_SNAKE_CASE for constants
- Use for configuration values, fixed strings, and magic numbers
- Group related constants together

#### Variables
- camelCase for variable names
- Use descriptive names
- Avoid single-letter variables except in loops

### 3. **File Organization**
- One class per file
- File name must match class name
- Include namespace declaration after opening PHP tag
- Include file access check after namespace
- Order: namespace → access check → use statements → class

### 4. **Class Member Organization**
Order members in classes as follows:
1. Constants (grouped by purpose)
2. Properties (public → protected → private)
3. Constructor
4. Public methods (by importance/logical flow)
5. Protected methods
6. Private methods
7. Static methods (at end)

---

## Trait Usage Guidelines

### 1. **Trait Purpose**
- Extract reusable functionality into traits
- Keep traits focused on specific functionality
- Traits should be self-contained
- Document what using the trait provides

### 2. **Trait Methods**
- Use protected for methods consumed by using class
- Use private for internal trait helper methods
- Provide helper methods for common operations
- Include validation methods when appropriate

### 3. **Trait Constants**
- Use private const for trait-specific constants
- Document constant purpose and usage

---

## Dependency Management

### 1. **Dependency Injection**
- Inject dependencies through constructors
- Use interfaces for type hints, not concrete classes
- Avoid static dependencies when possible
- Make dependencies explicit

### 2. **Interface Usage**
- Define interfaces for all major contracts
- Keep interfaces focused and cohesive
- Use interfaces for type hints in methods
- Document interface methods thoroughly

### 3. **Singleton Pattern**
- Use Singleton trait for plugin components
- Don't overuse singletons
- Make constructor private when using Singleton
- Provide getInstance() through trait

---

## Testing and Validation

### 1. **Input Validation**
- Validate all external input
- Check for required parameters
- Verify data types and formats
- Throw exceptions for invalid input

### 2. **Testable Code**
- Keep methods small and focused
- Inject dependencies for easier mocking
- Avoid static methods (except for utilities)
- Return values instead of echoing
- Separate logic from presentation

### 3. **Error Handling**
- Handle all error cases explicitly
- Provide meaningful error messages
- Log errors appropriately
- Return early for error conditions

---

## Performance Considerations

### 1. **Caching**
- Use transients for temporary cached data
- Cache expensive operations
- Set appropriate expiration times
- Clear cache when data changes

### 2. **Database Queries**
- Minimize database queries
- Use appropriate query methods
- Avoid queries in loops
- Use autoload => false for large options

### 3. **Asset Loading**
- Enqueue scripts and styles properly
- Only load on pages where needed
- Use minified versions in production
- Combine files when appropriate

---

## API Design

### 1. **Method Signatures**
- Keep parameter lists short (max 4-5 parameters)
- Use arrays/objects for complex parameter sets
- Provide sensible defaults
- Use named arguments for clarity when beneficial

### 2. **Return Values**
- Be consistent with return types
- Document complex return structures
- Return null vs empty arrays/strings consistently
- Avoid returning mixed types

### 3. **Public API**
- Keep public API minimal and stable
- Mark internal methods as private/protected
- Document breaking changes
- Version API changes

---

## Version Control

### 1. **Commit Messages**
- Use clear, descriptive commit messages
- Start with verb (Add, Fix, Update, Remove, etc.)
- Keep first line under 50 characters
- Add detailed description in body if needed

### 2. **Code Review Checklist**
Before committing, verify:
- [ ] No critical errors
- [ ] All code quality standards met
- [ ] Complete documentation added
- [ ] All strings localized
- [ ] Security best practices followed
- [ ] All output properly escaped
- [ ] Return types on all methods
- [ ] Type hints on all parameters
- [ ] No duplicate logic (DRY)
- [ ] PSR-12 coding standards followed
- [ ] Tests pass (if applicable)

---

## Configuration Management

### 1. **Constants**
- Use constants for configuration values
- Centralize configuration in dedicated files/classes
- Use environment variables for environment-specific config
- Never hard-code credentials or API keys

### 2. **Options**
- Group related options logically
- Use prefixes to avoid naming conflicts
- Set autoload appropriately
- Provide migration path for option changes

---

## Logging and Debugging

### 1. **Error Logging**
- Log errors appropriately for debugging
- Use WordPress debug log for development
- Don't expose sensitive information in logs
- Include context with logged errors

### 2. **Debug Mode**
- Check WP_DEBUG before debug output
- Remove debug code before production
- Use proper debug tools, not var_dump()

---

## Summary

This document provides the coding standards and guidelines for the AP API Integration plugin. Following these standards ensures:

- **Consistency:** Code is uniform and predictable
- **Maintainability:** Easy to understand and modify
- **Security:** Best practices prevent vulnerabilities
- **Performance:** Efficient and optimized code
- **Quality:** Professional-grade code that follows PSR-12 standards

When in doubt, refer to:
- [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)
- [PHP The Right Way](https://phptherightway.com/)

---

**Document Version:** 1.0

