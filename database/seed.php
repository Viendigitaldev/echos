<?php

/**
 * One-time seed: imports the copy that used to be hardcoded across
 * index.php / what-we-do.php / application.php / blog.php / blog-detail.php /
 * contact.php / header.php / footer.php so the DB-driven site renders the
 * same content the static template did. Run once: php database/seed.php
 */

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Database\Connection;

$pdo = Connection::get();

function upsertPage(\PDO $pdo, string $slug, ?string $seoTitle, ?string $seoDescription): int
{
    $stmt = $pdo->prepare('INSERT INTO pages (slug, seo_title, seo_description) VALUES (:slug, :title, :description)');
    $stmt->execute(['slug' => $slug, 'title' => $seoTitle, 'description' => $seoDescription]);
    return (int) $pdo->lastInsertId();
}

function insertBlock(\PDO $pdo, int $pageId, string $key, array $fields, int $sort): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO page_blocks (page_id, block_key, heading, subheading, body, image_path, cta_label, cta_url, sort_order)
         VALUES (:page_id, :block_key, :heading, :subheading, :body, :image_path, :cta_label, :cta_url, :sort_order)'
    );
    $stmt->execute([
        'page_id' => $pageId,
        'block_key' => $key,
        'heading' => $fields['heading'] ?? null,
        'subheading' => $fields['subheading'] ?? null,
        'body' => $fields['body'] ?? null,
        'image_path' => $fields['image_path'] ?? null,
        'cta_label' => $fields['cta_label'] ?? null,
        'cta_url' => $fields['cta_url'] ?? null,
        'sort_order' => $sort,
    ]);
}

$pdo->beginTransaction();

try {
    // ---------------------------------------------------------------- pages
    $homeId = upsertPage($pdo, 'home', 'Echos AI — Enterprise Artificial Intelligence Platform', 'Echos partners with enterprises to connect human expertise, enterprise context, and machine intelligence into thinking systems that continuously learn and improve.');
    $aboutId = upsertPage($pdo, 'about', 'About Echos — Forward Deployed Engineering', 'Echos combines forward-deployed engineering with AI-native thinking systems to help enterprises build enduring advantage.');
    $applicationsId = upsertPage($pdo, 'applications', 'Applications — Echos Thinking Systems', 'Explore enterprise ready AI applications that accelerate time to value and business impact.');
    $perspectivesId = upsertPage($pdo, 'perspectives', 'Perspectives — Echos', 'From vision to execution, we make intelligence work for you, at scale.');
    $contactId = upsertPage($pdo, 'contact', 'Contact Echos', "Ready to build your AI-first enterprise? Let's talk.");
    $thankYouId = upsertPage($pdo, 'thank-you', 'Thank You — Echos', 'Thanks for reaching out to Echos.');

    // ---------------------------------------------------------- home blocks
    insertBlock($pdo, $homeId, 'hero', [
        'heading' => 'Engineering the AI-First Enterprise',
        'body' => 'Echos partners with enterprises to connect human expertise, enterprise context, and machine intelligence into thinking systems that continuously learn and improve.',
        'cta_label' => 'Start Building',
        'cta_url' => '/contact',
    ], 1);

    insertBlock($pdo, $homeId, 'about_teaser', [
        'body' => 'The enterprises that define the next decade will place intelligence at the core of how they operate, make decisions, and create value. Organizations that learn faster will improve faster, creating advantages that compound over time.',
        'image_path' => 'assets/img/home-1/about-image.png',
    ], 2);

    insertBlock($pdo, $homeId, 'partner_elite', [
        'heading' => 'The Foundation of AI-First Enterprises',
        'body' => 'AI-first enterprises are built on a new technology foundation that combines enterprise context, operational systems, and frontier intelligence. Through our partnerships with Palantir and Anthropic, we help organizations build the infrastructure required to embed intelligence at scale.',
    ], 3);

    insertBlock($pdo, $homeId, 'thinking_systems', [
        'heading' => 'Thinking Systems at Work',
        'cta_label' => 'View All Applications',
        'cta_url' => '/applications',
    ], 4);

    insertBlock($pdo, $homeId, 'industries', [
        'heading' => 'Building AI-First Enterprises across Industries',
    ], 5);

    insertBlock($pdo, $homeId, 'media', [
        'heading' => 'In The Media',
        'body' => 'Despite more than $250 billion being invested in AI globally in 2025, only 25% of companies say it is having a transformative impact. Many organizations are still layering AI on to existing processes rather than redesigning how they operate around intelligence.',
        'image_path' => 'assets/img/inner-page/media2.jpg',
        'cta_label' => 'Read Article',
        'cta_url' => 'https://www.weforum.org/publications/the-ai-first-operating-system-a-blueprint-for-operating-and-business-model-innovation/',
    ], 6);

    insertBlock($pdo, $homeId, 'perspectives_teaser', [
        'heading' => 'Perspectives',
        'body' => 'See what our engineers are building, thinking, and shipping at the frontier of enterprise AI. Read here.',
        'cta_label' => 'Explore More',
        'cta_url' => '/perspectives',
    ], 7);

    // --------------------------------------------------------- about blocks
    insertBlock($pdo, $aboutId, 'vision', [
        'heading' => 'Our Vision',
        'body' => 'We founded Echos because we believe the future belongs to enterprises that can continuously learn and improve. In a world of human and AI teaming, organizations need systems that connect human expertise, enterprise context, and machine intelligence into a shared source of learning. We call these Thinking Systems, systems that compound intelligence over time, helping organizations make better decisions, adapt faster, and create enduring advantage.',
    ], 1);

    insertBlock($pdo, $aboutId, 'mindset', [
        'heading' => 'Forward Deployed Engineering',
        'body' => "Technology creates possibilities. People create outcomes. Our forward-deployed engineers work at the intersection of human expertise, enterprise context, and machine intelligence. Embedded within customer teams, they connect stakeholders, bridge functions, and break down silos to ensure technology serves business outcomes, not the other way around. By combining deep technical expertise with domain knowledge and hands-on execution, they help organizations move faster, make better decisions, and build capabilities that create enduring advantage long after the technology is deployed.",
    ], 2);

    insertBlock($pdo, $aboutId, 'builders', [
        'heading' => 'Powered by Exceptional Talent',
        'body' => "Experience from the world's leading technology companies and institutions.",
    ], 3);

    insertBlock($pdo, $aboutId, 'ecosystem', [
        'heading' => 'Our Ecosystem',
        'body' => 'Echos partners with Palantir and Anthropic to deliver a unified data and AI foundation for the enterprise. Together, we connect the infrastructure, intelligence, and expertise that modern organisations need to operate at scale',
    ], 4);

    insertBlock($pdo, $aboutId, 'leadership', [
        'heading' => 'The People behind Echos',
    ], 5);

    insertBlock($pdo, $aboutId, 'lets_deal', [
        'heading' => "The future belongs to AI-first enterprises. Let's build yours",
        'cta_label' => 'Get In Touch',
        'cta_url' => '/contact',
    ], 6);

    // --------------------------------------------------- applications page
    insertBlock($pdo, $applicationsId, 'breadcrumb', [
        'heading' => 'Thinking Systems at Work',
        'subheading' => 'Explore enterprise ready AI applications that accelerate time to value and business impact.',
    ], 1);

    // --------------------------------------------------- perspectives page
    insertBlock($pdo, $perspectivesId, 'breadcrumb', [
        'heading' => 'Our Blogs',
        'subheading' => 'From vision to execution, we make intelligence work for you, at scale.',
    ], 1);

    // --------------------------------------------------------- contact page
    insertBlock($pdo, $contactId, 'intro', [
        'heading' => "Ready to build your AI-first enterprise? Let's talk.",
    ], 1);

    insertBlock($pdo, $contactId, 'locations_heading', [
        'heading' => 'Echos Inc. Offices',
    ], 2);

    // ------------------------------------------------------------ thank you page
    insertBlock($pdo, $thankYouId, 'content', [
        'heading' => 'Thanks for reaching out.',
        'body' => "We've received your message and a member of the Echos team will be in touch shortly.",
        'cta_label' => 'Back to Home',
        'cta_url' => '/',
    ], 1);

    // ----------------------------------------------------------- team members
    $team = $pdo->prepare(
        'INSERT INTO team_members (name, designation, bio, image_path, linkedin_url, sort_order) VALUES (:name, :designation, :bio, :image_path, :linkedin_url, :sort_order)'
    );
    $team->execute([
        'name' => 'Deepti Kalra',
        'designation' => 'Co-founder & CEO',
        'bio' => "As a leader in the World Economic Forum's AI-First Enterprise initiative and a Stanford speaker on enterprise AI, Deepti is helping shape how organizations build and scale AI-first enterprises. After more than 15 years leading AI strategy, AI products, and large-scale enterprise transformation, she co-founded Echos to engineer the thinking systems that power the next generation of AI-native organizations.",
        'image_path' => 'assets/img/inner-page/Deepti.png',
        'linkedin_url' => '#',
        'sort_order' => 1,
    ]);
    $team->execute([
        'name' => 'Amit Gaba',
        'designation' => 'Co-founder & COO',
        'bio' => "Recognized as a Crain's Notable Leader in Consulting and Finance and a Marquis Who's Who in America honoree, Amit has spent decades helping enterprises unlock value through M&A, finance transformation, and AI-driven operating models. He co-founded Echos to combine deep business strategy with AI-native engineering, helping organizations turn operational complexity into durable competitive advantage.",
        'image_path' => 'assets/img/inner-page/Amit.jpeg',
        'linkedin_url' => '#',
        'sort_order' => 2,
    ]);

    // ------------------------------------------------------------ industries
    $industry = $pdo->prepare(
        'INSERT INTO industries (icon_path, title, description, sort_order) VALUES (:icon_path, :title, :description, :sort_order)'
    );
    $industries = [
        ['assets/img/home-3/paisa.png', 'Insurance', 'Get faster claims resolution, improve risk selection, and achieve stronger actuarial accuracy with our insurance thinking systems.'],
        ['assets/img/home-3/health-care.png', 'Healthcare', 'Improve patient outcomes, reduce clinician burden, optimize operations, and sustain cost avoidance across all environments.'],
        ['assets/img/home-3/cog.png', 'Manufacturing', 'Streamline supply chain operations, improve demand forecasting accuracy, and minimize unplanned downtime to keep production running at full capacity.'],
        ['assets/img/home-3/energy.png', 'Energy', 'Optimize grid performance, predict peak demand, and strengthen operational resilience across the energy value chain.'],
        ['assets/img/home-3/public.png', 'Banking', 'Strengthen risk portfolios, reduce operational costs, increase developer productivity and improve decision-making to lower cost to serve.'],
    ];
    foreach ($industries as $i => $row) {
        $industry->execute([
            'icon_path' => $row[0],
            'title' => $row[1],
            'description' => $row[2],
            'sort_order' => $i + 1,
        ]);
    }

    // ----------------------------------------------------------- applications
    // Categories are named for the industry each application serves (not the
    // generic AI capability) so the /applications tab filter reads as
    // "Insurance / Banking / Manufacturing / Compliance" — manager-specified
    // order via sort_order, not alphabetical.
    $appCategory = $pdo->prepare('INSERT INTO application_categories (name, slug, sort_order) VALUES (:name, :slug, :sort_order)');
    $appCategoryIds = [];
    foreach ([['Insurance', 'insurance', 1], ['Banking', 'banking', 2], ['Manufacturing', 'manufacturing', 3], ['Compliance', 'compliance', 4]] as [$name, $slug, $sortOrder]) {
        $appCategory->execute(['name' => $name, 'slug' => $slug, 'sort_order' => $sortOrder]);
        $appCategoryIds[$name] = (int) $pdo->lastInsertId();
    }

    $app = $pdo->prepare(
        'INSERT INTO applications (title, slug, category_id, short_description, full_description, image_path, show_on_home, show_on_applications_page, sort_order)
         VALUES (:title, :slug, :category_id, :short_description, :full_description, :image_path, :show_on_home, :show_on_applications_page, :sort_order)'
    );

    // The 3 cards from the homepage "Thinking Systems at Work" slider —
    // distinct products from the /applications grid in the source template.
    // Not shown on the /applications page, so no category tab applies.
    $homeOnly = [
        ['Banking Intelligence', 'banking-intelligence',
            'AI-powered banking intelligence platform that helps organizations analyze customer behavior, risk profiles, operational performance, and business insights through a unified dashboard.',
            'assets/img/home-1/Banking-intelligence.png'],
        ['Document Intelligence', 'document-intelligence',
            'Automated document extraction, classification, validation and intelligent processing using enterprise AI models.',
            'assets/img/home-1/document-intelligence.png'],
        ['Enterprise Command Center', 'enterprise-command-center',
            'Centralized AI operations hub for monitoring enterprise workflows, KPIs, governance and operational intelligence.',
            'assets/img/home-1/enterprise.png'],
    ];
    foreach ($homeOnly as $i => $row) {
        $app->execute([
            'title' => $row[0], 'slug' => $row[1], 'category_id' => null,
            'short_description' => $row[2], 'full_description' => $row[2], 'image_path' => $row[3],
            'show_on_home' => 1, 'show_on_applications_page' => 0, 'sort_order' => $i + 1,
        ]);
    }

    // The 4 cards from the /applications grid in the source template.
    $applicationsOnly = [
        ['SOX ITGC Intelligence', 'sox-itgc-intelligence', 'Compliance',
            'SOX ITGC thinking system that continuously monitors 100% of user access across enterprise platforms,',
            'SOX ITGC thinking system that continuously monitors 100% of user access across enterprise platforms, enforcing existing ITGC controls while using AI to discover new toxic combinations, learning from auditor feedback to surface only high-signal alerts. Testing cycles drop from weeks to minutes with 100% population coverage, violations worth millions are caught proactively, and AI-generated emerging controls adapt as your environment evolves.',
            'assets/img/inner-page/SOX ITGC Intelligence.png'],
        ['Customer 360 intelligence & relationship management', 'customer-360-intelligence-relationship-management', 'Banking',
            'Retail banking marketing thinking system that unifies financial data',
            'Retail banking marketing thinking system that unifies financial data, predicted customer lifetime value, life event detection, and behavioral signals into enriched customer 360° profiles with AI-generated personalized product recommendations and campaign-ready creatives, based on latest customer interactions surfacing only high-value opportunities. Relationship managers can now act on the right insight at the right moment without toggling between disparate systems to close any retention gaps using predicted vs. actual CLTV, drive higher conversion rates, reduce churn while expanding customer lifetime value all with full regulatory compliance.',
            'assets/img/inner-page/Banking Marketing Intelligence.png'],
        ['P&C Claims Intelligence', 'pc-claims-intelligence', 'Insurance',
            'Claims intelligence thinking system that unifies policy, claims data, and third-party sources into AI-enriched snapshots',
            'Claims intelligence thinking system with claims summaries and recommended next steps, full process visibility from FNOL till closed claims including loss and claim signals that are available for underwriters and actuaries for improved risk selection. Claims resolved in less than a day instead of weeks, adjudicator insights captured and shared in real-time, reducing loss ratios and preventing millions in leakage while continuously improving underwriting profitability through automated signals.',
            'assets/img/inner-page/P&C Claims Intelligence.png'],
        ['Supply Chain Intelligence', 'supply-chain-intelligence', 'Manufacturing',
            'Inventory management thinking system that unifies ERP, WMS, and supplier portals into real-time inventory visibility',
            'Inventory management thinking system that unifies ERP, WMS, and supplier portals into real-time inventory visibility with AI-driven reorder recommendations, demand forecasting, and what-if scenario analysis showing financial impact with one-click execution to automate actions. Supply chain teams model decisions with cost visibility before executing, release millions in tied-up capital through optimized inventory levels, prevent stockouts with predictive reorder points, and automate workflows from decision to execution in minutes.',
            'assets/img/inner-page/Supply Chain Intelligence.png'],
    ];
    foreach ($applicationsOnly as $i => $row) {
        $app->execute([
            'title' => $row[0], 'slug' => $row[1], 'category_id' => $appCategoryIds[$row[2]],
            'short_description' => $row[3], 'full_description' => $row[4], 'image_path' => $row[5],
            'show_on_home' => 0, 'show_on_applications_page' => 1, 'sort_order' => $i + 1,
        ]);
    }

    // ------------------------------------------------------------------ blog
    $category = $pdo->prepare('INSERT INTO blog_categories (name, slug) VALUES (:name, :slug)');
    $category->execute(['name' => 'Engineering', 'slug' => 'engineering']);
    $categoryId = (int) $pdo->lastInsertId();

    // Only one blog post has real authored content in the source template
    // (blog-detail.php); the blog.php listing's other two cards are unlinked
    // placeholder filler with no article body, so they are not seeded here.
    $body = <<<'BODY'
<p>Every generation of technology moves engineering toward the next bottleneck, and the engineers who define that era are the ones who understood where value actually moved. Mainframes rewarded reliability because computation was scarce. The internet rewarded scale because connectivity became abundant. Mobile rewarded experience because computing moved into everyone's hands. Cloud rewarded platform-building because infrastructure became programmable. AI rewards something that none of these waves demanded in quite the same way: context.</p>

<h2>The Bottleneck Has Moved</h2>
<p>For decades, enterprise software was constrained by the cost of building it, and the engineers who mattered most were the ones who could do it faster, cheaper, and at scale. That constraint is rapidly disappearing, and with it, the assumptions that shaped how enterprise technology was built and deployed. Frontier models are more capable than they have ever been, and software that once required months of engineering effort can now be assembled in hours. The tools are accessible, the cost is approaching zero, and yet enterprise AI continues to stall at the same stage it has always stalled. The difficulty has not disappeared. It has shifted to a place that most engineering disciplines were never designed to address. When intelligence becomes abundant, the question is no longer how to generate it but how to apply it correctly inside a specific organisation, with its specific workflows, its specific constraints, and the institutional knowledge that shapes how decisions actually get made. That is no longer a software question. It is an operating model question, and it demands a fundamentally different kind of engineering to answer it.</p>

<h3>What Changes When Intelligence Is Abundant</h3>
<p>Every major technology wave changed what organisations could build. AI is changing something more fundamental: what organisations should become. When intelligence was scarce, organisations designed themselves around that scarcity. Decisions were centralised because expertise was concentrated. Processes were structured to compensate for limited information. Bottlenecks were accepted as fixed costs because eliminating them was too expensive to justify. When intelligence stops being scarce, every one of those design choices stops making sense. The question shifts from where to apply expertise to how to redesign the organisation so that intelligence flows into every decision, every workflow, and every interaction at the point where it creates the most value. That requires rethinking where decisions should be made, what humans should continue to own, what machines should continuously improve, and how the organisation captures learning from every outcome it produces. This is not an engineering problem in the traditional sense, but it requires engineers to solve it and not the kind most organisations currently have embedded in their AI programs.</p>

<h3>Engineering Is No Longer Just About Building</h3>
<p>One pattern has become clear across enterprise AI engagements: building intelligent systems is only half of the engineering challenge, and it is often the easier half. Most engineering disciplines are optimised for that first half. Architects design systems, developers build them, and the hand-off point is go-live, at which point the work is considered complete. AI deployments do not end at go-live. That is precisely where the real work begins. The moment a system meets a real organisation, it encounters legacy infrastructure that was never designed to interoperate, compliance constraints that only surface in production, institutional knowledge that lives outside any documented process, and stakeholders with competing definitions of what success means. Intelligence at scale does not emerge from a well-scoped project delivered on time. It emerges when human expertise and machine intelligence reason continuously over a shared enterprise context, and when every decision feeds back into how the organisation learns and operates. Getting there requires an engineer willing to stay embedded long after most engineering engagements have formally closed.</p>

<h3>The Engineering Discipline This Era Demands</h3>
<p>Forward Deployed Engineers are not software engineers with client-facing responsibilities added to their job description. That framing misses what the role actually demands. An FDE earns the enterprise context that no requirements document ever fully captures. They work inside client environments, against real data, navigating broken pipelines, building under constraints that only become visible when a system is running in production. The closest analogy is a founder: someone who walks into an organisation without a clear playbook, determines what success looks like before building toward it, and creates something that did not exist before they arrived. What distinguishes this kind of engineer is not technical depth alone, though that remains necessary. It is a specific kind of judgment: the ability to understand how an organisation currently works, recognise why that is no longer sufficient, and find a credible path forward without a map. It is the ability to build the right things in the right sequence alongside people who did not always ask for the change. It is the ability to earn trust in an environment where trust is the only thing that makes the work possible. As AI makes intelligence increasingly abundant, that judgment becomes the scarcest and most consequential capability in the room.</p>

<h3>The Engineers Who Will Define This Era</h3>
<p>Every generation of technology creates a new engineering discipline because every generation changes where engineering creates value. Competitive advantage in the AI era will not come from access to better models. Every enterprise will soon have access to increasingly capable AI as a matter of course, in the same way they have access to cloud infrastructure or modern development frameworks: essential, broadly available, and no longer a source of differentiation on their own. Advantage will come from something much harder to replicate: the ability to build a shared enterprise context, connect human expertise with machine intelligence, and continuously redesign how the organisation operates as both evolve together. Forward Deployed Engineers are how that happens in practice. The defining engineers of this era will not simply build better software. They will build organisations that get smarter with every decision they make. At Echos, our forward-deployed engineers work embedded within your organisation, from first deployment to enduring capability. If you are ready to move beyond experimentation and build intelligence into the fabric of how your enterprise operates, let's talk.</p>
BODY;

    $post = $pdo->prepare(
        'INSERT INTO blog_posts (category_id, title, slug, excerpt, body, featured_image, author_name, read_minutes, published_at, status)
         VALUES (:category_id, :title, :slug, :excerpt, :body, :featured_image, :author_name, :read_minutes, :published_at, :status)'
    );
    $post->execute([
        'category_id' => $categoryId,
        'title' => 'AI Is Changing the Nature of Engineering',
        'slug' => 'ai-is-changing-the-nature-of-engineering',
        'excerpt' => 'Every generation of technology moves engineering toward the next bottleneck. AI rewards something none of the previous waves demanded in quite the same way: context.',
        'body' => $body,
        'featured_image' => 'assets/img/inner-page/blog-post-3.jpg',
        'author_name' => 'Team Echos',
        'read_minutes' => 12,
        'published_at' => '2025-12-18 09:00:00',
        'status' => 'published',
    ]);

    // ---------------------------------------------------------- office locations
    $location = $pdo->prepare('INSERT INTO office_locations (name, slug, image_path, sort_order) VALUES (:name, :slug, :image_path, :sort_order)');
    $location->execute(['name' => 'New York, USA', 'slug' => 'usa', 'image_path' => 'https://images.unsplash.com/photo-1499092346589-b9b6be3e94b2?q=80&w=1200', 'sort_order' => 1]);
    $location->execute(['name' => 'India', 'slug' => 'india', 'image_path' => 'https://images.unsplash.com/photo-1529253355930-ddbe423a2ac7?q=80&w=1200', 'sort_order' => 2]);

    // --------------------------------------------------------------- menu items
    $menu = $pdo->prepare(
        'INSERT INTO menu_items (label, url, parent_id, location, sort_order) VALUES (:label, :url, :parent_id, :location, :sort_order)'
    );
    $menu->execute(['label' => 'Company', 'url' => '#', 'parent_id' => null, 'location' => 'header', 'sort_order' => 1]);
    $companyId = (int) $pdo->lastInsertId();
    $menu->execute(['label' => 'About', 'url' => '/about', 'parent_id' => $companyId, 'location' => 'header', 'sort_order' => 1]);
    $menu->execute(['label' => 'Perspectives', 'url' => '/perspectives', 'parent_id' => $companyId, 'location' => 'header', 'sort_order' => 2]);
    $menu->execute(['label' => 'Applications', 'url' => '/applications', 'parent_id' => null, 'location' => 'header', 'sort_order' => 2]);
    $menu->execute(['label' => 'Contact Us', 'url' => '/contact', 'parent_id' => null, 'location' => 'header', 'sort_order' => 3]);

    $menu->execute(['label' => 'Privacy Policy', 'url' => '#', 'parent_id' => null, 'location' => 'footer_left', 'sort_order' => 1]);
    $menu->execute(['label' => 'Terms & Conditions', 'url' => '#', 'parent_id' => null, 'location' => 'footer_left', 'sort_order' => 2]);
    $menu->execute(['label' => 'Contact Us', 'url' => '/contact', 'parent_id' => null, 'location' => 'footer_left', 'sort_order' => 3]);

    $menu->execute(['label' => 'About', 'url' => '/about', 'parent_id' => null, 'location' => 'footer_right', 'sort_order' => 1]);
    $menu->execute(['label' => 'Applications', 'url' => '/applications', 'parent_id' => null, 'location' => 'footer_right', 'sort_order' => 2]);
    $menu->execute(['label' => 'Perspectives', 'url' => '/perspectives', 'parent_id' => null, 'location' => 'footer_right', 'sort_order' => 3]);

    // ------------------------------------------------------------------ settings
    $setting = $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (:key, :value)');
    $setting->execute(['key' => 'site_name', 'value' => 'Echos']);
    $setting->execute(['key' => 'footer_copyright', 'value' => '© 2026 Echos. All rights reserved. Design & Developed By Vien Digital.']);
    $setting->execute(['key' => 'social_x_url', 'value' => '#']);
    $setting->execute(['key' => 'social_linkedin_url', 'value' => '#']);
    $setting->execute(['key' => 'contact_notify_email', 'value' => '']);
    $setting->execute(['key' => 'robots_txt_content', 'value' => "User-agent: *\nAllow: /\n"]);
    $setting->execute(['key' => 'site_logo', 'value' => '']);
    $setting->execute(['key' => 'site_tagline', 'value' => '']);
    $setting->execute(['key' => 'contact_phone', 'value' => '']);
    $setting->execute(['key' => 'contact_address', 'value' => '']);
    $setting->execute(['key' => 'default_og_image', 'value' => '']);
    $setting->execute(['key' => 'google_site_verification', 'value' => '']);
    $setting->execute(['key' => 'ga_measurement_id', 'value' => '']);
    $setting->execute(['key' => 'blog_enabled', 'value' => '0']);

    // --------------------------------------------------------------- admin user
    $email = 'abhishek@ezrankings.com';
    $temporaryPassword = bin2hex(random_bytes(6));
    $admin = $pdo->prepare(
        'INSERT INTO admin_users (name, email, password_hash, must_change_password) VALUES (:name, :email, :password_hash, 1)'
    );
    $admin->execute([
        'name' => 'Admin',
        'email' => $email,
        'password_hash' => password_hash($temporaryPassword, PASSWORD_DEFAULT),
    ]);

    $pdo->commit();

    echo "Seed complete.\n";
    echo "Admin login: {$email}\n";
    echo "Temporary password (change on first login): {$temporaryPassword}\n";
} catch (\Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, 'Seed failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
