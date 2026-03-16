import { test, expect } from '@playwright/test';
import { uniqueEmail, uniqueText } from './utils/testData';

test('User signup (happy path)', async ({ page }) => {
  const password = 'Password123!';
  const email = uniqueEmail('signup');

  await page.goto('/register');
  await page.locator('#name').fill(uniqueText('User'));
  await page.locator('#email').fill(email);
  await page.locator('#password').fill(password);
  await page.locator('#password_confirmation').fill(password);
  await page.locator('button:has-text("Register")').click();

  await expect(page).toHaveURL(/\/dashboard$/);
  await expect(page.locator('text=You\'re logged in!')).toBeVisible();
});

test('User signup (negative): password confirmation mismatch shows error', async ({
  page,
}) => {
  const email = uniqueEmail('signup-neg');

  await page.goto('/register');
  await page.locator('#name').fill(uniqueText('User'));
  await page.locator('#email').fill(email);
  await page.locator('#password').fill('Password123!');
  await page.locator('#password_confirmation').fill('DifferentPassword123!');
  await page.locator('button:has-text("Register")').click();

  await expect(page).toHaveURL(/\/register$/);
  await expect(page.locator('ul.text-red-600')).toContainText('match');
});

