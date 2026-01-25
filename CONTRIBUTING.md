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

   Commit message template and examples
   - Template (one-line subject, blank line, optional body):

     ```text
     <type>(<scope>): <short summary>

     Optional longer description. Describe the _what_ and _why_, not the _how_.
     Reference issues or PRs if relevant: "Refs: #123".
     ```

   - Common `type` values:
     - `feat` — a new feature
     - `fix` — a bug fix
     - `docs` — documentation only changes
     - `style` — formatting, missing semicolons, etc; no code change
     - `refactor` — refactoring code without adding features or fixing bugs
     - `perf` — a code change that improves performance
     - `test` — adding missing tests or correcting existing tests
     - `chore` — build process or auxiliary tooling changes

   - Examples:

     ```text
     feat(mark): add bulk publish action for selected articles

     Adds a bulk action in the Mark dashboard to publish multiple articles at once
     and improves performance by batching repository operations.
     Refs: #456
     ```

     ```text
     fix(blog): validate slug uniqueness before insert

     Prevents constraint violations and provides a clearer error message to the user.
     ```

3. Git hooks
   - This repository includes a commit-msg hook that enforces English-only commit messages by rejecting messages that contain Slovak diacritics.
   - To install the hook locally, run from the repository root:

     ```bash
     ./scripts/install-git-hooks.sh
     ```

   - The hook is intentionally simple (heuristic check for diacritics). If it blocks a legitimate message, use `--no-verify` and explain in the PR why it was necessary.

4. Documentation
   - Keep `packages/*/README.md` up to date when adding or changing features.
   - Prefer short examples and single commands for local setup.

5. Tests
   - Add unit tests for domain logic and integration tests for use cases that touch persistence.

6. Style
   - Follow existing naming conventions (use `Mark`, not `Admin`).
   - Avoid emojis in documentation and commit messages.

**Commit template (optional but recommended)**
- You can set a local commit message template to help follow the guidelines:

  ```bash
  git config commit.template .gitmessage
  ```

  The repository includes `.gitmessage` with a short template you can use.

Thank you — your contributions make this project better!

PS: As a reminder — please write `README` files and git commit messages in English only. This helps keep the project accessible to all contributors. If you need to include non-English names, prefer adding them in the PR body or code comments (not in the commit subject), and explain any exceptions in the PR description.
