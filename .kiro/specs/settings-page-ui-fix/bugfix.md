# Bugfix Requirements Document

## Introduction

The System Settings page (`/settings`) has multiple UI defects affecting tab navigation styling, form submission behavior, and interactive element synchronization. These issues cause the active tab indicator to not render, the "Send Test" email button to corrupt the main form's action (breaking the save button), and the color picker hex preview to remain static when the color is changed.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN a user clicks a tab button (General, Branding, Email, SSO, System & Workflows) THEN the system fails to display the active bottom-border indicator on the selected tab because the static `border-transparent` Tailwind class on the button element overrides the `.active-tab` CSS rule.

1.2 WHEN a user enters a recipient address and clicks the "Send Test" button in the Email (SMTP) tab THEN the system mutates the enclosing form's `action` attribute to the test-email route, causing all subsequent "Sync Configurations" save attempts to POST to the wrong endpoint.

1.3 WHEN a user clicks "Sync Configurations" after having previously clicked "Send Test" THEN the system submits the settings form to the test-email route instead of the settings update route, resulting in a validation error and no settings being saved.

1.4 WHEN a user selects a new color using the primary brand color picker in the Branding tab THEN the system does not update the adjacent read-only hex text input, leaving it displaying the old color value.

1.5 WHEN the settings page first loads THEN the system briefly renders all tab panels simultaneously before Alpine.js initializes, because the `[x-cloak]` CSS rule is defined at the bottom of the page rather than in the document head.

### Expected Behavior (Correct)

2.1 WHEN a user clicks a tab button THEN the system SHALL display a visible bottom-border indicator on the active tab and remove it from all other tabs, using a styling approach that does not conflict with Tailwind's utility classes.

2.2 WHEN a user clicks the "Send Test" button THEN the system SHALL submit only the test email address to the test-email route without modifying the main settings form's `action` attribute.

2.3 WHEN a user clicks "Sync Configurations" at any point (before or after using "Send Test") THEN the system SHALL submit all settings fields to the settings update route correctly.

2.4 WHEN a user selects a new color using the primary brand color picker THEN the system SHALL update the adjacent hex text input in real time to reflect the newly selected color value.

2.5 WHEN the settings page first loads THEN the system SHALL hide all non-active tab panels immediately on page render, with no visible flash of hidden content before Alpine.js initializes.

### Unchanged Behavior (Regression Prevention)

3.1 WHEN a user navigates between tabs THEN the system SHALL CONTINUE TO show only the content panel corresponding to the active tab and hide all others.

3.2 WHEN a user submits the settings form via "Sync Configurations" THEN the system SHALL CONTINUE TO save all settings fields (company info, working days, SMTP, SSO, branding) and redirect back with a success message.

3.3 WHEN a user uploads a company logo THEN the system SHALL CONTINUE TO store the file and display it in the logo preview area.

3.4 WHEN the page is reloaded THEN the system SHALL CONTINUE TO restore the previously active tab from `localStorage`.

3.5 WHEN a user submits the settings form with invalid data THEN the system SHALL CONTINUE TO display validation error messages at the top of the page.
