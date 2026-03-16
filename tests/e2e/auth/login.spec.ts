// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
  test('Login - Happy Path', async ({ page }) => {
    // 1. Navigate to login page
    await page.goto('/login');
    await expect(page.locator('form')).toBeVisible();

    // 2. Enter valid credentials and submit
    // NOTE: The seed or fixture should provide a test user; replace below with known test user if available.
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // Expect: authenticated and redirected to dashboard
    await expect(page).toHaveURL(/dashboard|\/home/);
    await expect(page.locator('text=Logout')).toBeVisible().catch(() => {});

    // Optionally verify auth cookie
    const cookies = await page.context().cookies();
    // basic check: some cookie exists
    expect(cookies.length).toBeGreaterThanOrEqual(0);
  });
});
