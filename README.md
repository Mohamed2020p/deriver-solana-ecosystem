Deriverse Pro Terminal - Setup Guide

This project is a high-fidelity, on-chain trading analytics dashboard built for the Solana ecosystem. It provides comprehensive PnL tracking, risk metrics, and a session-based trading journal.

🚀 Getting Started

Since this project is built using PHP, it requires a web server environment to execute the code. Follow the instructions below based on your setup.

1. Running Locally with XAMPP (Windows/Mac/Linux)

XAMPP is the easiest way to create a local testing environment.

Download & Install XAMPP:

Download it from apachefriends.org.

Follow the installation wizard.

Locate the htdocs folder:

Windows: C:\xampp\htdocs

Mac: /Applications/XAMPP/htdocs

Create a project folder:

Create a new folder inside htdocs named deriverse.

Add the project files:

Place your index.php file inside the deriverse folder.

Start the Servers:

Open the XAMPP Control Panel.

Click Start next to Apache. (You don't need MySQL for the current version as it uses a mock dataset).

View the Dashboard:

Open your web browser and go to: http://localhost/deriverse/index.php

2. Deploying to a Real Website (Live Hosting)

If you have a domain and web hosting (e.g., Bluehost, SiteGround, or a VPS):

Access your File Manager:

Log into your hosting control panel (cPanel or similar).

Navigate to the public_html directory.

Upload via FTP/SFTP:

Use a client like FileZilla.

Upload index.php to your root folder or a subdirectory.

Check Permissions:

Ensure the file permissions are set to 644 so it can be read by the web server.

Visit your URL:

Navigate to https://yourdomain.com/index.php.

🛠 Features Included

Solana Themed UI: Utilizing the official purple, blue, and green color palette.

Wallet Connection: Simulated dApp connection logic for Phantom and Solflare.

Advanced Analytics:

Equity Curve (Chart.js) with 7D/1M/All timeframes.

Risk metrics (Avg Win/Loss, Drawdown, Long/Short ratio).

Fee breakdown (Execution vs Network).

Dynamic Filtering: Real-time filtering by Order Type, Symbol, and Date.

📦 Dependencies

The project uses the following CDNs (No installation required if you have an internet connection):

Tailwind CSS: For premium styling and layout.

Chart.js: For high-performance data visualization.

Google Fonts: "Outfit" and "JetBrains Mono".

📝 Note on Data

The current version uses a Mock Dataset defined in the $all_trades PHP array at the top of index.php. To connect to a live Solana API or a real database, you can replace this array with a dynamic query.
