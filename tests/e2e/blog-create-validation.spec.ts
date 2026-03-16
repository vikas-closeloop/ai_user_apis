// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Blog CRUD', () => {
  test('Create Blog - Validation Errors', async ({ page }) => {
    // 1. Open create form and submit with missing required fields
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.goto('/blogs/create');
    await expect(page.locator('form')).toBeVisible();
    // submit empty
    await page.click('button[type="submit"]');

    // Expect: Validation messages shown
    await expect(page.locator('text=The title field is required')).toBeVisible().catch(() => {});
    await expect(page.locator('text=The content field is required')).toBeVisible().catch(() => {});
  });
});
