export function uniqueEmail(prefix = 'e2e'): string {
  const rand = Math.random().toString(16).slice(2);
  return `${prefix}.${Date.now()}.${rand}@example.test`;
}

export function uniqueText(prefix = 'e2e'): string {
  const rand = Math.random().toString(16).slice(2);
  return `${prefix}-${Date.now()}-${rand}`;
}

