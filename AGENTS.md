# CRITICAL RULES - MUST FOLLOW

## PLANNING MODE

- Always ask clarifying questions before implementing anything non-trivial
- Never assume design, tech stack, or features without confirmation
- Never invent requirements that weren't explicitly requested
- Before coding, present a short plan (steps, affected files, technical decisions) and wait for approval
- If there's more than one reasonable way to solve the problem, list the options with pros/cons instead of choosing alone
- Ask about existing project conventions before introducing a new pattern (e.g. folder naming, API response format, commit style)
## CHANGE / EDIT MODE

- Only make the requested change — don't refactor unrelated code "for free"
- Never delete or rewrite tests just to make the build pass; fix the root cause
- Preserve the formatting, indentation, and style already used in the file
- Don't add new dependencies without flagging and justifying the reason
- Always run/lint existing tests before considering the task complete (when a test environment exists)
- If a change breaks something elsewhere (route, model, migration), flag it explicitly before proceeding
- Prefer small, reviewable diffs over large, monolithic changes
- Never run `git push`, `git commit --amend` on shared history, or destructive commands (`rm -rf`, `git reset --hard`, dropping migrations in production) without explicit confirmation
## CODE & STANDARDS

- Follow PSR-12 and Laravel conventions (controller names, resources, form requests, etc.)
- Use strict typing (`declare(strict_types=1)` where applicable) and type hints on public methods
- Prefer idiomatic Eloquent (`updateOrCreate`, relationships, scopes) over raw SQL queries unless there's a justified need
- Handle nullable/optional fields explicitly — never assume a field from an external API will always be present
- Jobs, Listeners, and Queues should be idempotent whenever possible
- Name variables and methods in English, even if comments are in another language (or define the project's standard and stick to it)
## SUB-AGENTS / PARALLEL EXECUTION

- When using sub-agents to implement features, first break the approved plan into discrete, independent units of work
- Identify which changes from the plan are truly independent (no shared files, no sequential data dependency) and can be implemented in parallel
- Use sub-agents to implement those independent parts concurrently instead of working through the plan strictly step-by-step
- Keep tasks that touch the same file, model, or migration in the same agent (or run them sequentially) to avoid conflicting edits
- Give each sub-agent a narrow, well-scoped piece of the plan — not the whole feature — so its output is easy to review in isolation
- After sub-agents finish, integrate and review all changes together before considering the feature complete: check for duplicated logic, inconsistent naming, and conflicting assumptions between the parallelized parts
- If a unit of work depends on the output of another (e.g. a migration must exist before a model can use its columns), run it sequentially — never parallelize just for the sake of speed
- Report back which parts were parallelized and why, so the decision is transparent and reviewable
## COMMUNICATION

- Explain the "why" behind technical decisions, not just the "what"
- If something requested is a bad practice or has a better alternative, say so before implementing it
- Flag assumptions made during implementation clearly and briefly
- Never pretend something was tested when it wasn't
## SECURITY

- Never commit secrets, tokens, or credentials — always use `.env` and `.env.example`
- Validate and sanitize all input coming from users or external APIs (Form Requests, schema validation)
- Don't disable CSRF, authentication, or validation "just for testing" without reverting it afterward and flagging it

## UI DESIGN

- Always follow the UI design system when creating or reviewing components or pagas.
- Design System: @DESIGN.md
