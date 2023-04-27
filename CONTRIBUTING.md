## Push Rules

Orientation to the [Conventional Commits Specification](https://www.conventionalcommits.org/)

### Branches rule

Regex Branches: `^(build|chore|ci|docs|feat|fix|perf|refactor|revert|style|test)(\(\w+\)?((:\s)|(!:\s)))?(!)?(\/\S*)?`

Examples:

- `feat/ui`
- `fix/error-handling`

### Commits rule

Regex Commits: `^(build|chore|ci|docs|feat|fix|perf|refactor|revert|style|test)(\(\w+\)?((:\s)|(!:\s)))?(!)?(:\s.*)?|^(Merge \w+)`

Examples:

- `feat: add forgot password form`
- `chore(ci)!: update workflow dependency`

Source Regex: [Stack Overflow](https://stackoverflow.com/questions/58899999/regexp-to-match-conventional-commit-syntax)
## Branch und Commit Rules

Composition: `term(component)!: xyz`

- Orientation to the [Conventional Commits Specification](https://www.conventionalcommits.org/)
- `term`: What action is the commit/branch about?
- `component`: Possibility to name the affected component (optional)
- `!`: Is it a major change/adjustment (BREAKING CHANGE)?
- `: oder /`: Separator for commits (:) and branches (/)
- `xyz`: Informative but brief description

| Term       | Example                                    | Beschreibung                                                                                            |
| ---------- | ------------------------------------------ | ------------------------------------------------------------------------------------------------------- |
| `feat`     | `feat(ui)!: add cancel button frontend`    | Integration of a new feature into the code (with `!`: MAJOR; without `!` MINOR)                         |
| `fix`      | `fix/#ISSUE-NUMBER`                        | Fixing an error/bug (PATCH)                                                                             |
| `chore`    | `chore: add temp dir to gitignore`         | Chore                                                                                                   |
| `docs`     | `docs/improve-dev`                         | Changes are only made in the documentation                                                              |
| `refactor` | `refactor(db)/schema`                      | A code change that neither fixes a bug nor adds a feature                                               |
| `test`     | `test: add mock test`                      | Add missing tests or change the existing tests                                                          |
| `build`    | `build: switch from npm to yarn`           | Changes related to the build system or external dependencies                                            |
| `ci`       | `ci/tagging`                               | Changes to the [CI config files](.github/workflows), pipeline and scripts                               |
| `revert`   | `revert: to #435678`                       | When the commit rolls back a previous commit (includes roll back commit's hash)                         |
| `pref`     | `pref(db): buffer overflow`                | A code change that improves performance                                                                 |
| `style`    | `style: rm leading spaces`                 | Changes that do not affect the meaning of the code (formatting, semicolons, spaces, etc.)               |
