---
applyTo: "**/*.{php,html,js,css}"
---

## 🎯 PRIME DIRECTIVE

- **Avoid working on more than one file at a time**
- Multiple simultaneous edits to a file will cause corruption
- Be chatting and teach about what you are doing while coding

## 📋 LARGE FILE & COMPLEX CHANGE PROTOCOL

### 📝 MANDATORY PLANNING PHASE

When working with large files (>300 lines) or complex changes:

1. **ALWAYS start by creating a detailed plan BEFORE making any edits**
2. **Your plan MUST include:**

   - All functions/sections that need modification
   - The order in which changes should be applied
   - Dependencies between changes
   - Estimated number of separate edits required

3. **Format your plan as:**

#### 📊 PROPOSED EDIT PLAN

```
Working with: [filename]
Total planned edits: [number]

Edit sequence:
1. [First specific change] - Purpose: [why]
2. [Second specific change] - Purpose: [why]
3. [Continue...]

Do you approve this plan? I'll proceed with Edit [number] after your confirmation.
```

4. **WAIT for explicit user confirmation before making ANY edits**

### ✏️ MAKING EDITS

- Focus on one conceptual change at a time
- Show clear "before" and "after" snippets when proposing changes
- Include concise explanations of what changed and why
- Always check if the edit maintains the project's coding style

### ⚡ EXECUTION PHASE

- After each individual edit, clearly indicate progress:
  - `"✅ Completed edit [#] of [total]. Ready for next edit?"`
- If you discover additional needed changes during editing:
  - **STOP** and update the plan
  - Get approval before continuing

### 🔧 REFACTORING GUIDANCE

When refactoring large files:

- Break work into logical, independently functional chunks
- Ensure each intermediate state maintains functionality
- Consider temporary duplication as a valid interim step
- Always indicate the refactoring pattern being applied

### ⏱️ RATE LIMIT AVOIDANCE

- For very large files, suggest splitting changes across multiple sessions
- Prioritize changes that are logically complete units
- Always provide clear stopping points

---

## 🎯 GENERAL REQUIREMENTS

Use modern technologies as described below for all code suggestions. Prioritize clean, maintainable code with appropriate comments.

### ♿ ACCESSIBILITY STANDARDS

- **Ensure compliance with WCAG 2.1 AA level minimum, AAA whenever feasible**
- **Always include:**
  - Labels for form fields
  - Proper **ARIA** roles and attributes
  - Adequate color contrast
  - Alternative texts (`alt`, `aria-label`) for media elements
  - Semantic HTML for clear structure
  - Tools like **Lighthouse** for audits
  - Add **clear comments** for functions and complex parts

### 🌐 BROWSER COMPATIBILITY

- Prioritize feature detection (`if ('fetch' in window)` etc.)
- **Support latest two stable releases of major browsers:**
  - Firefox
  - Chrome
  - Edge
  - Safari (macOS/iOS)

---

## 🐘 PHP REQUIREMENTS

### 📌 Target Version: **PHP 8.1 or higher**

### ✨ Features to Use

- Named arguments
- Constructor property promotion
- Union types and nullable types
- Match expressions
- Nullsafe operator (`?->`)
- Attributes instead of annotations
- Typed properties with appropriate type declarations
- Return type declarations
- Enumerations (`enum`)
- Readonly properties
- Use **snake_case** in file names
- **Emphasize strict property typing in all generated code**

### 📏 Coding Standards

- Follow **PSR-12** coding standards
- Use strict typing with `declare(strict_types=1);`
- Prefer composition over inheritance
- Use dependency injection

### 🔍 Static Analysis

- Include PHPDoc blocks compatible with **PHPStan** or **Psalm** for static analysis

### ⚠️ Error Handling

- Use exceptions consistently for error handling and avoid suppressing errors
- Provide meaningful, clear exception messages and proper exception types

---

## 🌐 HTML/CSS REQUIREMENTS

### 📄 HTML Standards

- Use **HTML5 semantic elements** (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`, `<search>`, etc.)
- Include appropriate **ARIA attributes** for accessibility
- Ensure valid markup that passes **W3C validation**
- Use **responsive design** practices
- Optimize images using modern formats (**WebP**, **AVIF**)
- Include `loading="lazy"` on images where applicable
- Generate `srcset` and `sizes` attributes for responsive images when relevant
- Prioritize **SEO-friendly elements** (`<title>`, `<meta description>`, Open Graph tags)

### 🎨 CSS Standards

**Use modern CSS features including:**

- CSS Grid and Flexbox for layouts
- CSS Custom Properties (variables)
- CSS animations and transitions
- Media queries for responsive design
- Logical properties (`margin-inline`, `padding-block`, etc.)
- Modern selectors (`:is()`, `:where()`, `:has()`)
- Follow **BEM** or similar methodology for class naming
- Use CSS nesting where appropriate
- Prioritize modern, performant fonts and variable fonts for smaller file sizes
- Use modern units (`rem`, `vh`, `vw`) instead of traditional pixels (`px`) for better responsiveness

#### 🅱️ Bootstrap (v5) Guidelines

- Wrap layouts in `container` → `row` → `col-*`
- Use spacing classes like `mb-3`, `p-2`; avoid inline styles
- Use proper structure for modals, navbars, alerts with ARIA

---

## ⚡ JAVASCRIPT REQUIREMENTS

### 📌 Target Version: **ECMAScript 2020 (ES11) or higher**

### ✨ Features to Use

- Arrow functions
- Template literals
- Destructuring assignment
- Spread/rest operators
- Async/await for asynchronous code
- Classes with proper inheritance when OOP is needed
- Object shorthand notation
- Optional chaining (`?.`)
- Nullish coalescing (`??`)
- Dynamic imports
- BigInt for large integers
- `Promise.allSettled()`
- `String.prototype.matchAll()`
- `globalThis` object
- Private class fields and methods
- Export * as namespace syntax
- Array methods (`map`, `filter`, `reduce`, `flatMap`, etc.)
- Always use __DIR__ in PHP when writing include or require paths to make them absolute.

### ❌ Features to Avoid

- `var` keyword (use `const` and `let`)
- jQuery or any external libraries
- Callback-based asynchronous patterns when promises can be used
- Internet Explorer compatibility
- Legacy module formats (use ES modules)
- Limit use of `eval()` due to security risks

### ⚡ Performance Considerations

- Recommend code splitting and dynamic imports for lazy loading

### ⚠️ Error Handling

- Use `try-catch` blocks **consistently** for asynchronous and API calls, and handle promise rejections explicitly
    - Differentiate among:
    - **Network errors** (e.g., timeouts, server errors, rate-limiting)
    - **Functional/business logic errors** (logical missteps, invalid user input, validation failures)
    - **Runtime exceptions** (unexpected errors such as null references)
    - Provide **user-friendly** error messages (e.g., “Something went wrong. Please try again shortly.”) and log more technical details to dev/ops (e.g., via a logging service).
    - Consider a central error handler function or global event (e.g., `window.addEventListener('unhandledrejection')`) to consolidate reporting.
    - Carefully handle and validate JSON responses, incorrect HTTP status codes, etc.

---

## 🗄️ DATABASE REQUIREMENTS

### 🐬 MySQL Guidelines

#### 🔒 Security Best Practices

- Use `?` placeholders or ORM-provided escaping for queries
- **Never** concatenate user input directly into SQL strings
- Parameterize all dynamic values in queries

#### ⚡ Performance Optimization

- Index columns you query often (e.g., `WHERE`, `JOIN`, `ORDER BY`)
- Use appropriate data types for optimal storage
- Consider composite indexes for multi-column queries
- Regularly analyze and optimize slow queries

#### 📊 Schema Design

- Use appropriate constraints (NOT NULL, UNIQUE, FOREIGN KEY)
- Follow consistent naming conventions (snake_case recommended)
- Document relationships and business rules
- Consider normalization vs. denormalization trade-offs

#### 🔧 Query Guidelines

- Use explicit column names instead of `SELECT *`
- Implement proper error handling for database operations
- Use transactions for multi-step operations
- Consider using stored procedures for complex operations

---

## 🔒 SECURITY CONSIDERATIONS

### 🛡️ Input Validation & Sanitization

- **Sanitize all user inputs** thoroughly
- Validate data types, lengths, and formats
- Use whitelist validation over blacklist when possible
- Implement server-side validation for all inputs

### 🗄️ Database Security

- **Parameterize database queries** (use prepared statements)
- Use least privilege principle for database connections

### 🌐 Web Application Security

- Enforce strong **Content Security Policies (CSP)**
- Use **CSRF protection** where applicable
- Use HTTPS for all sensitive communications

---

## 📁 PROJECT STRUCTURE GUIDELINES

### 🏗️ Directory Organization

- Follow consistent naming conventions (snake_case for php files, camelCase for JS, and kebab-case for CSS)
- Separate concerns clearly (MVC pattern recommended)
- Keep related files grouped together
- Use descriptive and self-documenting names

### 📝 Code Organization

- One class per file (PHP)
- Group related functionality into modules
- Keep functions/methods focused on single responsibilities
- Use meaningful variable and function names
