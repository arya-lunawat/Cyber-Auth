<?php

require_once '../app/Services/SessionManager.php';

SessionManager::start();

include 'includes/header.php';

?>

<!-- HERO SECTION -->
<section class="hero-center">

    <div class="hero-box">

        <h1>
            CyberAuth
        </h1>

        <p class="hero-subtitle">
            Learn SQL Injection Vulnerabilities Through Hands-On Demonstration
        </p>

        <div class="hero-actions">

            <a href="/secure/login.php" class="btn btn-success">
                Secure Login
            </a>

            <a href="/vulnerable/login.php" class="btn btn-danger">
                Vulnerable Login
            </a>

            <a href="/register.php" class="btn btn-secondary">
                Register
            </a>

        </div>

    </div>

</section>

<!-- FEATURES -->
<section class="section">

    <div class="container">

        <div class="section-title">

            <h2>Features</h2>

            <p>
                CyberAuth demonstrates how modern authentication should be implemented while comparing it with intentionally vulnerable implementations.
            </p>

        </div>

        <!-- STACKED FEATURE CARDS -->
        <div class="feature-grid">

            <div class="feature-card">

                <h3>Secure Authentication</h3>

                <p>
                    Uses password_hash(), password_verify(), prepared statements, secure sessions, account locking, login logging and role-based access.
                </p>

            </div>

            <div class="feature-card">

                <h3>SQL Injection Demo</h3>

                <p>
                    Demonstrates how raw SQL queries can be exploited using SQL Injection payloads for educational purposes.
                </p>

            </div>

            <div class="feature-card">

                <h3>Attack Monitoring</h3>

                <p>
                    Every login and attack attempt is logged and displayed inside the administrator dashboard.
                </p>

            </div>

            <div class="feature-card">

                <h3>Admin Dashboard</h3>

                <p>
                    View users, statistics, login history, attack payloads, and authentication methods.
                </p>

            </div>

        </div>

    </div>

</section>

<!-- ABOUT / EDUCATION BOX -->
<section class="section">

    <div class="container">

        <div class="glass-red-box">

            <h2>What is CyberAuth?</h2>

            <p>
                CyberAuth is an educational web application designed to demonstrate SQL Injection vulnerabilities and their remediation.
                It showcases both a vulnerable authentication system and a secure implementation, helping developers understand the importance of secure coding practices.
            </p>

            <h3>Key Learning Objectives</h3>

            <ul class="clean-list">
                <li>Understand SQL Injection and how attackers exploit unsanitized inputs</li>
                <li>Recognize common web application security vulnerabilities</li>
                <li>Implement secure coding practices such as prepared statements</li>
                <li>Use password hashing for authentication security</li>
                <li>Monitor and analyze attack patterns through logs</li>
            </ul>

            <h3>Quick Start</h3>

            <ul class="clean-list">
                <li>Open Vulnerable Login and test: <code>admin' OR '1'='1</code></li>
                <li>View detected attacks in the Admin Dashboard</li>
                <li>Test Secure Login to see protection in action</li>
                <li>Create an account and explore the user dashboard</li>
                <li>Compare secure and vulnerable implementations</li>
            </ul>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>