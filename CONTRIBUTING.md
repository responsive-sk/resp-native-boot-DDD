# Contributing

Thank you for contributing — please follow these simple rules to keep the project consistent and easy to collaborate on.

1. Language
   - Write README files and all public documentation in English only.
   - Write git commit messages in English only.
   - This ensures that the project is accessible to all contributors and keeps history searchable.

2. Commit messages
   - Keep the subject line concise (<= 72 chars) and use the imperative mood.
   - Optionally use the Conventional Commits style, e.g.:
     - `feat(mark): add article bulk publish`
     - `fix(blog): prevent SQL injection in articles repo`
     - `chore: update dependencies`
   - If you absolutely must include non-ASCII names, you may bypass the local hook with `--no-verify`, but avoid doing so regularly.

3. Git hooks
   - This repository includes a commit-msg hook that enforces English-only commit messages by rejecting messages that contain Slovak diacritics.
   - Git is already configured to use hooks from `.githooks/` directory automatically via `git config core.hooksPath .githooks`.
   - If you clone the repository fresh and hooks don't work, run: `git config core.hooksPath .githooks`
   - Alternatively, you can manually install hooks by copying: `./scripts/install-git-hooks.sh`
   - The hook is intentionally simple (heuristic check for diacritics). If it blocks a legitimate message, use `--no-verify` and explain in the PR why it was necessary.

4. Documentation
   - Keep `packages/*/README.md` up to date when adding or changing features.
   - Prefer short examples and single commands for local setup.

5. Tests
   - Add unit tests for domain logic and integration tests for use cases that touch persistence.

6. Style
   - Follow existing naming conventions (use `Mark`, not `Admin`).
   - Avoid emojis in documentation and commit messages.

Thank you — your contributions make this project better!
