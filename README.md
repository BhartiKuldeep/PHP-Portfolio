# PHP Portfolio

A responsive personal portfolio website built with pure PHP, modern CSS, and vanilla JavaScript.

## Features

- Clean one-page portfolio layout
- Responsive design for desktop, tablet, and mobile
- Theme toggle with local storage
- Data-driven sections using PHP arrays
- Working contact form with validation and CSRF protection
- Local message storage in JSON for clone-and-run setup
- Project filtering and scroll-based UI polish

## Tech Stack

- PHP 8+
- HTML5
- CSS3
- Vanilla JavaScript

## Project Structure

```
php-portfolio/
├── assets/
│   ├── css/styles.css
│   └── js/main.js
├── data/site.php
├── includes/helpers.php
├── storage/messages.json
├── index.php
└── README.md
```

## Run Locally

1. Clone the repository
2. Open the project folder
3. Start the PHP development server:

```bash
php -S localhost:8000
```

4. Visit:

```text
http://localhost:8000
```

## Contact Form

The contact form saves messages to `storage/messages.json` during local development so the project works without email or database setup.

## Customize Content

Update portfolio content from:

```php
data/site.php
```

You can change profile text, skills, projects, stats, and contact details from one place.

## Notes

- Make sure PHP sessions are enabled.
- If the form cannot save messages, check write permissions for the `storage/` directory.
- `storage/messages.json` is ignored by Git so local submissions do not clutter commits.
