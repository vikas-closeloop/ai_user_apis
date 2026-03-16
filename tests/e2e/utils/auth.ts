import { expect, type Page } from '@playwright/test';

export async function registerViaUI(
  page: Page,
  user: { name: string; email: string; password: string },
) {
  await page.goto('/register');
  await page.locator('#name').fill(user.name);
  await page.locator('#email').fill(user.email);
  await page.locator('#password').fill(user.password);
  await page.locator('#password_confirmation').fill(user.password);
  await page.locator('button:has-text("Register")').click();

  await expect(page).toHaveURL(/\/dashboard$/);
  await expect(page.locator('text=You\'re logged in!')).toBeVisible();
}

export async function loginViaUI(
  page: Page,
  creds: { email: string; password: string },
) {
  await page.goto('/login');
  await page.locator('#email').fill(creds.email);
  await page.locator('#password').fill(creds.password);
  await page.locator('button:has-text("Log in")').click();
  await expect(page).toHaveURL(/\/dashboard$/);
}

export async function logoutViaUI(page: Page) {
  // Breeze dropdown: click username -> click "Log Out" link.
  await page
    .locator('nav >> css=div.hidden.sm\\:flex.sm\\:items-center.sm\\:ms-6 button')
    .first()
    .click();
  await page
    .locator('form[action$="/logout"]:visible a:has-text("Log Out")')
    .first()
    .click();
  await expect(page).toHaveURL(/\/$/);
}

