### What is this?

This add-on gives you the opportunity to give a guest user access
to certain home assistant devices. It works just like the view "services"
in developer tools in home assistant.

When a link is created and service calls added the guest user gets a button
in the user view (visit the link to view)

You can set a time frame to the actions, as well as make an action "one time use"

### How to use

First, open the admin interface (default port 8899 or through Ingress).
Navigate to the "Create New Link" card to generate a guest access link.

Select which Home Assistant service you want to expose and
enter any required data (e.g., `entity_id` for a device).
You can optionally set a custom path or password for the link.

Within the valid time frame chosen when you created the action,
your guest can access the external_link/name_of_link 
(for example http://your-external-url.tld:8888/adf12345)
to be able to trigger the actions using a button.

### UI Customization

The admin interface now includes a dedicated section for customizing the user-facing page. You can access this by clicking "Manage Custom CSS & Footer" from the main admin page.

**Custom CSS**: Define custom CSS rules to change the appearance of the user page. This is injected into the `<head>` section.
**Custom Footer HTML**: Add custom HTML content that will appear at the bottom of the user page, just before the closing `</body>` tag. This is useful for disclaimers, additional links, or branding.

For advanced customization (e.g., custom JavaScript or header HTML), the addon still supports injecting `script.js` directly into the `<head>` and `header.htm` just after the opening `<body>` tag. These files should be placed in the `/share/limited-guest-access/` directory.

### Install instructions

In home assistant, head to Supervisor -> add-on store 
and press the [...] menu, then click repositories and paste
`https://github.com/TekniskSupport/homeassistant-addons`

The add-on will now show up as a card along with the other add-ons

Hit install, then edit configuration value and hit start.

If you are unable to start the add-on make sure nothing else is running
on the selected ports.
