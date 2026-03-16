// spec: specs/test.plan.md
// seed: tests/e2e/seed.spec.ts

import { test, expect } from '@playwright/test';

test.describe('API Endpoints', () => {
  test('API Auth Enforcement - protected actions unauthenticated', async ({ request }) => {
    const payload = { title: 'Should Not Create', content: 'No auth' };
    const resp = await request.post('/api/blogs', { data: payload });
    // Expect unauthorized or forbidden
    expect([401, 403]).toContain(resp.status());
  });
});
