# README File Generation Guidelines for WordPress Plugins & Themes

This document provides comprehensive guidelines for AI agents when generating README.MD files for WordPress plugins or themes. Use this as a reference to create consistent, professional, and complete documentation.

---

## Document Purpose

A README.MD file serves as the primary documentation for a WordPress plugin or theme. It should:

- **Inform users** about what the plugin/theme does
- **Guide installation** and configuration
- **Document features** and functionality
- **Provide troubleshooting** help
- **Include developer information** for extensibility
- **Be accessible** to both technical and non-technical users

**Target Audience:** WordPress site owners, administrators, developers, and end users

---

## Required Sections

Every README.MD file MUST include these sections (in this order):

### 1. Title and Tagline

```markdown
# Plugin/Theme Name

A brief one-line description of what the plugin/theme does.
```

**Example:**
```markdown
# AP API Integration

A WordPress plugin that integrates with the AP API to synchronize stores data on a daily schedule with comprehensive logging and automatic log rotation.
```

**Guidelines:**
- Use H1 (`#`) for the title
- Keep tagline under 150 characters
- Be specific about what it does
- Include key features in tagline if space allows

---

### 2. Overview Section

```markdown
## Overview

[2-3 paragraph description of the plugin/theme]
```

**What to include:**
- What problem it solves
- Who it's for
- Key benefits
- How it works (high-level)

**Example:**
```markdown
## Overview

The AP API Integration plugin provides seamless integration between your WordPress site and the AP API. It automatically fetches and stores store details on a daily basis, maintains detailed logs of all operations, and includes intelligent log management to keep your server clean.
```

---

### 3. Features Section

```markdown
## Features

- ✅ **Feature 1** - Description
- ✅ **Feature 2** - Description
- ✅ **Feature 3** - Description
```

**Guidelines:**
- Use checkmarks (✅) for visual appeal
- Bold the feature name
- Keep descriptions concise (one line)
- List 5-15 key features
- Start with most important features
- Use action-oriented language

**Example:**
```markdown
## Features

- ✅ **Automated Daily Sync** - Fetches store details from AP API once per day
- ✅ **Secure Authentication** - Token-based authentication with the AP API
- ✅ **Admin Settings Page** - Easy configuration of API credentials
```

---

### 4. Requirements Section

```markdown
## Requirements

- **WordPress:** [minimum version] or higher
- **PHP:** [minimum version] or higher
- **PHP Extensions:** [list required extensions]
- **Other:** [any other requirements]
```

**Guidelines:**
- Be specific about minimum versions
- List all required PHP extensions
- Include theme requirements if applicable
- Mention any required plugins

**Example:**
```markdown
## Requirements

- **WordPress:** 6.8 or higher
- **PHP:** 8.3 or higher
- **PHP Extensions:** JSON, cURL (typically included with WordPress)
```

---

### 5. Installation Section

```markdown
## Installation

### Automatic Installation
[steps for uploading via WordPress admin]

### Manual Installation
[steps for FTP/file upload]
```

**Standard Template:**
```markdown
## Installation

### Automatic Installation

1. Download the plugin zip file
2. Go to **WordPress Admin → Plugins → Add New**
3. Click **Upload Plugin** button
4. Choose the zip file and click **Install Now**
5. Click **Activate Plugin**

### Manual Installation

1. Download and extract the plugin zip file
2. Upload the `plugin-name` folder to `/wp-content/plugins/` directory
3. Go to **WordPress Admin → Plugins**
4. Find **Plugin Name** and click **Activate**
```

**For Themes:** Replace "Plugins" with "Appearance → Themes"

---

### 6. Configuration Section

```markdown
## Configuration

### Setting Up [Feature Name]

1. Go to **WordPress Admin → [Menu Location]**
2. Enter your settings:
   - **Field 1** - Description
   - **Field 2** - Description
3. Click **Save Settings**
```

**Guidelines:**
- Provide step-by-step instructions
- Use bold for UI elements (menu names, buttons)
- Include screenshots if complex
- Mention default values
- Note any important considerations

---

### 7. Support Section

```markdown
## Support

### Getting Help

[Instructions on where to get help]

### Reporting Issues

When reporting an issue, please include:
- WordPress version
- PHP version
- Plugin/theme version
- Steps to reproduce
- Error messages or screenshots
```

**Guidelines:**
- Provide clear contact methods
- Set expectations for response time
- List information needed for bug reports
- Include links to documentation or forums

---

## Optional Sections (Include When Applicable)

### 8. How It Works Section

Include when the plugin/theme has complex functionality that needs explanation.

```markdown
## How It Works

### [Process Name]

1. **Step 1** - What happens
2. **Step 2** - What happens
3. **Step 3** - What happens
```

**When to include:**
- Plugin has automated processes (cron jobs, sync, etc.)
- Complex data flow
- Integration with external services
- Non-obvious functionality

---

### 9. Cron Jobs / Scheduled Tasks Section

Include when plugin uses WordPress cron or scheduled tasks.

```markdown
## Cron Jobs

The plugin uses [number] automated cron jobs:

### 1. [Task Name]

- **Hook Name:** `hook_name`
- **Schedule:** [frequency]
- **Purpose:** [what it does]
- **Self-Healing:** [yes/no and explanation]
```

**Example:**
```markdown
### 1. Daily Data Sync

- **Hook Name:** `ap_api_daily_sync`
- **Schedule:** Once per day
- **Purpose:** Fetches store details from AP API
- **Self-Healing:** Automatically reschedules if deleted
```

---

### 10. Logging System Section

Include when plugin has logging functionality.

```markdown
## Logging System

### Log File Location
[Where logs are stored]

### Log File Naming
[Naming convention and format]

### Log File Content
[What's logged and format]

### Accessing Logs
[How users can view logs]

### Log Retention
[How long logs are kept and cleanup policy]
```

---

### 11. Recommended Plugins Section

Include when there are plugins that enhance or complement functionality.

```markdown
## Recommended Plugins

### [Plugin Name]

We highly recommend installing **[Plugin Name]** to [benefit].

**Plugin Link:** [URL]

#### Why [Plugin Name] is Useful:

- ✅ **Benefit 1** - Description
- ✅ **Benefit 2** - Description

#### How to Use [Plugin Name] with This Plugin:

1. [Step-by-step instructions]
```

**Example from AP API Integration:**
```markdown
### WP Crontrol

We highly recommend installing **WP Crontrol** to monitor and manage the plugin's cron jobs.

**Plugin Link:** [https://wordpress.org/plugins/wp-crontrol/](https://wordpress.org/plugins/wp-crontrol/)

#### Why WP Crontrol is Useful:

- ✅ **View Scheduled Cron Jobs** - See when the next sync will run
- ✅ **Run Jobs Manually** - Trigger sync or cleanup on demand
```

---

### 12. Troubleshooting Section

Always include when plugin has any complexity. This is one of the most valuable sections.

```markdown
## Troubleshooting

### [Issue Name]

**Symptoms:** [What users will observe]

**Solutions:**
1. [Solution step 1]
2. [Solution step 2]
3. [Solution step 3]
```

**Common Issues to Cover:**
- Setup/configuration problems
- Feature not working
- Cron jobs not running
- Authentication/connection errors
- Performance issues
- Conflict with other plugins/themes

**Guidelines:**
- List 5-10 common issues
- Start with most common problems
- Provide step-by-step solutions
- Include code examples if needed
- Link to relevant sections

---

### 13. Developer Information Section

Include when plugin is extensible or has a public API.

```markdown
## Developer Information

### Plugin Architecture
[High-level technical overview]

### Key Components
[List and describe main classes/modules]

### Hooks and Filters
[List available hooks with descriptions]

### Extending the Plugin
[How developers can extend functionality]
```

**What to Include:**
- Namespace structure
- Design patterns used
- Key classes and their responsibilities
- Available hooks (actions/filters)
- Code examples for extending
- API documentation
- Constants and their purposes

**Example Structure:**
```markdown
### Key Components

#### 1. Client (`src/DataSync/Client.php`)
- Handles HTTP communication with API
- Supports GET and POST methods
- 30-second timeout for API requests
```

---

### 14. Changelog Section

```markdown
## Changelog

### Version X.X.X
- [Change 1]
- [Change 2]
- [Change 3]

### Version X.X.X
- [Previous version changes]
```

**Guidelines:**
- List versions in reverse chronological order (newest first)
- Group changes by type: Added, Changed, Fixed, Removed
- Be specific about what changed
- Include dates if applicable

---

## Formatting Standards

### Headers

```markdown
# H1 - Plugin Title Only
## H2 - Main Sections
### H3 - Subsections
#### H4 - Sub-subsections (use sparingly)
```

**Rules:**
- Only ONE H1 per document (the title)
- Use H2 for major sections
- Use H3 for subsections
- Avoid going deeper than H4

---

### Lists

**Unordered Lists:**
```markdown
- Item 1
- Item 2
  - Sub-item 2a
  - Sub-item 2b
```

**Ordered Lists:**
```markdown
1. Step 1
2. Step 2
3. Step 3
```

**Guidelines:**
- Use unordered for feature lists
- Use ordered for step-by-step instructions
- Indent sub-items with 2 spaces
- Keep consistent formatting

---

### Code Blocks

**Inline Code:**
```markdown
Use `code` for inline references
```

**Code Blocks:**
````markdown
```php
<?php
// PHP code example
```

```bash
# Shell commands
```

```
Plain text or generic code
```
````

**When to Use:**
- Inline code: function names, class names, constants, file paths
- Code blocks: examples, commands, configuration snippets

---

### Emphasis

```markdown
**Bold** - For UI elements, feature names, important terms
*Italic* - For emphasis (use sparingly)
```

**Rules:**
- Bold for: buttons, menu items, field names, feature names
- Don't overuse emphasis
- Be consistent throughout document

---

### Links

```markdown
[Link Text](https://example.com)
```

**Guidelines:**
- Use descriptive link text (not "click here")
- Include full URLs for external resources
- Link to relevant sections within document using anchors

---

### Horizontal Rules

```markdown
---
```

**When to Use:**
- Separate major sections
- Create visual breaks
- Don't overuse (max 1-2 per major section)

---

### Emojis and Symbols

**Recommended Use:**
- ✅ Checkmarks for features
- ❌ X marks for "don't do this"
- ⚠️ Warning symbol for important notes
- 📝 Note symbol for additional information

**Don't Overuse:**
- Keep professional
- Use consistently
- Don't use in headers

---

## Content Guidelines

### Writing Style

**Tone:**
- Professional but friendly
- Clear and concise
- Action-oriented
- User-focused

**Voice:**
- Use active voice (not passive)
- Use "you" to address the reader
- Use present tense
- Be direct

**Examples:**
- ✅ Good: "Click the Save button"
- ❌ Bad: "The Save button should be clicked"

---

### Terminology

**WordPress-Specific Terms:**
- Use "WordPress Admin" not "admin panel" or "backend"
- Use "plugin" not "extension" or "add-on"
- Use "theme" not "template" or "skin"
- Use proper menu paths: "Settings → General"

**Technical Terms:**
- Define acronyms on first use
- Use consistent terminology throughout
- Link to WordPress documentation when relevant

---

### Instructions

**Best Practices:**
- Number sequential steps
- Bold UI elements: **Save Settings**
- Use arrow for menu navigation: Settings → Plugins
- Include screenshots for complex steps
- Provide alternative methods when applicable

**Example:**
```markdown
1. Go to **WordPress Admin → Settings → AP API Integration**
2. Enter your **API Username** in the username field
3. Click **Save Settings**
```

---

## WordPress-Specific Requirements

### Security Considerations

**What to Document:**
- How credentials are stored
- Security best practices
- Permission requirements
- Data handling policies

**Example:**
```markdown
**Note:** API credentials are stored securely in the WordPress options table with autoload disabled for performance.
```

---

### Performance Notes

**Include When Relevant:**
- Caching strategies used
- Database query optimization
- Asset loading practices
- Large dataset handling

---

### Compatibility

**Document:**
- WordPress version compatibility
- PHP version requirements
- Known plugin/theme conflicts
- Multisite support
- Gutenberg compatibility (if relevant)

**Example:**
```markdown
- **Tested up to WordPress:** 6.4
- **Multisite Compatible:** Yes
- **Gutenberg Compatible:** Yes
```

## Common Mistakes to Avoid

### Content Mistakes

❌ **Don't:**
- Use vague descriptions ("This plugin is great")
- Skip installation instructions
- Forget troubleshooting section
- Use technical jargon without explanation
- Make assumptions about user knowledge
- Include outdated information
- Copy/paste without customization

✅ **Do:**
- Be specific and clear
- Provide complete instructions
- Include troubleshooting help
- Explain technical terms
- Guide users step-by-step
- Keep information current
- Customize for your plugin/theme

---

### Formatting Mistakes

❌ **Don't:**
- Use multiple H1 headers
- Inconsistent code block formatting
- Mix list styles
- Overuse emphasis
- Create walls of text
- Use unclear link text

✅ **Do:**
- Use single H1 for title
- Format code consistently
- Use appropriate list types
- Bold UI elements only
- Break text into paragraphs
- Use descriptive link text

---

### Structure Mistakes

❌ **Don't:**
- Put developer info before user info
- Skip required sections
- Create overly deep nesting
- Mix unrelated topics in one section
- Use inconsistent section ordering

✅ **Do:**
- Follow logical order (user → developer)
- Include all required sections
- Keep structure flat
- Group related information
- Use consistent organization

---

## Special Considerations

### For API Integration Plugins

**Must Include:**
- Authentication method
- API endpoint documentation
- Rate limiting information
- Error handling explanation
- Credential storage details
- Connection troubleshooting

**Example Section:**
```markdown
## API Configuration

The plugin connects to the [Service Name] API using token-based authentication.

### Authentication
[How authentication works]

### Rate Limits
[API rate limit information]

### Troubleshooting Connection Issues
[Common connection problems and solutions]
```

---

### For Plugins with Cron Jobs

**Must Include:**
- All cron job hooks and schedules
- Self-healing mechanism explanation
- Manual triggering instructions
- Recommended monitoring plugins (like WP Crontrol)
- Troubleshooting for cron not running

---

### For Plugins with Logging

**Must Include:**
- Log file locations
- Log rotation policy
- How to access logs
- What's logged
- Log retention/cleanup
- Privacy considerations

---

### For Themes

**Additional Sections:**
- Demo link/screenshots
- Customization options
- Widget areas
- Menu locations
- Template files
- Child theme compatibility
- Page templates available

---

## Markdown Best Practices

### File Naming
- Use `README.MD` (all caps) or `README.md` (all lowercase)
- Be consistent across projects

### Line Length
- Keep lines under 120 characters when possible
- Break long lines at logical points
- Don't break URLs

### Whitespace
- One blank line between sections
- Two blank lines before major sections
- Consistent indentation (2 or 4 spaces)

### Tables (When Needed)

```markdown
| Column 1 | Column 2 | Column 3 |
|----------|----------|----------|
| Data 1   | Data 2   | Data 3   |
```

**When to Use:**
- Comparing options
- Listing constants/hooks
- Showing compatibility matrix

---

## Final Checklist

Before finalizing README.MD, verify:

- [ ] Title and tagline are clear
- [ ] Overview explains what it does
- [ ] All required sections included
- [ ] Installation steps are complete
- [ ] Configuration is documented
- [ ] Troubleshooting section exists
- [ ] Support information provided
- [ ] Formatting is consistent
- [ ] Code examples are correct
- [ ] Links work properly
- [ ] Spelling/grammar checked
- [ ] WordPress version noted
- [ ] PHP version noted
- [ ] Screenshots included (if needed)
- [ ] Changelog is up to date

---

## Additional Resources

- [GitHub Markdown Guide](https://guides.github.com/features/mastering-markdown/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [Semantic Versioning](https://semver.org/)

---

## Summary

A great README.MD file:
- **Informs** users clearly about functionality
- **Guides** through installation and setup
- **Documents** all features comprehensively
- **Troubleshoots** common issues
- **Supports** developers with technical details
- **Maintains** consistent formatting
- **Follows** WordPress standards

Use this guide as a reference when generating README files to ensure consistency, completeness, and professional quality documentation.

---

**Document Version:** 1.0

