<?php

/**
 * Adds the Terms of Service and Privacy Policy pages, seeded from the
 * live content at https://echo-s.ai/terms-of-service and
 * https://echo-s.ai/privacy-policy (fetched 2026-07-07).
 *
 * This is a PHP script rather than a .sql file (unlike every other
 * migration) because the source text is full of curly quotes and
 * apostrophes — hand-escaping that into SQL string literals is exactly
 * the kind of thing that caused the mojibake bug in migration 004.
 * Going through Connection::get() (PDO, utf8mb4 charset set in the DSN
 * itself) and bound parameters sidesteps escaping entirely.
 *
 * Idempotent — safe to run more than once. Run once: php database/migrations/007_add_legal_pages.php
 */

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Database\Connection;

$pdo = Connection::get();

function upsertLegalPage(PDO $pdo, string $slug, string $seoTitle, string $seoDescription, string $heading, string $subheading, string $body): void
{
    $stmt = $pdo->prepare('SELECT id FROM pages WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $pageId = $stmt->fetchColumn();

    if ($pageId === false) {
        $stmt = $pdo->prepare('INSERT INTO pages (slug, seo_title, seo_description) VALUES (:slug, :title, :description)');
        $stmt->execute(['slug' => $slug, 'title' => $seoTitle, 'description' => $seoDescription]);
        $pageId = (int) $pdo->lastInsertId();
    } else {
        $pageId = (int) $pageId;
    }

    $stmt = $pdo->prepare('SELECT id FROM page_blocks WHERE page_id = :page_id AND block_key = :block_key');
    $stmt->execute(['page_id' => $pageId, 'block_key' => 'content']);

    if ($stmt->fetchColumn() === false) {
        $stmt = $pdo->prepare(
            'INSERT INTO page_blocks (page_id, block_key, heading, subheading, body, sort_order)
             VALUES (:page_id, :block_key, :heading, :subheading, :body, 0)'
        );
        $stmt->execute([
            'page_id' => $pageId,
            'block_key' => 'content',
            'heading' => $heading,
            'subheading' => $subheading,
            'body' => $body,
        ]);
        echo "Seeded page_blocks for '{$slug}'.\n";
    } else {
        echo "Skipped '{$slug}' — content block already exists.\n";
    }
}

$termsBody = <<<'HTML'
<p>These Terms of Service ("Terms") govern the professional services relationship between Echos ("Company," "we," "us," or "our") and your organization ("you" or "your"). By engaging our services through a Statement of Work (SOW), you agree to these Terms.</p>
<p>Echos is a deep-tech engineering firm headquartered in New York, with operations in the US, UK, Australia, UAE, and India. We provide consulting and deep-tech implementation expertise on third-party platforms.</p>

<h2>2. Services</h2>
<p>We provide deep-tech implementation and consulting on platforms including Palantir Foundry, OpenAI, Anthropic, Nvidia, and Databricks. Our services include:</p>
<ul>
<li>Zero-to-one solution development.</li>
<li>Custom enterprise operating systems.</li>
<li>AI and data architecture consulting.</li>
</ul>
<p>We primarily serve the insurance, banking, healthcare, industrial, energy, and supply chain sectors.</p>

<h2>3. Your Responsibilities</h2>
<p>To deliver our services, you agree to:</p>
<ul>
<li><strong>Platform Access:</strong> Provide us with necessary access to your own third-party platform instances (e.g., your Databricks workspace).</li>
<li><strong>Licensing:</strong> Maintain active, valid licenses for any third-party platforms required for the project.</li>
<li><strong>Data Quality:</strong> Provide the accurate data and subject matter expertise needed for implementation.</li>
<li><strong>Compliance:</strong> Ensure your use of our implementations meets your specific regulatory requirements.</li>
</ul>

<h2>4. Third-Party Platforms Data Controller</h2>
<p>Our work depends on third-party technologies (Databricks, Palantir, etc.). You acknowledge that:</p>
<ul>
<li><strong>No Liability:</strong> We are not responsible for the performance, security, or availability of these third-party platforms.</li>
<li><strong>Platform Terms:</strong> You are bound by the terms and conditions of those platform providers.</li>
<li><strong>Dependencies:</strong> Delays caused by third-party platform outages or API changes are outside of our control.</li>
</ul>
<p>Echos acts as the "data controller", determining how and why your personal data is processed.</p>

<h2>5. Intellectual Property</h2>
<ul>
<li><strong>Your Data:</strong> You retain all rights to your proprietary data and pre-existing systems.</li>
<li><strong>Our IP:</strong> We retain ownership of our pre-existing methodologies, frameworks, and tools used during the engagement.</li>
<li><strong>Deliverables:</strong> Unless stated otherwise in an SOW, custom configurations and code developed specifically for you become your property upon full payment.</li>
</ul>

<h2>6. Security and Privacy</h2>
<p>We protect your data using:</p>
<ul>
<li><strong>Encryption:</strong> Industry-standard encryption for data at rest and in transit.</li>
<li><strong>Access Control:</strong> Multi-factor authentication (MFA) for all system access.</li>
<li><strong>Compliance:</strong> Security practices aligned with regulatory requirements.</li>
</ul>

<h2>7. Confidentiality</h2>
<p>Both parties agree to protect any proprietary information shared during the engagement. This obligation survives the end of our working relationship.</p>

<h2>8. Limitation of Liability</h2>
<p>To the maximum extent permitted by law, our total liability is capped at the amount you paid us for the specific services in the 12 months preceding the claim. We are not liable for indirect or consequential damages, or for issues caused by third-party platform failures.</p>

<h2>9. Termination</h2>
<p>Either party may terminate an engagement with 30 days' notice. Upon termination, you must revoke our access to your systems, and all outstanding fees become due.</p>

<h2>10. Governing Law</h2>
<p>These Terms are governed by the laws of the State of New York. Any disputes will be resolved in the courts of New York County.</p>

<h2>11. Contact</h2>
<p>For support or questions, please contact:</p>
<p>Email: <a href="mailto:Support@echo-s.ai">Support@echo-s.ai</a></p>
HTML;

$privacyBody = <<<'HTML'
<p>Welcome to Echos ("Echos", "we", "us", or "our"). We are committed to protecting your privacy and ensuring the security of any personal information you share with us. This Privacy Policy explains how we collect, use, store, and share your data when you use our website and services. It also outlines your rights and how to exercise them.</p>

<h2>Data Controller</h2>
<p>Echos acts as the "data controller", determining how and why your personal data is processed.</p>

<h2>Information We Collect</h2>
<p>We may collect the following categories of personal data:</p>
<ul>
<li><strong>Usage and Technical Data:</strong> IP address, browser type/version, device info, time zone, session details, and other metadata.</li>
<li><strong>Content Data:</strong> Any voice, text, or other content you provide, including uploads (e.g., audio files).</li>
<li><strong>Meeting / Interaction Data:</strong> If applicable — records, transcripts, summaries.</li>
<li><strong>Third-Party and Derived Data:</strong> Aggregated or anonymized usage insights.</li>
</ul>

<h2>How We Collect Data</h2>
<p>We collect data via:</p>
<ul>
<li><strong>Direct interactions:</strong> When you sign up, contact us, or provide information manually.</li>
<li><strong>Automated technologies:</strong> Cookies, tracking pixels, website analytics, logs.</li>
<li><strong>Meeting / Interaction Data:</strong> If applicable — records, transcripts, summaries.</li>
<li><strong>Third-party integrations:</strong> For example, calendar syncs or external services (if used).</li>
</ul>

<h2>Use of Your Data</h2>
<p>We process your personal data for purposes such as:</p>
<ul>
<li>Providing and maintaining our services.</li>
<li>Enhancing user experience through personalization and performance analytics.</li>
<li>Communication (e.g., support, announcements).</li>
<li>Legal and security compliance (e.g., fraud prevention, audits).</li>
<li>Aggregated analytics (without personally identifying you).</li>
</ul>

<h2>Data Sharing and Third Parties</h2>
<p>We may share data with:</p>
<ul>
<li>Affiliates and service partners, under contract and with safeguards (e.g., data processing agreements).</li>
<li>Third-party tools integrated into our platform (e.g., cloud providers, analytics, etc.).</li>
<li>Legal requests: if required by law or to protect legal rights.</li>
<li>Third-party integrations: for example, calendar syncs or external services (if used).</li>
</ul>
<p>We will implement safeguards for any international data transfers, using mechanisms and deploying global compliance policies.</p>

<h2>Security Measures</h2>
<p>We prioritize security through:</p>
<ul>
<li>Encryption of data in transit and at rest.</li>
<li>Access controls and authentication (e.g., MFA, time-limited tokens).</li>
<li>Regular audits, monitoring, and logging for accountability.</li>
<li>Retention and deletion policies to limit data exposure.</li>
</ul>

<h2>Data Retention</h2>
<p>We retain personal data only as long as necessary to:</p>
<ul>
<li>Deliver services or meet business needs.</li>
<li>Comply with legal, regulatory, or contractual obligations.</li>
<li>Respond to disputes or enforce agreements.</li>
</ul>

<h2>Children's Privacy</h2>
<p>Echos is not directed to, and does not knowingly collect data from, children under 16 to 18 years of age. If you believe personal information of a child has been collected, please contact us immediately.</p>

<h2>Your Privacy Rights</h2>
<p>Depending on your jurisdiction, you may have the right to:</p>
<ul>
<li>Access, correct, or delete your personal data.</li>
<li>Restrict or object to certain data processing (e.g., for marketing).</li>
<li>Withdraw consent where used.</li>
</ul>

<h2>Updates to This Policy</h2>
<p>We may update this Privacy Policy from time to time. When changes are material, we'll notify you via our website or by direct communication. Continued use of our services after updates implies acceptance.</p>

<h2>Contact Us</h2>
<p>For privacy questions or to exercise your data rights, please contact:</p>
<p>Email: <a href="mailto:info@echo-s.ai">info@echo-s.ai</a></p>
HTML;

upsertLegalPage(
    $pdo,
    'terms-of-service',
    'Terms of Service — Echos',
    'Terms of Service governing the professional services relationship between Echos and its clients.',
    'Terms of Service',
    'Effective Date: April 2, 2026',
    $termsBody
);

upsertLegalPage(
    $pdo,
    'privacy-policy',
    'Privacy Policy — Echos',
    'How Echos collects, uses, and protects your personal data.',
    'Privacy Policy',
    '',
    $privacyBody
);

// Activate the footer links, which were seeded pointing at '#'.
$pdo->prepare("UPDATE menu_items SET url = '/privacy-policy' WHERE label = 'Privacy Policy' AND url = '#'")->execute();
$pdo->prepare("UPDATE menu_items SET url = '/terms-of-service' WHERE label = 'Terms & Conditions' AND url = '#'")->execute();
echo "Footer links updated.\n";
