<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/includes/helpers.php';

$data = require __DIR__ . '/data/site.php';
$profile = $data['profile'];
$stats = $data['stats'];
$services = $data['services'];
$skills = $data['skills'];
$projects = $data['projects'];
$timeline = $data['timeline'];
$testimonials = $data['testimonials'];
$seo = $data['seo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'subject' => trim((string) ($_POST['subject'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];

    setOldInput($form);

    $errors = [];

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Security check failed. Please refresh the page and try again.';
    }

    if ($form['name'] === '' || strlen($form['name']) < 2) {
        $errors[] = 'Please enter your name.';
    }

    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($form['subject'] === '' || strlen($form['subject']) < 3) {
        $errors[] = 'Please add a short subject.';
    }

    if ($form['message'] === '' || strlen($form['message']) < 15) {
        $errors[] = 'Please write a message with at least 15 characters.';
    }

    if ($errors !== []) {
        flash('error', implode(' ', $errors));
        header('Location: ' . strtok(currentUrl(), '?') . '#contact');
        exit;
    }

    $saved = saveMessage([
        'name' => $form['name'],
        'email' => $form['email'],
        'subject' => $form['subject'],
        'message' => $form['message'],
        'submitted_at' => gmdate('c'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    ]);

    if ($saved) {
        clearOldInput();
        flash('success', 'Message saved successfully. In local development, submissions are stored in storage/messages.json.');
    } else {
        flash('error', 'The message could not be saved. Please check file permissions for storage/messages.json.');
    }

    header('Location: ' . strtok(currentUrl(), '?') . '#contact');
    exit;
}

$successMessage = flash('success');
$errorMessage = flash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($seo['description']); ?>">
    <title><?= e($seo['title']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/styles.css')); ?>">
</head>
<body>
<div class="site-shell">
    <header class="site-header" id="top">
        <div class="container nav-wrap">
            <a class="brand" href="#top">
                <span class="brand-mark">BK</span>
                <span>
                    <strong><?= e($profile['name']); ?></strong>
                    <small><?= e($profile['title']); ?></small>
                </span>
            </a>

            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
                <span></span>
                <span></span>
            </button>

            <nav class="site-nav" id="site-nav" data-nav>
                <a href="#about">About</a>
                <a href="#projects">Projects</a>
                <a href="#skills">Skills</a>
                <a href="#journey">Journey</a>
                <a href="#contact">Contact</a>
            </nav>

            <div class="nav-actions">
                <button class="theme-toggle" type="button" data-theme-toggle aria-label="Toggle theme">
                    <span class="theme-toggle__sun">☀</span>
                    <span class="theme-toggle__moon">☾</span>
                </button>
                <a class="button button--ghost" href="<?= e($profile['github']); ?>" target="_blank" rel="noreferrer">GitHub</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero section">
            <div class="container hero-grid">
                <div class="hero-copy" data-reveal>
                    <div class="eyebrow">
                        <span class="status-dot"></span>
                        <?= e($profile['handle']); ?> · <?= e($profile['location']); ?>
                    </div>
                    <h1><?= e($profile['title']); ?></h1>
                    <p class="hero-lead"><?= e($profile['subtitle']); ?></p>

                    <div class="hero-badges">
                        <?php foreach ($profile['hero_badges'] as $badge): ?>
                            <span><?= e($badge); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="hero-actions">
                        <a class="button" href="#projects">See Projects</a>
                        <a class="button button--ghost" href="#contact">Let’s Work Together</a>
                    </div>
                </div>

                <div class="hero-card" data-reveal>
                    <div class="profile-panel card">
                        <div class="profile-avatar">
                            <span>BK</span>
                        </div>
                        <div>
                            <p class="muted">Now building</p>
                            <h2><?= e($profile['tagline']); ?></h2>
                        </div>
                        <div class="mini-grid">
                            <?php foreach ($profile['highlights'] as $highlight): ?>
                                <article>
                                    <span><?= e($highlight['label']); ?></span>
                                    <strong><?= e($highlight['value']); ?></strong>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container stats-grid" data-reveal>
                <?php foreach ($stats as $item): ?>
                    <article class="stat card">
                        <strong><?= e($item['number']); ?></strong>
                        <span><?= e($item['label']); ?></span>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section" id="about">
            <div class="container section-head" data-reveal>
                <p class="eyebrow">About</p>
                <h2>Backend-focused development with sharp execution.</h2>
                <p class="section-copy">This portfolio is built around the kind of work shown publicly on GitHub: secure PHP products, structured codebases, and practical features that feel ready for real use.</p>
            </div>

            <div class="container about-grid">
                <div class="card prose" data-reveal>
                    <?php foreach ($profile['about'] as $paragraph): ?>
                        <p><?= e($paragraph); ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="stack-column">
                    <div class="card" data-reveal>
                        <p class="card-label">Working Principles</p>
                        <ul class="check-list">
                            <?php foreach ($profile['principles'] as $principle): ?>
                                <li><?= e($principle); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="card" data-reveal>
                        <p class="card-label">Achievements</p>
                        <div class="chip-row">
                            <?php foreach ($profile['achievements'] as $achievement): ?>
                                <span class="chip"><?= e($achievement); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <p class="muted small"><?= e($profile['availability']); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section--muted" id="services">
            <div class="container section-head" data-reveal>
                <p class="eyebrow">What I Build</p>
                <h2>Clear products, secure flows, and maintainable foundations.</h2>
            </div>
            <div class="container services-grid">
                <?php foreach ($services as $service): ?>
                    <article class="card service-card" data-reveal>
                        <div class="service-icon"></div>
                        <h3><?= e($service['title']); ?></h3>
                        <p><?= e($service['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section" id="projects">
            <div class="container section-head" data-reveal>
                <p class="eyebrow">Selected Work</p>
                <h2>Projects shaped by backend thinking and security discipline.</h2>
                <div class="project-filters" data-filters>
                    <button class="filter is-active" type="button" data-filter="all">All</button>
                    <button class="filter" type="button" data-filter="portfolio">Portfolio</button>
                    <button class="filter" type="button" data-filter="backend">Backend</button>
                    <button class="filter" type="button" data-filter="security">Security</button>
                    <button class="filter" type="button" data-filter="product">Product</button>
                </div>
            </div>

            <div class="container projects-grid" data-project-grid>
                <?php foreach ($projects as $project): ?>
                    <article class="card project-card" data-reveal data-category="<?= e($project['category']); ?>">
                        <div class="project-card__top">
                            <span class="project-status"><?= e($project['status']); ?></span>
                            <a class="project-link" href="<?= e($project['url']); ?>" target="_blank" rel="noreferrer">Open ↗</a>
                        </div>
                        <h3><?= e($project['title']); ?></h3>
                        <p><?= e($project['summary']); ?></p>
                        <div class="chip-row">
                            <?php foreach ($project['stack'] as $tool): ?>
                                <span class="chip chip--soft"><?= e($tool); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <ul class="feature-list">
                            <?php foreach ($project['features'] as $feature): ?>
                                <li><?= e($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section section--muted" id="skills">
            <div class="container section-head" data-reveal>
                <p class="eyebrow">Skills</p>
                <h2>The stack behind the work.</h2>
            </div>

            <div class="container skills-grid">
                <?php foreach ($skills as $group => $items): ?>
                    <article class="card skill-card" data-reveal>
                        <h3><?= e($group); ?></h3>
                        <div class="chip-row">
                            <?php foreach ($items as $item): ?>
                                <span class="chip"><?= e($item); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="section" id="journey">
            <div class="container section-head" data-reveal>
                <p class="eyebrow">Journey</p>
                <h2>Recent direction and development focus.</h2>
            </div>

            <div class="container timeline-grid">
                <div class="timeline">
                    <?php foreach ($timeline as $item): ?>
                        <article class="timeline-item" data-reveal>
                            <span class="timeline-item__period"><?= e($item['period']); ?></span>
                            <div class="card">
                                <h3><?= e($item['title']); ?></h3>
                                <p><?= e($item['body']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="testimonial-column">
                    <?php foreach ($testimonials as $entry): ?>
                        <blockquote class="card testimonial" data-reveal>
                            <p>“<?= e($entry['quote']); ?>”</p>
                            <footer><?= e($entry['author']); ?></footer>
                        </blockquote>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section section--accent" id="contact">
            <div class="container contact-grid">
                <div class="contact-copy" data-reveal>
                    <p class="eyebrow">Contact</p>
                    <h2>Need a clean PHP build or a strong portfolio presence?</h2>
                    <p>Use the form to leave a message. In local development, messages are stored directly in a JSON file so the project works immediately after setup.</p>
                    <div class="contact-links">
                        <a href="<?= e($profile['github']); ?>" target="_blank" rel="noreferrer">GitHub Profile</a>
                        <a href="<?= e($profile['email']); ?>">Email</a>
                    </div>
                </div>

                <div class="card" data-reveal>
                    <?php if ($successMessage): ?>
                        <div class="alert alert--success"><?= e($successMessage); ?></div>
                    <?php endif; ?>

                    <?php if ($errorMessage): ?>
                        <div class="alert alert--error"><?= e($errorMessage); ?></div>
                    <?php endif; ?>

                    <form method="post" class="contact-form" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()); ?>">

                        <label>
                            <span>Name</span>
                            <input type="text" name="name" value="<?= e(old('name')); ?>" placeholder="Your name" required>
                        </label>

                        <label>
                            <span>Email</span>
                            <input type="email" name="email" value="<?= e(old('email')); ?>" placeholder="you@example.com" required>
                        </label>

                        <label>
                            <span>Subject</span>
                            <input type="text" name="subject" value="<?= e(old('subject')); ?>" placeholder="Project inquiry" required>
                        </label>

                        <label>
                            <span>Message</span>
                            <textarea name="message" rows="5" placeholder="Tell me about your project"><?= e(old('message')); ?></textarea>
                        </label>

                        <button class="button" type="submit">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-wrap">
            <div>
                <strong><?= e($profile['name']); ?></strong>
                <p>Built with PHP, reusable content data, and a responsive UI that works out of the box.</p>
            </div>
            <p>© <span data-year></span> <?= e($profile['name']); ?>. Built for shipping, not just showing.</p>
        </div>
    </footer>
</div>
<script src="<?= e(asset('js/main.js')); ?>"></script>
</body>
</html>
