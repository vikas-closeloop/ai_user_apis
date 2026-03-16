// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('API Endpoints', () => {
  test('POST /api/blogs - Create (auth)', async ({ page, request }) => {
    // Authenticate via UI to obtain session cookie
    await page.goto('/login');
    await page.fill('input[name="email"]', 'test@example.test');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await expect(page.locator('text=Dashboard')).toBeVisible();

    // Build cookie header from page context
    const cookies = await page.context().cookies();
    const cookieHeader = cookies.map(c => `${c.name}=${c.value}`).join('; ');

    const payload = {
      title: `API Created Blog ${Date.now()}`,
      content: 'Created by Playwright API test',
      tags: ['api', 'e2e']
    };

    const resp = await request.post('/api/blogs', {
      headers: { cookie: cookieHeader, 'content-type': 'application/json' },
      data: payload,
    });

    expect([200, 201]).toContain(resp.status());
    const body = await resp.json();
    expect(body).toHaveProperty('id');
    expect(body.title).toBe(payload.title);
  });
});
