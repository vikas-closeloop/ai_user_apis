# E2E Playwright Test Plan

## Application Overview

Comprehensive end-to-end Playwright test plan for the Laravel app covering authentication, blog CRUD, APIs, profile flows, and UI/validation/error scenarios. Assumes fresh DB state per test (seed.spec.ts used to prepare), independent tests, and running on local dev server.

## Test Scenarios

### 1. Authentication

**Seed:** `tests/e2e/seed.spec.ts`

#### 1.1. Signup - Happy Path

**File:** `tests/e2e/auth/signup.spec.ts`

**Steps:**
  1. Navigate to signup page
    - expect: Signup form is displayed
  2. Fill valid name, email, password, confirm password; submit
    - expect: User is created in DB
    - expect: User is redirected to dashboard
    - expect: Success message shown
    - expect: User session exists
  3. Attempt to visit protected page
    - expect: User can access protected page

#### 1.2. Login - Happy Path

**File:** `tests/e2e/auth/login.spec.ts`

**Steps:**
  1. Navigate to login page
    - expect: Login form is displayed
  2. Enter valid credentials and submit
    - expect: User is authenticated and redirected to dashboard
    - expect: Auth cookie/session set

#### 1.3. Logout

**File:** `tests/e2e/auth/logout.spec.ts`

**Steps:**
  1. Authenticate via UI or API
    - expect: User is logged in
  2. Click logout
    - expect: User session cleared
    - expect: Redirect to homepage or login
    - expect: Protected pages return 302 to login

#### 1.4. Password Reset Flow

**File:** `tests/e2e/auth/password-reset.spec.ts`

**Steps:**
  1. Navigate to forgot password, request reset for existing email
    - expect: Reset email sent (or simulated)
    - expect: Reset token exists in DB
  2. Open reset link, submit new password
    - expect: Password updated
    - expect: User can login with new password

#### 1.5. Email Verification

**File:** `tests/e2e/auth/email-verification.spec.ts`

**Steps:**
  1. Register new user requiring verification
    - expect: User is created and marked unverified
  2. Send/trigger verification email, follow link
    - expect: User marked verified
    - expect: Access to verified-only areas allowed

### 2. Blog CRUD

**Seed:** `tests/e2e/seed.spec.ts`

#### 2.1. Create Blog - Happy Path

**File:** `tests/e2e/blog-create.spec.ts`

**Steps:**
  1. Authenticate as author
    - expect: User authenticated
  2. Navigate to create blog form; fill title, content, tags; submit
    - expect: New blog record present in DB
    - expect: Redirect to blog detail page
    - expect: Success message shown

#### 2.2. Update Blog - Happy Path

**File:** `tests/e2e/blog-update.spec.ts`

**Steps:**
  1. Ensure a blog exists owned by user
    - expect: Editable blog present
  2. Navigate to edit form, change fields, submit
    - expect: Changes persisted in DB
    - expect: Updated content visible on detail page

#### 2.3. Delete Blog - Happy Path

**File:** `tests/e2e/blog-delete.spec.ts`

**Steps:**
  1. Ensure a blog exists
    - expect: Blog record present
  2. Initiate delete and confirm
    - expect: Blog removed from DB
    - expect: User redirected to list with success message

#### 2.4. Create Blog - Validation Errors

**File:** `tests/e2e/blog-create-validation.spec.ts`

**Steps:**
  1. Open create form and submit with missing required fields
    - expect: Validation messages shown for required fields
    - expect: No blog created in DB

#### 2.5. Unauthorized Blog Actions

**File:** `tests/e2e/blog-unauthorized.spec.ts`

**Steps:**
  1. Without authentication, attempt to access create/edit/delete URLs
    - expect: Redirected to login or receive 403 depending on route protection

### 3. API Endpoints

**Seed:** `tests/e2e/seed.spec.ts`

#### 3.1. GET /api/blogs - List

**File:** `tests/e2e/api/blogs-list.spec.ts`

**Steps:**
  1. Call API endpoint for blogs (unauthenticated)
    - expect: 200 OK response
    - expect: Response contains list of blogs with expected fields

#### 3.2. POST /api/blogs - Create (auth)

**File:** `tests/e2e/api/blogs-create.spec.ts`

**Steps:**
  1. Authenticate via API, POST new blog payload
    - expect: 201 Created response
    - expect: Response body matches persisted record
    - expect: Record exists in DB and attributed to user

#### 3.3. API Auth Enforcement

**File:** `tests/e2e/api/auth-enforcement.spec.ts`

**Steps:**
  1. Attempt protected API actions unauthenticated
    - expect: 401 or 403 responses as per API policy
    - expect: No DB changes made

### 4. Profile & User Management

**Seed:** `tests/e2e/seed.spec.ts`

#### 4.1. Profile Update

**File:** `tests/e2e/profile-update.spec.ts`

**Steps:**
  1. Authenticate user and navigate to profile settings
    - expect: Profile form displayed with current values
  2. Change name, bio, avatar (if supported) and save
    - expect: Changes persisted in DB
    - expect: UI shows updated profile info

#### 4.2. User Management - Admin Actions

**File:** `tests/e2e/admin/user-management.spec.ts`

**Steps:**
  1. Authenticate as admin
    - expect: Admin session active
  2. Visit user management page, suspend/reactivate a user
    - expect: User status changed in DB
    - expect: Appropriate notifications/messages shown

### 5. UI/UX, Validation, and Edge Cases

**Seed:** `tests/e2e/seed.spec.ts`

#### 5.1. Form Accessibility & Keyboard Navigation

**File:** `tests/e2e/ui/accessibility.spec.ts`

**Steps:**
  1. Open forms and navigate using keyboard only (Tab/Enter)
    - expect: All interactive elements reachable and operable by keyboard
    - expect: ARIA attributes present for form errors where applicable

#### 5.2. Responsive Layout - Key Pages

**File:** `tests/e2e/ui/responsive.spec.ts`

**Steps:**
  1. Load homepage, blog list, blog detail at mobile/tablet/desktop widths
    - expect: Layout adapts without overflow or hidden content
    - expect: Critical actions remain reachable

#### 5.3. Error Handling - 500 and 404 Pages

**File:** `tests/e2e/ui/errors.spec.ts`

**Steps:**
  1. Request a missing route
    - expect: 404 page shown with navigation back to safety
  2. Simulate server error on action (where feasible)
    - expect: 500 error page or graceful fallback shown
    - expect: No sensitive stack traces visible to user

#### 5.4. Data Boundary Tests

**File:** `tests/e2e/ui/data-boundary.spec.ts`

**Steps:**
  1. Submit extremely long title/content and invalid characters
    - expect: App enforces max lengths or sanitizes input
    - expect: No DB corruption or XSS in rendered output
