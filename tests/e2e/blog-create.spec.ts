// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Blog CRUD', () => {
  test('Create Blog - Happy Path', async ({ page }) => {
    // 1. Authenticate as author
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await expect(page.locator('text=Dashboard')).toBeVisible();

    // 2. Navigate to create blog form; fill title, content, tags; submit
    await page.goto('/blogs/create');
    await expect(page.locator('form')).toBeVisible();
    const title = `E2E Blog ${Date.now()}`;
    await page.fill('input[name="title"]', title);
    await page.fill('textarea[name="content"]', 'This is content created by E2E test.');
    await page.fill('input[name="tags"]', 'e2e,playwright');
    await page.click('button[type="submit"]');

    // Expect: New blog record present in UI and redirect to detail
    await expect(page).toHaveURL(/blogs\/\d+|blogs\//);
    await expect(page.locator(`text=${title}`)).toBeVisible();
  });
});
