# Page snapshot

```yaml
- generic [ref=e2]:
  - link [ref=e4] [cursor=pointer]:
    - /url: /
    - img [ref=e5]
  - generic [ref=e8]:
    - generic [ref=e9]:
      - generic [ref=e10]: Email
      - textbox "Email" [active] [ref=e11]: login-neg.1773640064749.f043e32ff2955@example.test
      - list [ref=e12]:
        - listitem [ref=e13]: These credentials do not match our records.
    - generic [ref=e14]:
      - generic [ref=e15]: Password
      - textbox "Password" [ref=e16]
    - generic [ref=e18]:
      - checkbox "Remember me" [ref=e19]
      - generic [ref=e20]: Remember me
    - generic [ref=e21]:
      - link "Forgot your password?" [ref=e22] [cursor=pointer]:
        - /url: http://127.0.0.1:8000/forgot-password
      - button "Log in" [ref=e23] [cursor=pointer]
```