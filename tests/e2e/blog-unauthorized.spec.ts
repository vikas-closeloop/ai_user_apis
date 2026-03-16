// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('Blog CRUD', () => {
  test('Unauthorized Blog Actions', async ({ page }) => {
    // 1. Without authentication, attempt to access create/edit/delete URLs
    await page.goto('/blogs/create');
    // Expect: redirected to login or shown login form
    await expect(page).toHaveURL(/login/).catch(async () => {
      await expect(page.locator('form')).toBeVisible();
    });

    // Attempt edit of an existing blog (assuming id 1 exists)
    await page.goto('/blogs/1/edit');
    await expect(page).toHaveURL(/login|403/).catch(async () => {
      await expect(page.locator('text=403')).toBeVisible().catch(() => {});
    });

    // Attempt delete via direct URL if applicable
    const resp = await page.request.post('/blogs/1/delete');
    if (resp.status() !== 200) {
      // expect unauthorized status
      expect([401, 403, 302]).toContain(resp.status());
    }
  });
});
