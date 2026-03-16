// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('API Endpoints', () => {
  test('GET /api/blogs - List', async ({ request }) => {
    const resp = await request.get('/api/blogs');
    expect(resp.status()).toBe(200);
    const body = await resp.json();
    expect(Array.isArray(body)).toBeTruthy();
    if (body.length > 0) {
      const item = body[0];
      expect(item).toHaveProperty('id');
      expect(item).toHaveProperty('title');
      expect(item).toHaveProperty('content');
    }
  });
});
