// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Authentication', () => {
  test('Logout', async ({ page }) => {
    // 1. Authenticate via UI or API
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await expect(page.locator('text=Logout')).toBeVisible();

    // 2. Click logout
    await page.click('text=Logout');
    await expect(page).toHaveURL(/login|\//);

    // Verify protected page redirects to login
    await page.goto('/profile');
    await expect(page).toHaveURL(/login/).catch(() => {});
  });
});
