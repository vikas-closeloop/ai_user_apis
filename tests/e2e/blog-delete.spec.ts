// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Blog CRUD', () => {
  test('Delete Blog - Happy Path', async ({ page }) => {
    // 1. Ensure a blog exists (create one)
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.goto('/blogs/create');
    const title = `E2E DeleteBlog ${Date.now()}`;
    await page.fill('input[name="title"]', title);
    await page.fill('textarea[name="content"]', 'Content to be deleted');
    await page.click('button[type="submit"]');
    await expect(page.locator(`text=${title}`)).toBeVisible();

    // 2. Initiate delete and confirm
    page.once('dialog', dialog => dialog.accept());
    await page.click('text=Delete');

    // Expect: Blog removed and redirected to list with success message
    await page.goto('/blogs');
    await expect(page.locator(`text=${title}`)).toHaveCount(0);
  });
});
