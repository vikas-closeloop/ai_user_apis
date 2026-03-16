import { test, expect } from '@playwright/test';
import { registerViaUI, logoutViaUI } from './utils/auth';
import { uniqueEmail, uniqueText } from './utils/testData';

test('User login (happy path)', async ({ page }) => {
  const password = 'Password123!';
  const email = uniqueEmail('login');

  await registerViaUI(page, {
    name: uniqueText('User'),
    email,
    password,
  });
  await logoutViaUI(page);

  await page.goto('/login');
  await page.locator('#email').fill(email);
  await page.locator('#password').fill(password);
  await page.locator('button:has-text("Log in")').click();

  await expect(page).toHaveURL(/\/dashboard$/);
  await expect(page.locator('text=You\'re logged in!')).toBeVisible();
});

test('User login (negative): wrong password shows error', async ({ page }) => {
  const password = 'Password123!';
  const email = uniqueEmail('login-neg');

  await registerViaUI(page, {
    name: uniqueText('User'),
    email,
    password,
  });
  await logoutViaUI(page);

  await page.goto('/login');
  await page.locator('#email').fill(email);
  await page.locator('#password').fill('WrongPassword123!');
  await page.locator('button:has-text("Log in")').click();

  await expect(page).toHaveURL(/\/login$/);
  await expect(
    page.locator('text=These credentials do not match our records.'),
  ).toBeVisible();
});

