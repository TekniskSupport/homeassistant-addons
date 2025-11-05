### What is this?

This add-on gives you the opportunity to give a guest user access
to certain home assistant devices. It works just like the view "services"
in developer tools in home assistant.

When a link is created and service calls added the guest user gets a button
in the user view (visit the link to view)

You can set a time frame to the actions, as well as make an action "one time use"

### How to use

First, open the admin interface (default port 8899 or through Ingress).
To create a new guest access link, click the "Add New Link" button.
Fill in the details for your new link, including an optional custom path or password, and then click "Create Link". You can also add various Home Assistant service calls to this link, defining their friendly names, service data (e.g., entity_id), and optional timeframes or one-time use restrictions.

Within the valid time frame chosen when you created the action,
your guest can access the external_link/name_of_link 
(for example http://your-external-url.tld:8888/adf12345)
to be able to trigger the actions using a button.

When a visitor accesses a password-protected link, they will be prompted to "Login" with the set password.

### UI Customization

The admin interface now includes a dedicated section for customizing the user-facing page. You can access this by clicking "Manage Customization" from the main admin page.

**Custom CSS**: Define custom CSS rules to change the appearance of the user page. This is injected into the `<head>` section.
**Custom Header HTML**: Add custom HTML content that will appear at the top of the user page, just after the opening `<body>` tag. This is useful for additional navigation, branding, or meta tags.
**Custom Footer HTML**: Add custom HTML content that will appear at the bottom of the user page, just before the closing `</body>` tag. This is useful for disclaimers, additional links, or branding.
**Custom JavaScript**: Add custom JavaScript code that will be injected into the `<head>` section of the user page.

All customization options are managed directly through the admin UI. UI-saved customizations take precedence over any fallback files in the `/share/limited-guest-access/` directory.

### Install instructions

In Home Assistant, head to Supervisor -> add-on store 
and press the [...] menu, then click repositories and paste
`https://github.com/chsln/homeassistant-addons`

The add-on will now show up as a card along with your other add-ons.

Hit install, then edit configuration values and hit start.

If you are unable to start the add-on make sure nothing else is running
on the selected ports.
