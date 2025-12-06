# Guidelines short document for LLM guidelines

## LLM Workflow Guidelines

When working with an LLM agent to make code changes, follow these interaction guidelines:

### 1. **Change Planning Process**
- **Always outline the plan first:** Before making any code changes, the LLM must present a detailed plan of what will be changed and why
- **Wait for confirmation:** Do not proceed with any edits until the developer explicitly confirms the plan
- **Ask for confirmation always:** Even if I tell you to do something, you will again ask me to approve it before editing the files.
- **Be specific:** Include which files will be modified, what changes will be made, and the reasoning behind each change
- **Address concerns:** If the developer has questions or concerns about the plan, revise it before proceeding

### 2. **Command Execution Process**
- **Never execute commands automatically:** Do not run any terminal commands without explicit confirmation
- **Always follow this two-step sequence:**
  1. **Outline first:** Present what commands will be needed and why, WITHOUT executing them
  2. **Wait for confirmation:** Let the developer review and confirm the plan
  3. **Execute one at a time:** Only after confirmation, execute commands one by one
  4. **Ask before each execution:** Get explicit approval before running each command
- **Explain commands:** Briefly explain what each command does and why it's needed
- **Show expected output:** If possible, describe what the expected output should be
- **Example workflow:**
  - ❌ Wrong: "I'll run `rm file.txt`" [executes immediately]
  - ✅ Correct: "I need to run `rm file.txt` to remove the old file. Should I proceed?" [waits for confirmation, then executes]

### 3. **Code Review Process**
- **Review before commit:** Always perform a code review of changes before asking to commit
- **Run error checks:** Use error checking tools to validate changes
- **Present findings:** Outline any issues found during review
- **Fix issues first:** Address all critical errors and code quality issues before considering the work complete

### 4. **Iterative Development**
- **Small incremental changes:** Make changes in small, logical increments
- **Test after each change:** Validate that each change works before moving to the next
- **Ask for feedback:** Check if the developer is satisfied with each increment before continuing
- **Be ready to rollback:** If a change causes issues, be prepared to revert and try a different approach

### 5. **Communication Best Practices**
- **Be clear and concise:** Explain changes in simple, understandable terms
- **Use examples:** When explaining complex changes, provide before/after examples
- **Acknowledge limitations:** If something cannot be done or needs manual intervention, state it clearly
- **Ask for clarification:** If requirements are unclear, ask specific questions before proceeding

### 6. **Error Handling**
- **Report errors immediately:** If any errors occur, report them right away with full context
- **Suggest solutions:** Provide potential fixes or workarounds for errors
- **Don't hide issues:** Always be transparent about problems, even if they were caused by the LLM's changes

---

## MANDATORY WORKFLOW ENFORCEMENT

### When Workflow is NOT Required:

The LLM can respond directly WITHOUT approval for:
- **Questions about code:** Explaining how something works, what errors mean, suggesting approaches
- **Information requests:** Providing documentation, explaining concepts, analyzing code
- **Troubleshooting help:** Diagnosing issues, explaining problems, suggesting solutions
- **Code review:** Reviewing existing code without making changes
- **General discussions:** Architecture discussions, best practices, recommendations

### When Workflow IS Required:

The LLM MUST follow the approval workflow for:
- **File edits:** Any modification to existing files
- **Creating new files:** Adding new code files or configuration
- **Running commands:** Terminal commands, installations, builds
- **Deleting/moving files:** Any file system operations

### Before ANY File Edit or Command:

The LLM MUST:

1. **STOP** - Do not proceed with ANY action
2. **STATE**: "I need to make changes. Following the mandatory workflow..."
3. **OUTLINE** the complete plan with:
  - Files to be modified (with exact paths)
  - Specific changes to each file
  - Commands to be run (if any)
  - Reason for each change
4. **ASK**: "Do you approve this plan? Please confirm with 'yes' or 'approved' before I proceed."
5. **WAIT** - Do not make ANY edits or run ANY commands until explicit approval received
6. **After receiving "yes" or "approved"** - Execute ALL planned changes without asking for additional confirmations

### Single Approval Rule:

- **One approval covers the entire plan:** Once the user approves the plan, execute all changes outlined in that plan
- **No additional confirmations needed:** Do not ask for approval for each individual file edit or command within the approved plan
- **Complete the full task:** Execute all planned changes in sequence until the entire approved plan is finished
- **Only ask again if plan changes:** If you need to modify the plan or add new changes not covered in the original approval, present a new plan

### Key Distinction:

- **"Can you explain this error?"** → Direct response, no editing files suggestion and no editing files without explicit asking to do so.
- **"Can you fix this error?"** → Requires approval workflow before making changes
- **"What does this code do?"** → Direct response, no editing files suggestion and no editing files without explicit asking to do so.
- **"Can you refactor this code?"** → Requires approval workflow before making changes

### Violation Consequences:

If the LLM proceeds with edits or commands WITHOUT following the workflow steps:
- The developer will stop the session
- All changes will be reverted
- The LLM has failed the task

### NO EXCEPTIONS for File Edits:

- "Can you add X?" = STILL follow workflow steps before editing
- "Please fix Y" = STILL follow workflow steps before editing
- "Just do Z" = STILL follow workflow steps before editing

**The workflow is MANDATORY for file modifications, not for responses.**

### After Approval:

- **Execute the full plan:** Complete all outlined changes without additional confirmations
- **Report completion:** Confirm when all changes have been made
- **Handle errors:** If errors occur during execution, report them immediately but continue with remaining changes if possible
