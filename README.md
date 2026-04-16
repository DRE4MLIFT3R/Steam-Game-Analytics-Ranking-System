Project Title: Steam Game Analytics & Ranking System

The Steam Game Analytics & Ranking System is a web-based data platform designed to ingest, process, and visualize complex gaming datasets. By integrating game metadata, player activity, sales history, and user ratings, the system provides developers and product teams with actionable insights into market trends and game performance.

The core of the application is a robust relational MySQL database architecture tailored for handling both static metadata and high-frequency time-series data. Key entities include games, player statistics (current and peak counts), and discrete sale events. A scheduled ranking engine processes these data points to compute dynamic popularity scores, enabling the generation of live top-10 leaderboards and trend analysis.

Key Features:

    Automated Ranking Engine: A backend worker that aggregates telemetry and sales data to calculate real-time popularity rankings using weighted formulas.

    Data Visualization: Interactive frontend dashboards powered by Chart.js, featuring time-series growth graphs, discount impact analysis, and trend indicators (percentage change over 24h/7d).

    Telemetry Tracking: Systematic recording of player counts to identify peak activity windows and long-term engagement patterns.

    Marketing Optimization: Tools for studying the correlation between discount windows and player lift, helping platforms optimize sales strategies.

    Secure Infrastructure: Role-based access control (RBAC) with salted bcrypt/argon2 hashing and input sanitization to ensure data integrity and user privacy.

The project follows a three-phase roadmap, moving from an MVP focused on core schema and ranking jobs to a scaled production environment featuring performance tuning and advanced caching. Ultimately, the system serves as a critical bridge between raw telemetry and strategic decision-making in the competitive gaming industry.
