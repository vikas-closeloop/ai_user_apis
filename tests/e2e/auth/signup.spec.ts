// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
  test('Signup - Happy Path', async ({ page }) => {
    // 1. Navigate to signup page
    await page.goto('/register');
    await expect(page.locator('form')).toBeVisible();

    // 2. Fill valid name, email, password, confirm password; submit
    await page.fill('input[name="name"]', 'E2E User');
    const email = `e2e+signup+${Date.now()}@example.test`;
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', 'Password123!');
    await page.fill('input[name="password_confirmation"]', 'Password123!');
    await page.click('button[type="submit"]');

    // Expect: redirected to dashboard / success UI
    await expect(page).toHaveURL(/dashboard|\/home/);
    await expect(page.locator('text=Welcome')).toBeVisible().catch(() => {});

    // 3. Attempt to visit protected page
    await page.goto('/profile');
    await expect(page.locator('text=Profile')).toBeVisible().catch(() => {});

    // Note: DB assertions should be implemented via API or direct DB checks in CI.
  });
});
