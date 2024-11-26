# Contributions Guide

Thank you for your interest in contributing to **Simple Tech Metrics**! This document explains the workflow and best practices we follow to ensure the project's success. Whether you're a beginner or an experienced developer, you're welcome here!

---

## 🌟 How to Contribute
You can contribute in several ways:
1. **Propose Features:** Have an idea to improve the plugin? Share it on GitHub.
2. **Fix Bugs:** Found an issue? Submit a bug report or a pull request with a fix.
3. **Submit Translations:** Add translations to make the plugin accessible in more languages.
4. **Improve Documentation:** Help us refine our documentation.

---

## 🚀 Workflow: Using Gitflow

We use **Gitflow** to manage our development process. This workflow ensures smooth collaboration and keeps the project well-organized.

### Key Branches:
1. **`master`**: The stable, production-ready branch. Only release versions go here.
2. **`develop`**: The active development branch. All features and bug fixes are merged here before being prepared for release.

### Working with Gitflow:
1. Create feature branches for new features:
   - **Branch naming:** `feature/<feature-name>`
   - Example: `feature/export-csv`
2. Create bugfix branches for fixing issues:
   - **Branch naming:** `bugfix/<issue-name>`
   - Example: `bugfix/fix-csv-export`
3. When ready, create a **pull request** to the `develop` branch.

---

## 👩‍💻 Gitflow Basics for Beginners

### Step 1: Fork and Clone the Repository
1. Fork the repository on GitHub.
2. Clone your fork:
   ```bash
   git clone https://github.com/<your-username>/wp-tech-metrics.git
   cd wp-tech-metrics
   ```

### Step 2: Set Up Gitflow

1. **Install Gitflow (if not already installed):**
   sudo apt install git-flow

2. **Initialize Gitflow in the project directory:**
   git flow init
   - Use the following branch names:
     - Main branch: master
     - Develop branch: develop

---

### Step 3: Create a Feature or Bugfix Branch

1. **Start a new branch:**
   git flow feature start <branch-name>
   Replace <branch-name> with a descriptive name (e.g., add-export-functionality).

2. **Make your changes, then commit them:**
   git add .
   git commit -m "Your descriptive commit message"

3. **Push your branch to your fork:**
   git push origin feature/<branch-name>

### 🔀 Pull Requests

### Step 1: Open a Pull Request
1. Go to the main repository on GitHub.
2. Open a pull request:
   - Base branch: develop
   - Compare branch: <your-branch-name>

3. Add a clear description of your changes.

### Step 2: Wait for Review
A project maintainer will review your changes and either approve, request changes, or provide feedback.
