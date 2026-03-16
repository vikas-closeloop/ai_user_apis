import { test, expect } from '@playwright/test';
import { registerViaUI, logoutViaUI } from './utils/auth';
import { uniqueEmail, uniqueText } from './utils/testData';

test('Logout (happy path)', async ({ page }) => {
  await registerViaUI(page, {
    name: uniqueText('User'),
    email: uniqueEmail('logout'),
    password: 'Password123!',
  });

  await logoutViaUI(page);

  await page.goto('/dashboard');
  await expect(page).toHaveURL(/\/login$/);
});

