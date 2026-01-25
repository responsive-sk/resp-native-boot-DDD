#!/usr/bin/env bash
# Installs git hooks from .githooks into .git/hooks for this repo (local only).
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
GITHOOKS_DIR="$REPO_ROOT/.githooks"
GIT_DIR="$REPO_ROOT/.git"

if [ ! -d "$GIT_DIR" ]; then
  echo "No .git directory found at $GIT_DIR. Run this script from inside a git repository."
  exit 1
fi

echo "Installing git hooks from $GITHOOKS_DIR to $GIT_DIR/hooks"
mkdir -p "$GIT_DIR/hooks"

for hook in "$GITHOOKS_DIR"/*; do
  hookname=$(basename "$hook")
  dest="$GIT_DIR/hooks/$hookname"
  echo "Installing $hookname -> $dest"
  cp -f "$hook" "$dest"
  chmod +x "$dest"
done

echo "Hooks installed. To uninstall, remove files from $GIT_DIR/hooks or run 'git config core.hooksPath .githooks' to use repo-level hooks."
