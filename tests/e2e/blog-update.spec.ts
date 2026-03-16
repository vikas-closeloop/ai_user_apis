// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Blog CRUD', () => {
  test('Update Blog - Happy Path', async ({ page }) => {
    // 1. Ensure a blog exists owned by user (create one)
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.goto('/blogs/create');
    const title = `E2E UpdateBlog ${Date.now()}`;
    await page.fill('input[name="title"]', title);
    await page.fill('textarea[name="content"]', 'Initial content');
    await page.click('button[type="submit"]');
    await expect(page.locator(`text=${title}`)).toBeVisible();

    // 2. Navigate to edit form, change fields, submit
    // assuming an Edit link exists on blog detail
    await page.click('text=Edit');
    await expect(page.locator('form')).toBeVisible();
    const updated = `${title} - updated`;
    await page.fill('input[name="title"]', updated);
    await page.fill('textarea[name="content"]', 'Updated content by E2E test');
    await page.click('button[type="submit"]');

    // Expect: Changes persisted and visible
    await expect(page.locator(`text=${updated}`)).toBeVisible();
  });
});
